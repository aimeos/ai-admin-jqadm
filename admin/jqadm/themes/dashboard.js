/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */


Aimeos.Dashboard = {

	/**
	 * Adds a HTML legend for the given data to the selector
	 *
	 * @param string selector CSS selector of the container the legend should be added to
	 * @param array data List of labels for the legend
	 * @param function colorScale D3js scale for the color of the legend squares
	 */
	addLegend : function(selector, data, colorScale) {

		var legendItem = d3.select(selector)
			.append("div")
				.attr("class", "legend")
			.selectAll("div")
			.data(data)
			.enter().append("div")
				.attr("class", "legend-item");

		legendItem.append("div")
			.attr("class", "legend-square")
			.attr("style", function(d) { return "background-color: " + colorScale(d); });

		legendItem.append("span")
			.attr("class", "legend-text")
			.text(function(d) { return d; });
	},


	/**
	 * Returns a jQuery promise for the constructed request
	 *
	 * @param string resource Resource name like "product", "order" or "order/base/address"
	 * @param string key Aggregation key to group results by, e.g. "order.cdate", "order.base.address.countryid"
	 * @param object criteria Polish notation object with conditions for limiting the results, e.g. {">": {"order.cdate": "2000-01-01"}}
	 * @param string|null sort Optional sorting criteria like "order.cdate" (ascending) or "-order.cdate" (descending), also more then one separated by comma
	 * @param integer|null limit Optional limit for the number of records that are selected before aggregation (default: 25)
	 * @param string|null value Aggregate values from that column, e.g "order.base.price" (in combination with type)
	 * @param string|null type Type of aggregation like "sum" or "avg" (default: null for count)
	 * @return jQuery promise object
	 */
	getData : function(resource, key, criteria, sort, limit, value, type) {

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

			if(value) {
				param["value"] = value;
			}

			if(type) {
				param["type"] = type;
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
	},



	drawDonut : function(selector, resource, key, criteria, sort, limit) {

		var lgspace = 190, txheight = 20,
			margin = {top: 20, right: 20, bottom: 20, left: 20},
			width = $(selector).width() - margin.left - margin.right,
			height = $(selector).height() - margin.top - margin.bottom - 10,
			radius = (width - lgspace > height ? Math.min(width - lgspace, height) : Math.min(width, height) ) / 2;

		if(width > height) {
			width = height;
		}


		Aimeos.Dashboard.getData(resource, key, criteria, sort, limit).then(function(data) {

			if( typeof data.data == "undefined" ) {
				throw 'No data in response';
			}

			var colorScale = d3.scaleOrdinal(d3.schemeCategory20);
			var domain = data.data.map(function(d) { return d.id; })
			var sum = d3.sum(data.data, function(d) { return d.attributes; });

			var arc = d3.arc().outerRadius(radius).innerRadius(radius - 50);
			var pie = d3.pie().padAngle(.02).sort(null).value(function(d) { return +d.attributes; });

			var svg = d3.select(selector)
				.append("svg")
				.attr("width", width + margin.left + margin.right)
				.attr("height", height + margin.top + margin.bottom);

			var donut = svg.append("g")
				.attr("transform", "translate(" + (radius + margin.left) + "," + (radius + margin.top) + ")")
				.selectAll(".arc")
				.data(pie(data.data))
				.enter()
				.append("g")
				.attr("class", "arc");

			donut.append("path")
				.attr("d", arc)
				.style("fill", function(d, i) { return colorScale(d.data.id); });

			donut.append("text")
				.attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
				.attr("dx", "-1.25em")
				.attr("dy", ".35em")
				.text(function(d) {
					var perc = Math.round(d.data.attributes / sum * 1000) / 10;
					return ( perc > 5.0 ? perc + "%" : "" );
				});

			Aimeos.Dashboard.addLegend(selector, domain, colorScale);

		}).always(function() {
			$(selector).removeClass("loading");
		});
	}
};
