/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */


Aimeos.Dashboard = {

    getData : function(fcn, resource, key, criteria, sort, limit) {

        Aimeos.options.done(function(data) {

			var params = {}, param = {};

			param['aggregate'] = key;
			param['filter'] = criteria;
			param['sort'] = sort;
            param['page'] = {'limit': limit};

			if( data.meta && data.meta.prefix ) {
				params[data.meta.prefix] = param;
			} else {
				params = param;
			}

			$.ajax({
				dataType: "json",
				url: data.meta.resources[resource] || null,
				data: params,
				success: fcn
            });
        });
    }
};


Aimeos.Dashboard.Order = {

    init : function() {

        this.chartPaymentStatus();
        this.chartPaymentType();
    },


    chartPaymentStatus : function() {

        var start = new Date( new Date().getTime() - 7 * 86400 * 1000 ).toISOString().substr(0, 10) + ' 00:00:00';
        var criteria = {'&&': [{'>=': {'order.ctime': start}}, {'>=': {'order.statuspayment': 5}}]};

        Aimeos.Dashboard.getData(function(data) {

            if( typeof data.data == 'undefined' ) {
                throw error;
            }

            var i, date, entries = [];
            var dateParser = d3.time.format("%Y-%m-%d").parse;

            for( i = 0; i <= 7; i++ ) {

                date = new Date( new Date().getTime() - (7-i) * 86400 * 1000 ).toISOString().substr(0, 10);
                entries[i] = {'id': dateParser(date), 'attributes': 0};

                data.data.forEach(function(entry) {
                    if(entry.id === date) {
                        entries[i].attributes = +entry.attributes;
                    }
                });
            }


            var margin = {top: 20, right: 30, bottom: 20, left: 40},
                width = $("#order-paymentstatus-data").width() - margin.left - margin.right,
                height = $("#order-paymentstatus-data").height() - margin.top - margin.bottom;

            var xScale = d3.time.scale().range([0, width])
                .domain(d3.extent(entries, function(d) { return d.id; }));
            var yScale = d3.scale.linear().range([height, 0])
                .domain(d3.extent(entries, function(d) { return d.attributes; }));

            var xAxis = d3.svg.axis().scale(xScale).orient("bottom").ticks(3);
            var yAxis = d3.svg.axis().scale(yScale).orient("left").tickFormat(d3.format("d"));

            var line = d3.svg.line()
                .x(function(d) { return xScale(d.id); })
                .y(function(d) { return yScale(d.attributes); });

            var svg = d3.select("#order-paymentstatus-data")
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

            svg.append("path")
                .datum(entries)
                .attr("class", "line")
                .attr("d", line);

        }, 'order', 'order.cdate', criteria, '-order.ctime', 10000);
    },


    chartPaymentType : function() {

        Aimeos.Dashboard.getData(function(data) {

            if( typeof data.data == 'undefined' ) {
                throw error;
            }

            var lgspace = 190, txheight = 20,
                width = $("#order-paymentstatus-data").width(),
                height = $("#order-paymentstatus-data").height(),
                radius = (width - lgspace > height ? Math.min(width - lgspace, height) : Math.min(width, height) ) / 2;

            var color = d3.scale.category20();
            var sum = d3.sum(data.data, function(d) { return d.attributes; });

            var arc = d3.svg.arc()
                .outerRadius(radius)
                .innerRadius(radius - 50);

            var pie = d3.layout.pie()
                .sort(null)
                .value(function(d) { return +d.attributes; });

            var svg = d3.select("#order-paymenttype-data")
                .append("svg")
                    .attr("width", width)
                    .attr("height", (width - lgspace > height ? height : radius * 2 + data.data.length * txheight + 25 ));

            var donut = svg.append("g")
                .attr("transform", "translate(" + radius + "," + radius + ")")
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
                    return ( perc > 5.0 ? perc + '%' : '' );
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
                        return "translate(" + (radius * 2 + 25) + "," + (i * txheight + 10) + ")";
                    } else {
                        return "translate(0," + (radius * 2 + 25 + i * txheight) + ")";
                    }
                });

            legend.append("text")
                .text(function(d, i) { return d.id; })
                .attr("transform", function(d, i) {
                    if(width - lgspace > height) {
                        return "translate(" + (radius * 2 + 50) + "," + (i * txheight + 20) + ")";
                    } else {
                        return "translate(25," + (radius * 2 + 25 + i * txheight + 10) + ")";
                    }
                });

        }, 'order', 'order.base.service.code', {'==': {'order.base.service.type': 'payment'}}, '-order.ctime', 1000);
    }
};


$(function() {

	Aimeos.Dashboard.Order.init();
});
