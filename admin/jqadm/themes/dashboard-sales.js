/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */


Aimeos.Dashboard.Sales = {

	colors: ["#31A354", "#FF8E00", "#68D6AD", "#DD3737", "#E3E700"],
	currencies: $(".aimeos .dashboard-order").data("currencies"),


	init: function() {

		if( $(".order-salesday").length ) {
			this.chartDay();
		}

		if( $(".order-salesmonth").length ) {
			this.chartMonth();
		}

		if( $(".order-salesweekday").length ) {
			this.chartWeekday();
		}
	},



	chartDay : function() {

		var selector = "#order-salesday-data",
			margins = {top: 10, bottom: 20, legend: 30, left: 50, right: 20},
			width = $(selector).width() - margins.left - margins.right,
			height = $(selector).height() - margins.top - margins.bottom - margins.legend;

		var days = 30,
			startdate = new Date(new Date().getTime() - (days+1) * 86400 * 1000),
			enddate = new Date(new Date().getTime() + 86400 * 1000);

		var lang = $(".aimeos").attr("lang") || "en",
			dateFmt = new Intl.DateTimeFormat(lang, {month: "numeric", day: "numeric"});

		var xScaleDays = d3.scaleTime().rangeRound([0, width]).domain([startdate, enddate]);
		var xAxis = d3.axisBottom().scale(xScaleDays).ticks(width/days/2).tickFormat(function(d) { return dateFmt.format(d); });

		var svg = d3.select(selector)
			.append("svg")
				.attr("width", width + margins.left + margins.right)
				.attr("height", height + margins.top + margins.bottom)
			.append("g")
				.attr("transform", "translate(" + margins.left + "," + margins.top + ")");

		svg.append("g")
			.attr("class", "x axis")
			.attr("transform", "translate(0," + height + ")")
			.call(xAxis);


		var currencies = [], result = {}, max = 0;
		var criteria, currencyid, promises = {},
			firstdate = new Date(new Date().getTime() - days * 86400 * 1000);

		for(var i=0; i<this.currencies.length; i++) {

			currencyid = this.currencies[i];
			criteria = {"&&": [
				{">=": {"order.statuspayment": 5}},
				{">": {"order.cdate": firstdate.toISOString().substr(0, 10)}},
				{"==": {"order.base.currencyid": currencyid}}
			]};

			promises[currencyid] = Aimeos.Dashboard.getData("order", "order.cdate", criteria, "-order.cdate", 10000, "order.base.price", "sum");
			currencies.push(currencyid);
		}

		jQuery.each(promises, function(currency, promise) {
			promise.done(function(data) {

				if(typeof data.data == "undefined") {
					throw 'No data in response';
				}

				for(var i=0; i<data.data.length; i++) {
					max = Math.max(max, +data.data[i].attributes);

					result[data.data[i].id] = result[data.data[i].id] || {};
					result[data.data[i].id][currency] = +data.data[i].attributes;
				}
			});
		});


		$.when.apply($, Object.values(promises)).done(function() {

			var dateParser = d3.timeParse("%Y-%m-%d");
			var numFmt = Intl.NumberFormat(lang);

			var xScaleCurrencies = d3.scaleBand().domain(currencies).rangeRound([0, width/days]).padding(0.15).paddingInner(0.05);
			var colorScale = d3.scaleOrdinal(Aimeos.Dashboard.Sales.colors);

			var yScale = d3.scaleLinear().range([height, 0]).domain([0, max]);
			var yAxis = d3.axisLeft().scale(yScale).ticks(7).tickFormat(function(d) { return numFmt.format(d); });


			Aimeos.Dashboard.addLegend(selector, currencies, colorScale);

			svg.append("g")
				.attr("class", "y axis left")
				.call(yAxis);

			svg.append("g")
				.selectAll("g")
				.data(Object.entries(result))
				.enter().append("g")
					.attr("transform", function(d) { return "translate(" + (xScaleDays(dateParser(d[0])) - width/days/2) + ",0)"; })
				.selectAll("rect")
				.data(function(d) { return Object.entries(d[1]); })
				.enter().append("rect")
					.attr("class", "barsum")
					.attr("x", function(d) { return xScaleCurrencies(d[0]); })
					.attr("y", function(d) { return yScale(d[1]); })
					.attr("width", xScaleCurrencies.bandwidth())
					.attr("height", function(d) { return height - yScale(d[1]); })
					.attr("fill", function(d) { return colorScale(d[0]); })
					.append("title").text(function(d) {
						return new Intl.NumberFormat(lang, {style: 'currency', currency: d[0]}).format(d[1]);
					});

		}).always(function() {
			$(selector).removeClass("loading");
		});
	},



	chartMonth : function() {

		var selector = "#order-salesmonth-data",
			margins = {top: 10, bottom: 20, legend: 30, left: 50, right: 20},
			width = $(selector).width() - margins.left - margins.right,
			height = $(selector).height() - margins.top - margins.bottom - margins.legend;

		var date = new Date(new Date().getTime() - 365 * 86400 * 1000),
			firstdate = new Date(date.getUTCFullYear(), date.getMonth()+1, 1);
			months = d3.utcMonth.range(firstdate, new Date()).map(function(d) { return d.toISOString().substr(0, 7); });

		var xScaleMonths = d3.scaleBand().range([0, width]).domain(months).paddingInner(0.15);
		var xAxis = d3.axisBottom().scale(xScaleMonths).tickFormat(function(d) { return d.substr(5, 2); });

		var svg = d3.select(selector)
			.append("svg")
				.attr("width", width + margins.left + margins.right)
				.attr("height", height + margins.top + margins.bottom)
			.append("g")
				.attr("transform", "translate(" + margins.left + "," + margins.top + ")");

		svg.append("g")
			.attr("class", "x axis")
			.attr("transform", "translate(0," + height + ")")
			.call(xAxis);


		var currencies = [], result = {}, max = 0;
		var criteria, currencyid, promises ={};

		for(var i=0; i<this.currencies.length; i++) {

			currencyid = this.currencies[i];
			criteria = {"&&": [
				{">=": {"order.statuspayment": 5}},
				{">": {"order.cdate": firstdate.toISOString().substr(0, 10)}},
				{"==": {"order.base.currencyid": currencyid}}
			]};

			promises[currencyid] = Aimeos.Dashboard.getData("order", "order.cmonth", criteria, "-order.cdate", 10000, "order.base.price", "sum");
			currencies.push(currencyid);
		}

		jQuery.each(promises, function(currency, promise) {
			promise.done(function(data) {

				if(typeof data.data == "undefined") {
					throw 'No data in response';
				}

				for(var i=0; i<data.data.length; i++) {
					max = Math.max(max, +data.data[i].attributes);

					result[data.data[i].id] = result[data.data[i].id] || {};
					result[data.data[i].id][currency] = +data.data[i].attributes;
				}
			});
		});


		$.when.apply($, Object.values(promises)).done(function() {

			var lang = $(".aimeos").attr("lang") || "en";
			var numFmt = Intl.NumberFormat(lang);

			var xScaleCurrencies = d3.scaleBand().domain(currencies).rangeRound([0, xScaleMonths.bandwidth()]).padding(0.05);
			var colorScale = d3.scaleOrdinal(Aimeos.Dashboard.Sales.colors);

			var yScale = d3.scaleLinear().range([height, 0]).domain([0, max]);
			var yAxis = d3.axisLeft().scale(yScale).ticks(7).tickFormat(function(d) { return numFmt.format(d); });


			Aimeos.Dashboard.addLegend(selector, currencies, colorScale);

			svg.append("g")
				.attr("class", "y axis left")
				.call(yAxis);

			svg.append("g")
				.selectAll("g")
				.data(Object.entries(result))
				.enter().append("g")
					.attr("transform", function(d) { return "translate(" + xScaleMonths(d[0]) + ",0)"; })
				.selectAll("rect")
				.data(function(d) { return Object.entries(d[1]); })
				.enter().append("rect")
					.attr("class", "barsum")
					.attr("x", function(d) { return xScaleCurrencies(d[0]); })
					.attr("y", function(d) { return yScale(d[1]); })
					.attr("width", xScaleCurrencies.bandwidth())
					.attr("height", function(d) { return height - yScale(d[1]); })
					.attr("fill", function(d) { return colorScale(d[0]); })
					.append("title").text(function(d) {
						return new Intl.NumberFormat(lang, {style: 'currency', currency: d[0]}).format(d[1]);
					});

		}).always(function() {
			$(selector).removeClass("loading");
		});
	},



	chartWeekday : function() {

		var selector = "#order-salesweekday-data",
			margins = {top: 10, bottom: 20, legend: 30, left: 50, right: 20},
			width = $(selector).width() - margins.left - margins.right,
			height = $(selector).height() - margins.top - margins.bottom - margins.legend;

		var lang = $(".aimeos").attr("lang") || "en",
			dateFmt = new Intl.DateTimeFormat(lang, {weekday: "short"});

		var weekdays = [],
			firstdate = new Date(new Date().getTime() - 365 * 86400 * 1000);

		for(var i=1; i<=7; i++) {
			weekdays.push(dateFmt.format(new Date('1970-02-0' + i))); // Sunday to Saturday
		}

		var xScaleWdays = d3.scaleBand().range([0, width]).domain([0, 1, 2, 3, 4, 5, 6]).paddingInner(0.15);
		var xAxis = d3.axisBottom().scale(xScaleWdays).tickFormat(function(d) { return weekdays[d]; });

		var svg = d3.select(selector)
			.append("svg")
				.attr("width", width + margins.left + margins.right)
				.attr("height", height + margins.top + margins.bottom)
			.append("g")
				.attr("transform", "translate(" + margins.left + "," + margins.top + ")");

		svg.append("g")
			.attr("class", "x axis")
			.attr("transform", "translate(0," + height + ")")
			.call(xAxis);


		var currencies = [], result = {}, max = 0;
		var criteria, currencyid, promises ={};

		for(var i=0; i<this.currencies.length; i++) {

			currencyid = this.currencies[i];
			criteria = {"&&": [
				{">=": {"order.statuspayment": 5}},
				{">": {"order.cdate": firstdate.toISOString().substr(0, 10)}},
				{"==": {"order.base.currencyid": currencyid}}
			]};

			promises[currencyid] = Aimeos.Dashboard.getData("order", "order.cwday", criteria, "-order.cdate", 10000, "order.base.price", "sum");
			currencies.push(currencyid);
		}

		jQuery.each(promises, function(currency, promise) {
			promise.done(function(data) {

				if(typeof data.data == "undefined") {
					throw 'No data in response';
				}

				for(var i=0; i<data.data.length; i++) {
					max = Math.max(max, +data.data[i].attributes);

					result[data.data[i].id] = result[data.data[i].id] || {};
					result[data.data[i].id][currency] = +data.data[i].attributes;
				}
			});
		});


		$.when.apply($, Object.values(promises)).done(function() {

			var numFmt = Intl.NumberFormat(lang);

			var xScaleCurrencies = d3.scaleBand().domain(currencies).rangeRound([0, xScaleWdays.bandwidth()]).padding(0.05);
			var colorScale = d3.scaleOrdinal(Aimeos.Dashboard.Sales.colors);

			var yScale = d3.scaleLinear().range([height, 0]).domain([0, max]);
			var yAxis = d3.axisLeft().scale(yScale).ticks(7).tickFormat(function(d) { return numFmt.format(d); });


			Aimeos.Dashboard.addLegend(selector, currencies, colorScale);

			svg.append("g")
				.attr("class", "y axis left")
				.call(yAxis);

			svg.append("g")
				.selectAll("g")
				.data(Object.entries(result))
				.enter().append("g")
					.attr("transform", function(d) { return "translate(" + xScaleWdays(d[0]) + ",0)"; })
				.selectAll("rect")
				.data(function(d) { return Object.entries(d[1]); })
				.enter().append("rect")
					.attr("class", "barsum")
					.attr("x", function(d) { return xScaleCurrencies(d[0]); })
					.attr("y", function(d) { return yScale(d[1]); })
					.attr("width", xScaleCurrencies.bandwidth())
					.attr("height", function(d) { return height - yScale(d[1]); })
					.attr("fill", function(d) { return colorScale(d[0]); })
					.append("title").text(function(d) {
						return new Intl.NumberFormat(lang, {style: 'currency', currency: d[0]}).format(d[1]);
					});

		}).always(function() {
			$(selector).removeClass("loading");
		});
	}
};



$(function() {

	Aimeos.Dashboard.Sales.init();
});
