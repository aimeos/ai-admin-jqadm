/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */


Aimeos.Dashboard = {

	/**
	 * Returns a jQuery promise for the constructed request
	 *
	 * @param resource Resource name like "product", "order" or "order/base/address"
	 * @param key Aggregation key to group results by, e.g. "order.cdate", "order.base.address.countryid"
	 * @param criteria Polish notation object with conditions for limiting the results, e.g. {">": {"order.cdate": "2000-01-01"}}
	 * @param sort|null Optional sorting criteria like "order.cdate" (ascending) or "-order.cdate" (descending), also more then one separated by comma
	 * @param limit|null Optional limit for the number of records that are selected before aggregation (default: 25)
	 * @return jQuery promise object
	 */
	getData : function(resource, key, criteria, sort, limit) {

		return $.when( Aimeos.options ).then(function(data) {

			var params = {}, param = {};

			param["aggregate"] = key;
			param["filter"] = criteria;

			if(sort) {
				param["sort"] = sort;
			}

			if(limit) {
				param["page"] = {"limit": limit};
			}

			if( data.meta && data.meta.prefix ) {
				params[data.meta.prefix] = param;
			} else {
				params = param;
			}

			return $.ajax({
				dataType: "json",
				url: data.meta.resources[resource] || null,
				data: params
			});
		});
	}
};


