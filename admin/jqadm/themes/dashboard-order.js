/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */


Aimeos.Dashboard.Order = {

	init : function() {

		if( $(".order-counthour").length ) {
			this.chartHour();
		}

		if( $(".order-countday").length ) {
			this.chartDay();
		}

		if( $(".order-countpaystatus").length ) {
			this.chartPaymentStatus();
		}
	},



	chartDay : function() {

		var selector = "#order-countday-data",
			margin = {top: 20, bottom: 20, left: 30, right: 35},
			width = $(selector).width() - margin.left - margin.right,
			height = $(selector).height() - margin.top - margin.bottom - 10,
			cellPad = 2, cellSize = 16, cellWidth = cellSize + cellPad;

		var weeks = Math.ceil((width - cellWidth) / cellWidth),
			firstdate = new Date(new Date().getTime() - weeks * 7 * 86400 * 1000 + (6 - new Date().getDay()) * 86400 * 1000),
			dateRange = d3.utcDay.range(firstdate, new Date());


		var criteria = {">=": {"order.cdate": firstdate.toISOString().substr(0, 10)}};

		Aimeos.Dashboard.getData("order", "order.cdate", criteria, "-order.cdate", 10000).done(function(data) {

			if( typeof data.data == "undefined" ) {
				throw 'No data in response';
			}

			var lang = $(".aimeos").attr("lang") || "en";
			var numFmt = new Intl.NumberFormat(lang);

			var entries = {};
			data.data.forEach(function(d) { entries[d.id] = +d.attributes; });

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
				.enter()
				.append("rect")
					.attr("class", "day")
					.attr("width", cellSize)
					.attr("height", cellSize)
					.attr("x", function(d) { return d3.timeWeek.count(firstdate, d) * cellWidth; })
					.attr("y", function(d) { return d.getUTCDay() * cellWidth; })
					.datum(d3.timeFormat("%Y-%m-%d"));

			cell.attr("class", function(d) { return "day " + (entries[d]>0 ? color(entries[d]) : "q0"); })
				.append("title").text(function(d) { return d + ": " + numFmt.format(entries[d] || 0); });


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
				.data(d3.utcMonth.range(new Date(firstdate.getTime() + 28*24*3600*1000), new Date()))
				.enter().append("text")
				.text(function (d) { var num = d.getMonth(); return (num === 0 ? num = 12 : (num < 10 ? "0" + num : num)); })
				.attr("class", "x axis")
				.attr("y", -10)
				.attr("x", function (d) {
					var idx = dateNumbers.indexOf(+d);
					if(idx != -1) { return (Math.floor(idx / 7) - 1) * cellWidth; }
				});


			// outline of months in the heat map
			svg.selectAll(".path-month")
				.data(function() { return d3.timeMonths(firstdate, new Date()); })
				.enter().append("path")
				.attr("class", "path-month")
					.attr("d", function(m0) {

						if(m0.getFullYear() === new Date().getFullYear() && m0.getMonth() === new Date().getMonth()) {
							return;
						}

						var m1 = new Date(m0.getFullYear(), m0.getMonth() + 1, 0);
						var w0 = d3.timeWeek.count(firstdate, m0)
						var w1 = d3.timeWeek.count(firstdate, m1)

						result = "M" + ((w0 + 1) * cellWidth - 1) + "," + m0.getDay() * cellWidth
							+ "H" + (w0 * cellWidth - 1) + "V" + (7 * cellWidth)
							+ "H" + (w1 * cellWidth - 1) + "V" + ((m1.getDay() + 1) * cellWidth)
							+ "H" + ((w1 + 1) * cellWidth - 1) + "V" + 0
							+ "H" + ((w0 + 1) * cellWidth - 1) + "Z";

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

		}).always(function() {
			$(selector).removeClass("loading");
		});
	},



	chartHour : function() {

		var selector = "#order-counthour-data",
			margin = {top: 20, bottom: 40, left: 50, right: 20},
			width = $(selector).width() - margin.left - margin.right,
			height = $(selector).height() - margin.top - margin.bottom - 10;

		var tzoffset = Math.floor((new Date()).getTimezoneOffset() / 60); // orders are stored with UTC timestamps

		var xScale = d3.scaleBand().range([0, width]).domain(d3.range(0, 24, 1)).paddingInner(0.15);
		var xAxis = d3.axisBottom().scale(xScale);

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


		Aimeos.Dashboard.getData("order", "order.chour", {}, "-order.ctime", 10000).done(function(data) {

			if( typeof data.data == "undefined" ) {
				throw 'No data in response';
			}

			var yScale = d3.scaleLinear().range([height, 0]).domain([0, d3.max(data.data, function(d) { return +d.attributes; })]);
			var yAxis = d3.axisLeft().scale(yScale).ticks(7).tickFormat(function(d) { return numFmt.format(d); });

			var lang = $(".aimeos").attr("lang") || "en";
			var numFmt = new Intl.NumberFormat(lang);

			svg.append("g")
				.attr("class", "y axis left")
				.call(yAxis);

			var bars = svg.selectAll(".barcnt")
				.data(data.data).enter()
				.append("g").attr("class", "barcnt");

			bars.append("rect")
				.attr("class", "bar")
				.attr("x", function(d) { return xScale(((+d.id - tzoffset)) % 24); })
				.attr("y", function(d) { return yScale(+d.attributes); })
				.attr("width", xScale.bandwidth())
				.attr("height", function(d) { return height - yScale(+d.attributes); })
				.append("title").text(function(d){ return numFmt.format(d.attributes); });

		}).always(function() {
			$(selector).removeClass("loading");
		});
	},



	chartPaymentStatus : function() {

		var selector = "#order-countpaystatus-data",
			margin = {top: 20, bottom: 40, left: 50, right: 20},
			width = $(selector).width() - margin.left - margin.right,
			height = $(selector).height() - margin.top - margin.bottom - 10,
			bandwidth = 25;

		var statuslist = ['-1', '0', '1', '2', '3', '4', '5', '6'],
			statusrange = [
				"pay-unfinished", "pay-deleted", "pay-canceled", "pay-refused",
				"pay-refund", "pay-pending", "pay-authorized", "pay-received"
			];

		var numdays = Math.floor(width / bandwidth),
			firstdate = new Date(new Date().getTime() - numdays * 86400 * 1000),
			dateRange = d3.utcDay.range(firstdate, new Date());

		var lang = $(".aimeos").attr("lang") || "en",
			dateFmt = new Intl.DateTimeFormat(lang, {month: "numeric", day: "numeric"});


		var colorScale = d3.scaleOrdinal().domain(statuslist).range(statusrange);
		var xScale = d3.scaleTime().range([0, width]).domain([firstdate, new Date()]);
		var xAxis = d3.axisBottom().scale(xScale).ticks(numdays/2).tickFormat(function(d) { return dateFmt.format(d); });

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


		// Move ticks and label to the middle of the bars
		svg.selectAll(".tick line").attr("transform", "translate(" + (bandwidth / 2 - 2) + ",0)");
		svg.selectAll(".tick text").attr("transform", "translate(" + (bandwidth / 2 - 2) + ",0)");


		var criteria, promises = {}, result = {};

		for(var i=0; i<statuslist.length; i++) {

			criteria = {"&&": [
				{">": {"order.cdate": firstdate.toISOString().substr(0, 10)}},
				{"==": {"order.statuspayment": statuslist[i]}}
			]};

			promises[statuslist[i]] = Aimeos.Dashboard.getData("order", "order.cdate", criteria, "-order.cdate", 10000);
		}

		jQuery.each(promises, function(status, promise) {
			promise.done(function(data) {

				if(typeof data.data == "undefined") {
					throw 'No data in response';
				}

				for(var i=0; i<data.data.length; i++) {
					result[data.data[i].id] = result[data.data[i].id] || {};
					result[data.data[i].id][status] = +data.data[i].attributes;
				}
			});
		});


		$.when.apply($, Object.values(promises)).done(function() {

			var dateParser = d3.timeParse("%Y-%m-%d");
			var numFmt = Intl.NumberFormat(lang);

			var stack = d3.stack().keys(statuslist).value(function(d, key) { return d[1][key] || 0; });
			var max = d3.max(Object.entries(result), function(d) { return d3.sum(Object.values(d[1])); });

			var yScale = d3.scaleLinear().range([height, 0]).domain([0, max]);
			var yAxis = d3.axisLeft().scale(yScale).ticks(7).tickFormat(function(d) { return numFmt.format(d); });

			svg.append("g")
				.attr("class", "y axis")
				.call(yAxis);

			svg.append("g")
				.selectAll("g")
				.data(stack(Object.entries(result)))
				.enter().append("g")
					.attr("class", function(d) { return colorScale(d.key); })
					.selectAll("rect")
					.data(function(d) { return d; })
					.enter().append("rect")
						.attr("class", "barcnt")
						.attr("x", function(d) { return xScale(dateParser(d.data[0])) - 1; })
						.attr("y", function(d) { return yScale(d[1]); })
						.attr("height", function(d) { return yScale(d[0]) - yScale(d[1]); })
						.attr("width", width/numdays - 2);


			statuslist.reverse(); // print counts per status in descending order

			// interactive chart details
			var tooltip = $('<div class="tooltip" />').appendTo($(selector));
			var translation = $(selector).data("translation");

			svg.append("rect")
				.attr("class", "overlay")
				.attr("width", width + 25)
				.attr("height", height)
				.on("mouseover", function() { tooltip.css("display", "block"); })
				.on("mouseout", function() { tooltip.css("display", "none"); })
				.on("mousemove", function() {

					// now tooltip for the date in the diagram
					var x0 = xScale.invert(d3.mouse(this)[0]),
						mouseX = xScale(x0),
						curdate, i;

					for(i=0; i<dateRange.length; i++) {
						curdate = dateRange[i].toISOString().substr(0, 10);

						if(i === dateRange.length-1 || x0 >= dateRange[i] && x0 < dateRange[i+1]) {
							break;
						}
					}

					var html = '<h1 class="head">' + curdate + '</h1><table class="values">';
					statuslist.forEach(function(status) {
						html += '<tr><th>' + translation[status] + "</th><td>" + (result[curdate] && result[curdate][status] ? result[curdate][status] : 0) + '</td></tr>';
					});
					html += '</table>';

					// avoid pointer being inside the tooltip to prevent flickering
					// dispay tooltip right or left of the pointer depending on the position in the diagram
					tooltip.html(html)
						.css("top", margin.top)
						.css("left", (mouseX - width / 2 > 0 ? mouseX - tooltip.outerWidth() - 20 : mouseX + 20) + margin.left);
				});

		}).always(function() {
			$(selector).removeClass("loading");
		});
	}
};



$(function() {
	Aimeos.Dashboard.Order.init();
});
