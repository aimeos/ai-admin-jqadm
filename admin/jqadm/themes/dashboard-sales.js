/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2017
 */


Aimeos.Dashboard.Sales = {

	currencyPromise: null,


	init: function() {

		this.currencyPromise = $.when(Aimeos.options).pipe(function(data) {

			if( typeof data.meta == "undefined" ) {
				throw 'No meta data in response';
			}

			var params = {}, param = {"filter": {">": {"locale.currency.status": 0}}};

			if( data.meta.prefix ) {
				params[data.meta.prefix] = param;
			} else {
				params = param;
			}

			return $.ajax({
				method: "GET",
				dataType: "json",
				url: data.meta.resources['locale/currency'],
				data: params
			});
		});


		if( $(".order-saleslastmonth").length ) {
			this.chartDay();
		}

		if( $(".order-saleslastyear").length ) {
			this.chartMonth();
		}

		if( $(".order-weekday").length ) {
			this.chartWeekday();
		}
	},



	chartDay : function() {

		var selector = "#order-saleslastmonth-data",
			margins = {top: 10, bottom: 20, legend: 30, left: 50, right: 50},
			width = $(selector).width() - margins.left - margins.right,
			height = $(selector).height() - margins.top - margins.bottom - margins.legend;

		var days = 30,
			firstdate = new Date(new Date().getTime() - days * 86400 * 1000),
			dateRange = d3.utcDay.range(firstdate, new Date());

		var xScaleDays = d3.scaleTime().rangeRound([0, width]).domain([firstdate, new Date()]).nice(days);
		var xAxis = d3.axisBottom().scale(xScaleDays);

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

		$.when(Aimeos.Dashboard.Sales.currencyPromise).pipe(function(data) {

			if( typeof data.data == "undefined" ) {
				throw 'No data in response';
			}

			var criteria, currencyid, promises ={};

			for(var i=0; i<data.data.length; i++) {

				currencyid = data.data[i].id;
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

			return $.when.apply($, Object.values(promises));

		}).done(function() {

			var dateParser = d3.timeParse("%Y-%m-%d");
			var lang = $(".dashboard-order").data("language") || "en";

			var xScaleCurrencies = d3.scaleBand().domain(currencies).rangeRound([0, width/days]).padding(0.05);
			var colorScale = d3.scaleOrdinal(d3.schemeCategory10);

			var yScale = d3.scaleLinear().range([height, 0]).domain([0, max]);
			var yAxis = d3.axisLeft().scale(yScale).ticks(7);


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
					.attr("x", function(d) { return xScaleCurrencies(d[0]); })
					.attr("y", function(d) { return yScale(d[1]); })
					.attr("width", xScaleCurrencies.bandwidth())
					.attr("height", function(d) { return height - yScale(d[1]); })
					.attr("fill", function(d) { return colorScale(d[0]); })
					.append("title").text(function(d) {
						return new Intl.NumberFormat(lang, {style: 'currency', currency: d[0]}).format(d[1]);
					});

			$(selector).removeClass("loading");
		});
	},



	chartMonth : function() {

		var selector = "#order-saleslastyear-data",
			margins = {top: 10, bottom: 20, legend: 30, left: 50, right: 50},
			width = $(selector).width() - margins.left - margins.right,
			height = $(selector).height() - margins.top - margins.bottom - margins.legend;

		var date = new Date(new Date().getTime() - 365 * 86400 * 1000),
			firstdate = new Date(date.getUTCFullYear(), date.getMonth()+1, 1);
			months = d3.utcMonth.range(firstdate, new Date()).map(function(d) { return d.toISOString().substr(0, 7); });

		var xScaleMonths = d3.scaleBand().range([0, width]).domain(months).paddingInner(0.15);
		var xAxis = d3.axisBottom().scale(xScaleMonths);

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

		$.when(Aimeos.Dashboard.Sales.currencyPromise).pipe(function(data) {

			if( typeof data.data == "undefined" ) {
				throw 'No data in response';
			}

			var criteria, currencyid, promises ={};

			for(var i=0; i<data.data.length; i++) {

				currencyid = data.data[i].id;
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

			return $.when.apply($, Object.values(promises));

		}).done(function() {

			var lang = $(".dashboard-order").data("language") || "en";

			var xScaleCurrencies = d3.scaleBand().domain(currencies).rangeRound([0, xScaleMonths.bandwidth()]).padding(0.05);
			var colorScale = d3.scaleOrdinal(d3.schemeCategory10);

			var yScale = d3.scaleLinear().range([height, 0]).domain([0, max]);
			var yAxis = d3.axisLeft().scale(yScale).ticks(7);


			Aimeos.Dashboard.addLegend(selector, currencies, colorScale);

			svg.append("g")
				.attr("class", "y axis left")
				.call(yAxis);

			svg.append("g")
				.selectAll("g")
				.data(Object.entries(result))
				.enter().append("g")
					.attr("transform", function(d) { console.log(d); return "translate(" + xScaleMonths(d[0]) + ",0)"; })
				.selectAll("rect")
				.data(function(d) { return Object.entries(d[1]); })
				.enter().append("rect")
					.attr("x", function(d) { return xScaleCurrencies(d[0]); })
					.attr("y", function(d) { return yScale(d[1]); })
					.attr("width", xScaleCurrencies.bandwidth())
					.attr("height", function(d) { return height - yScale(d[1]); })
					.attr("fill", function(d) { return colorScale(d[0]); })
					.append("title").text(function(d) {
						return new Intl.NumberFormat(lang, {style: 'currency', currency: d[0]}).format(d[1]);
					});

			$(selector).removeClass("loading");
		});
	},



	chartWeekday : function() {

		var selector = "#order-weekday-data",
			margins = {top: 10, bottom: 20, legend: 30, left: 50, right: 50},
			width = $(selector).width() - margins.left - margins.right,
			height = $(selector).height() - margins.top - margins.bottom - margins.legend;

		var firstdate = new Date(new Date().getTime() - 365 * 86400 * 1000);

		var xScaleWdays = d3.scaleBand().range([0, width]).domain([0, 1, 2, 3, 4, 5, 6]).paddingInner(0.15);
		var xAxis = d3.axisBottom().scale(xScaleWdays).tickFormat(function(d) { return ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"][d]; });

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

		$.when(Aimeos.Dashboard.Sales.currencyPromise).pipe(function(data) {

			if( typeof data.data == "undefined" ) {
				throw 'No data in response';
			}

			var criteria, currencyid, promises ={};

			for(var i=0; i<data.data.length; i++) {

				currencyid = data.data[i].id;
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

			return $.when.apply($, Object.values(promises));

		}).done(function() {

			var lang = $(".dashboard-order").data("language") || "en";

			var xScaleCurrencies = d3.scaleBand().domain(currencies).rangeRound([0, xScaleWdays.bandwidth()]).padding(0.05);
			var colorScale = d3.scaleOrdinal(d3.schemeCategory10);

			var yScale = d3.scaleLinear().range([height, 0]).domain([0, max]);
			var yAxis = d3.axisLeft().scale(yScale).ticks(7);


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
					.attr("x", function(d) { return xScaleCurrencies(d[0]); })
					.attr("y", function(d) { return yScale(d[1]); })
					.attr("width", xScaleCurrencies.bandwidth())
					.attr("height", function(d) { return height - yScale(d[1]); })
					.attr("fill", function(d) { return colorScale(d[0]); })
					.append("title").text(function(d) {
						return new Intl.NumberFormat(lang, {style: 'currency', currency: d[0]}).format(d[1]);
					});

			$(selector).removeClass("loading");
		});
	}
};



$(function() {

	Aimeos.Dashboard.Sales.init();
});