Aimeos.Dashboard.Order = {

	init : function() {

		if( $(".order-paymentstatus").length ) {
			this.chartPaymentStatus();
		}

		if( $(".order-hour").length ) {
			this.chartHour();
		}

		if( $(".order-day").length ) {
			this.chartDay();
		}

		if( $(".order-paymenttype").length ) {
			this.chartPaymentType();
		}

		if( $(".order-deliverytype").length ) {
			this.chartDeliveryType();
		}
	},



	chartDay : function() {

		var selector = "#order-day-data",
			margin = {top: 20, bottom: 20, left: 15, right: 40},
			width = $(selector).width() - margin.left - margin.right,
			height = $(selector).height() - margin.top - margin.bottom - 10,
			cellPad = 2, cellSize = 16, cellWidth = cellSize + cellPad;

		var weeks = Math.ceil((width - cellWidth) / cellWidth),
			firstdate = new Date(new Date().getTime() - weeks * 7 * 86400 * 1000 + (6 - new Date().getDay()) * 86400 * 1000),
			dateRange = d3.utcDay.range(firstdate, new Date());


		var criteria = {">=": {"order.cdate": firstdate.toISOString().substr(0, 10)}};

		Aimeos.Dashboard.getData("order", "order.cdate", criteria, "-order.cdate", 10000).then(function(data) {

			if( typeof data.data == "undefined" ) {
				throw error;
			}

			var entries = {};
			data.data.forEach(function(d) { entries[d.id] = d.attributes; });

			var color = d3.scaleQuantize()
				.range(d3.range(9).map(function(d) { return "q" + (d+1); }))
				.domain([1, d3.max(data.data, function(d) { return +d.attributes; })]);

			var svg = d3.select(selector)
				.append("svg")
					.attr("width", width + margin.left + margin.right)
					.attr("height", height + margin.top + margin.bottom)
				.append("g")
					.attr("transform", "translate(" + margin.left + "," + ((height - cellWidth * 7) / 2 + margin.top) + ")");

			var cell = svg.selectAll(".day")
				.data(dateRange)
				.enter().append("rect")
					.attr("class", "day")
					.attr("width", cellSize)
					.attr("height", cellSize)
					.attr("x", function(d) {
						var d1 = new Date(d.getUTCFullYear(), 0, 1);
						var d51 = new Date(d.getUTCFullYear(), 11, 24);
						var d52 = new Date(firstdate.getUTCFullYear(), 11, 31);
						var first = d3.timeWeek.count(d3.timeYear(d1), d1);
						var weeks = d3.timeWeek.count(d3.timeYear(d52), d52);

						if(weeks == 1) { weeks = d3.timeWeek.count(d3.timeYear(d51), d51); }
						if(first == 0) { weeks += 1; }

						var result = d3.timeWeek.count(d3.timeYear(d), d) - d3.timeWeek.count(d3.timeYear(firstdate), firstdate) + (weeks * (d.getUTCFullYear() - firstdate.getUTCFullYear()));
						return result * cellWidth;
					})
					.attr("y", function(d) { return d.getUTCDay() * cellWidth; })
					.datum(d3.timeFormat("%Y-%m-%d"));

			cell.attr("class", function(d) { return "day " + (entries[d]>0 ? color(entries[d]) : "q0"); })
				.append("title").text(function(d) { return d + ": " + (entries[d] || 0); });


			// day of week initials left of the heat map
			["M","W","F"].forEach(function(day, i) {
				svg.append("text")
					.attr("transform", "translate(7.5," + ((cellWidth - 1) * (i + 1) * 2) + ")")
					.attr("class", "y axis")
					.text(day);
			});

			// month numbers on top of the heat map
			var dateNumbers = dateRange.map(Number);
			svg.selectAll(".legend-month")
				.data(d3.utcMonth.range(firstdate, new Date()))
				.enter().append("text")
					.text(function (d) { var num = d.getMonth(); return (num === 0 ? num = 12 : (num < 10 ? "0" + num : num)); })
					.attr("class", "x axis")
					.attr("y", -10)
					.attr("x", function (d) {
						var idx = dateNumbers.indexOf(+d);
						if(idx !== -1) { return Math.floor(idx / 7) * cellWidth; }
					});


			// outline of months in the heat map
			svg.selectAll(".path-month")
				.data(function() { return d3.timeMonths(firstdate, new Date()); })
				.enter().append("path")
					.attr("class", "path-month")
					.attr("d", function(t0) {

						if(t0.getFullYear() === new Date().getFullYear() && t0.getMonth() === new Date().getMonth()) {
							return;
						}

						var t1 = new Date(t0.getFullYear(), t0.getMonth() + 1, 0),
							d1 = new Date(t0.getUTCFullYear(), 0, 1),
							d51 = new Date(t0.getUTCFullYear(), 11, 24),
							d52 = new Date(firstdate.getUTCFullYear(), 11, 31),
							first = d3.timeWeek.count(d3.timeYear(d1), d1),
							weeks = d3.timeWeek.count(d3.timeYear(d52), d52),
							d0 = t0.getDay(), d1 = t1.getDay();

						if(weeks == 1) { weeks = d3.timeWeek.count(d3.timeYear(d52), d52); }
						if(first == 0) { weeks += 1; }

						var w0 = d3.timeWeek.count(d3.timeYear(t0), t0) - d3.timeWeek.count(d3.timeYear(firstdate), firstdate) + (weeks * (t1.getUTCFullYear() - firstdate.getUTCFullYear()));
						var w1 = d3.timeWeek.count(d3.timeYear(t1), t1) - d3.timeWeek.count(d3.timeYear(firstdate), firstdate) + (weeks * (t1.getUTCFullYear() - firstdate.getUTCFullYear()));

						result = "M" + ((w0 + 1) * cellWidth - 1) + "," + d0 * cellWidth
							+ "H" + (w0 * cellWidth - 1) + "V" + (7 * cellWidth - 1)
							+ "H" + (w1 * cellWidth - 1) + "V" + ((d1 + 1) * cellWidth - 1)
							+ "H" + ((w1 + 1) * cellWidth - 1) + "V" + 0
							+ "H" + ((w0 + 1) * cellWidth -1) + "Z";

						return result;
					});


			// color scale below the heat map
			var legend = svg.append("g")
				.attr("class", "legend-quantity")
				.attr("transform", "translate(" + (weeks - color.range().length) * cellWidth + "," + (7 * cellWidth + 10) + ")");

			legend.selectAll(".legend-days")
				.data(color.range())
				.enter()
				.append("rect")
					.attr("width", cellSize)
					.attr("height", cellSize)
					.attr("class", function (d) { return "legend-day " + d; })
					.attr("x", function (d, i) { return i * cellWidth; });

			legend.append("text")
				.attr("class", "legend-less")
				.attr("x", -cellWidth + 4)
				.attr("y", 13) // @todo calculate
				.text("-");

			legend.append("text")
				.attr("class", "legend-more")
				.attr("x", color.range().length * cellWidth + 4)
				.attr("y", 13) // @todo calculate
				.text("+");

		}).done(function() {
			$(selector).removeClass("loading");
		});
	},



	chartHour : function() {

		var selector = "#order-hour-data",
			margin = {top: 20, right: 30, bottom: 30, left: 40},
			width = $("#order-hour-data").width() - margin.left - margin.right,
			height = $("#order-hour-data").height() - margin.top - margin.bottom - 10;


		Aimeos.Dashboard.getData("order", "order.chour", {}, "-order.ctime", 1000).then(function(data) {

			if( typeof data.data == "undefined" ) {
				throw error;
			}

			var tzoffset = Math.floor((new Date()).getTimezoneOffset() / 60); // orders are stored with UTC timestamps

			var xScale = d3.scaleLinear().range([0, width]).domain([0,23]);
			var yScale = d3.scaleLinear().range([height, 0]).domain([0, d3.max(data.data, function(d) { return +d.attributes; })]);

			var xAxis = d3.axisBottom().scale(xScale);
			var yAxis = d3.axisLeft().scale(yScale);

			var svg = d3.select(selector)
				.append("svg")
					.attr("width", width + margin.left + margin.right)
					.attr("height", height + margin.top + margin.bottom)
				.append("g")
					.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

			svg.append("g")
				.attr("class", "x axis")
				.attr("transform", "translate(0," + height + ")")
				.call(xAxis);

			svg.append("g")
				.attr("class", "y axis")
				.call(yAxis);

			svg.selectAll(".bar")
					.data(data.data)
				.enter().append("rect")
					.attr("class", "bar")
					.attr("x", function(d) { return xScale((+d.id - tzoffset) % 24); })
					.attr("width", width / 24 - 2)
					.attr("y", function(d) { return yScale(+d.attributes); })
					.attr("height", function(d) { return height - yScale(+d.attributes); });

		}).done(function() {
			$(selector).removeClass("loading");
		});
	},



	chartDeliveryType : function() {

		var criteria = {"==": {"order.base.service.type": "delivery"}};
		this.drawDonut("#order-deliverytype-data", "order", "order.base.service.code", criteria, "-order.ctime", 1000);
	},



	chartPaymentType : function() {

		var criteria = {"==": {"order.base.service.type": "payment"}};
		this.drawDonut("#order-paymenttype-data", "order", "order.base.service.code", criteria, "-order.ctime", 1000);
	},



	chartPaymentStatus : function() {

		var dates = [],
			selector = "#order-paymentstatus-data",
			translation = $(selector).data("translation"),
			margin = {top: 20, right: 60, bottom: 30, left: 40},
			width = $(selector).width() - margin.left - margin.right,
			height = $(selector).height() - margin.top - margin.bottom - 10,
			statusrange = ["pay-unfinished", "pay-deleted", "pay-canceled", "pay-refused", "pay-refund", "pay-pending", "pay-authorized", "pay-received"],
			statuslist = ['-1', '0', '1', '2', '3', '4', '5', '6'],
			numdays = width / 25;

		for( var i = 0; i < numdays; i++ ) {
			dates.push(new Date(new Date().getTime() - (numdays - i - 1) * 86400 * 1000 ).toISOString().substr(0, 10));
		}


		var criteria = {"&&": [{">=": {"order.cdate": dates[0]}}, {"<=": {"order.cdate": dates[dates.length-2]}}]};

		Aimeos.Dashboard.getData("order", "order.cdate", criteria, '-order.cdate', 100000).then(function(data) {

			if( typeof data.data == "undefined" ) {
				throw 'No data in response';
			}

			var dateParser = d3.timeParse("%Y-%m-%d");

			var color = d3.scaleOrdinal().domain(statuslist).range(statusrange);
			var xScale = d3.scaleTime().range([0, width]).domain([dateParser(dates[0]), dateParser(dates[dates.length-1])]);
			var yScale = d3.scaleLinear().range([height, 0]).domain([0, d3.max(data.data, function(d) { return +d.attributes; })]);

			var xAxis = d3.axisBottom().scale(xScale).ticks(numdays/3);
			var yAxis = d3.axisLeft().scale(yScale);

			var svg = d3.select(selector)
				.append("svg")
					.attr("width", width + margin.left + margin.right)
					.attr("height", height + margin.top + margin.bottom)
				.append("g")
					.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

			svg.append("g")
				.attr("class", "x axis")
				.attr("transform", "translate(0," + height + ")")
				.call(xAxis);

			svg.append("g")
				.attr("class", "y axis")
				.call(yAxis);


			if( data.data.length == 0 ) { // no data avaiable
				$(selector).removeClass("loading");
				return;
			}


			var res = [], entries = {};

			dates.forEach(function(date) {
				var pdate = dateParser(date);
				entries[date] = {
					'-1': {'key': pdate, 'status': '-1', 'y0': 0, 'y1': 0},
					'0': {'key': pdate, 'status': '0', 'y0': 0, 'y1': 0},
					'1': {'key': pdate, 'status': '1', 'y0': 0, 'y1': 0},
					'2': {'key': pdate, 'status': '2', 'y0': 0, 'y1': 0},
					'3': {'key': pdate, 'status': '3', 'y0': 0, 'y1': 0},
					'4': {'key': pdate, 'status': '4', 'y0': 0, 'y1': 0},
					'5': {'key': pdate, 'status': '5', 'y0': 0, 'y1': 0},
					'6': {'key': pdate, 'status': '6', 'y0': 0, 'y1': 0},
					'total': 0
				};
			});

			// create a JSON request for every status value (-1 till 6)
			for(var i=0; i<statuslist.length; i++) {
				var criteria = {"&&": [
					{">=": {"order.cdate": dates[0]}},
					{"<=": {"order.cdate": dates[dates.length-1]}},
					{"==": {"order.statuspayment": statuslist[i]}}
				]};

				res.push(Aimeos.Dashboard.getData("order", "order.cdate", criteria, '-order.cdate', 10000));
			};

			// draw a new layer for each status value
			var drawLayer = function(status, data) {
				if( typeof data.data == "undefined" ) {
					throw 'No data in response';
				}

				data.data.forEach(function(d) {
					entries[d.id][status]['count'] = (+d.attributes);
					entries[d.id][status]['y0'] = entries[d.id]['total'];
					entries[d.id][status]['y1'] = entries[d.id]['total'] + (+d.attributes);
					entries[d.id]['total'] += (+d.attributes);
				});

				dates.forEach(function(d) {
					svg.append("rect")
						.datum(entries[d][status])
						.attr("class", function(d) { return "bar " + color(d.status); })
						.attr("x", function(d) { return xScale(d.key); })
						.attr("width", width / dates.length - 2)
						.attr("y", function(d) { return yScale(d.y1); })
						.attr("height", function(d) { return height - yScale(d.y1 - d.y0); });
				});
			};


			// order is maintained by waiting for the promise of the status value
			$.when(res[0], res[1], res[2], res[3], res[4], res[5], res[6], res[7])
				.done(function(dt0, dt1, dt2, dt3, dt4, dt5, dt6, dt7) {
					drawLayer('-1', dt0[0]);
					drawLayer('0', dt1[0]);
					drawLayer('1', dt2[0]);
					drawLayer('2', dt3[0]);
					drawLayer('3', dt4[0]);
					drawLayer('4', dt5[0]);
					drawLayer('5', dt6[0]);
					drawLayer('6', dt7[0]);


					statuslist.reverse(); // print counts per status in descending order

					// interactive chart details
					var tooltip = $('<div class="tooltip" />').appendTo($(selector));

					svg.append("rect")
						.attr("class", "overlay")
						.attr("width", width+25)
						.attr("height", height)
						.on("mouseover", function() { tooltip.css("display", "block"); })
						.on("mouseout", function() { tooltip.css("display", "none"); })
						.on("mousemove", function() {

							// now tooltip for the date in the diagram
							var x0 = xScale.invert(d3.mouse(this)[0]),
								mouseX = xScale(x0),
								curdate, i;

							for(i=0; i<dates.length; i++) {
								curdate = dates[i];

								if(i === dates.length-1 || x0 >= dateParser(dates[i]) && x0 < dateParser(dates[i+1])) {
									break;
								}
							}

							var html = '<h1 class="head">' + curdate + '</h1><table class="values">';
							statuslist.forEach(function(status) {
								html += '<tr><th>' + translation[status] + "</th><td>" + (entries[curdate][status]['count'] || 0) + '</td></tr>';
							});
							html += '</table>';

							// avoid pointer being inside the tooltip to prevent flickering
							// dispay tooltip right or left of the pointer depending on the position in the diagram
							tooltip.html(html)
								.css("top", margin.top)
								.css("left", (mouseX - width / 2 > 0 ? mouseX - tooltip.outerWidth() : mouseX + 20) + margin.left);
						});

					$(selector).removeClass("loading");
				}
			);
		});
	},



	drawDonut : function(selector, resource, key, criteria, sort, limit) {

		var lgspace = 190, txheight = 20,
			margin = {top: 20, right: 20, bottom: 20, left: 20},
			width = $(selector).width() - margin.left - margin.right,
			height = $(selector).height() - margin.top - margin.bottom - 10,
			radius = (width - lgspace > height ? Math.min(width - lgspace, height) : Math.min(width, height) ) / 2;


		Aimeos.Dashboard.getData(resource, key, criteria, sort, limit).then(function(data) {

			if( typeof data.data == "undefined" ) {
				throw error;
			}

			var color = d3.scaleOrdinal(d3.schemeCategory20);
			var sum = d3.sum(data.data, function(d) { return d.attributes; });

			var arc = d3.arc()
				.outerRadius(radius)
				.innerRadius(radius - 50);

			var pie = d3.pie()
				.padAngle(.02)
				.sort(null)
				.value(function(d) { return +d.attributes; });

			var svg = d3.select(selector)
				.append("svg")
					.attr("width", width + margin.left + margin.right)
					.attr("height", (width - lgspace > height ? height + margin.top + margin.bottom : radius * 2 + margin.top + data.data.length * txheight + 25 ));

			var donut = svg.append("g")
				.attr("transform", "translate(" + (radius + margin.left) + "," + (radius + margin.top) + ")")
				.selectAll(".arc")
					.data(pie(data.data))
					.enter()
					.append("g")
						.attr("class", "arc");

			donut.append("path")
				.attr("d", arc)
				.style("fill", function(d, i) { return color(i); });

			donut.append("text")
				.attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
				.attr("dx", "-1.25em")
				.attr("dy", ".35em")
				.text(function(d) {
					var perc = Math.round(d.data.attributes / sum * 1000) / 10;
					return ( perc > 5.0 ? perc + "%" : "" );
				});

			var legend = svg.selectAll(".legend")
				.data(data.data)
				.enter()
				.append("g")
					.attr("class", "legend-item");

			legend.append("rect")
				.style("fill", function(d, i) { return color(i); })
				.attr("height", 10)
				.attr("width", 10)
				.attr("transform", function(d, i) {
					if(width - lgspace > height) {
						return "translate(" + (radius * 2 + margin.left + 25) + "," + (margin.top + i * txheight + 10) + ")";
					} else {
						return "translate(" + margin.left + "," + (radius * 2 + margin.top + 25 + i * txheight) + ")";
					}
				});

			legend.append("text")
				.text(function(d, i) { return d.id; })
				.attr("transform", function(d, i) {
					if(width - lgspace > height) {
						return "translate(" + (radius * 2 + margin.left + 50) + "," + (margin.top + i * txheight + 20) + ")";
					} else {
						return "translate(" + (margin.left + 25) + "," + (radius * 2 + margin.top + 25 + i * txheight + 10) + ")";
					}
				});

		}).done(function() {
			$(selector).removeClass("loading");
		});
	}
};


$(function() {

	Aimeos.Dashboard.Order.init();
});
