/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */


Aimeos.Dashboard = {

    getData : function(resource, key, criteria, sort, limit) {

        return $.when( Aimeos.options ).then(function(data) {

			var params = {}, param = {};

			param['aggregate'] = key;
			param['filter'] = criteria;

            if(sort) {
			    param['sort'] = sort;
            }

            if(limit) {
                param['page'] = {'limit': limit};
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

        this.chartPaymentStatus();
        this.chartHour();
        this.chartPaymentType();
        this.chartDeliveryType();
    },


    chartPaymentStatus : function() {

        var dates = [];
        var numdays = 7;
        var dateParser = d3.time.format("%Y-%m-%d").parse;
        var translation = $('#order-paymentstatus-data').data('translation');

        for( var i = 0; i <= numdays; i++ ) {
            dates.push(new Date( new Date().getTime() - (numdays - i - 1) * 86400 * 1000 ).toISOString().substr(0, 10));
        }


        var criteria = {'&&': [{'>=': {'order.cdate': dates[0]}}, {'<': {'order.cdate': dates[dates.length-1]}}]};

        Aimeos.Dashboard.getData('order', 'order.cdate', criteria).then(function(data) {

            if( typeof data.data == 'undefined' ) {
                throw error;
            }

            var margin = {top: 20, right: 30, bottom: 30, left: 40},
                width = $("#order-paymentstatus-data").width() - margin.left - margin.right,
                height = $("#order-paymentstatus-data").height() - margin.top - margin.bottom - 10;

            var color = d3.scale.ordinal()
                .domain([-1, 0, 1, 2, 3, 4, 5, 6])
                .range(["pay-unfinished", "pay-deleted", "pay-canceled", "pay-refused", "pay-refund", "pay-pending", "pay-authorized", "pay-received"]);

            var xScale = d3.time.scale().range([0, width]).domain(d3.extent(dates, function(d) { return dateParser(d); }));
            var yScale = d3.scale.linear().range([height, 0]).domain([0, d3.max(data.data, function(d) { return +d.attributes; })]);

            var xAxis = d3.svg.axis().scale(xScale).orient("bottom").ticks(3);
            var yAxis = d3.svg.axis().scale(yScale).orient("left").tickFormat(d3.format("d"));

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


            dates.slice(0, numdays).reverse().forEach(function(date) {

                var criteria = {'==': {'order.cdate': new Date(new Date(date).getTime()).toISOString().substr(0, 10)}};

                Aimeos.Dashboard.getData('order', 'order.statuspayment', criteria, null, 10000).then(function(data) {

                    if( typeof data.data == 'undefined' ) {
                        throw error;
                    }

                    var start = 0;
                    var entries = [];
                    var dateobj = dateParser(date);

                    data.data.sort(function(a, b) { return a.id - b.id; });
                    data.data.forEach(function(d, idx) {
                        entries[idx] = {'key': d.id, 'value': d.attributes, y0: start, y1: start += +d.attributes};
                    });

                    var bar = svg.append("g")
                        .attr("class", "bar")
                        .attr("transform", "translate(" + xScale(dateobj) + ",0)");

                    bar.selectAll("rect")
                        .data(entries)
                        .enter()
                        .append("rect")
                            .attr("y", function(d) { return yScale(d.y1); })
                            .attr("class", function(d) { return color(d.key); })
                            .attr("height", function(d) { return yScale(d.y0) - yScale(d.y1); })
                            .attr("width", width / numdays - width / numdays / 100 * 10)
                            .append("title").text(function(d) { return translation[d.key] + ': ' + d.value; });
                });
            });
        });
    },


    chartHour : function() {

        var margin = {top: 20, right: 30, bottom: 30, left: 40},
            width = $("#order-hour-data").width() - margin.left - margin.right,
            height = $("#order-hour-data").height() - margin.top - margin.bottom - 10;

        var svg = d3.select("#order-hour-data")
            .append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
            .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


        Aimeos.Dashboard.getData('order', 'order.chour', {}, '-order.ctime', 1000).then(function(data) {

            if( typeof data.data == 'undefined' ) {
                throw error;
            }

            var tzoffset = Math.floor((new Date()).getTimezoneOffset() / 60); // orders are stored with UTC timestamps

            var xScale = d3.scale.linear().range([0, width]).domain([0,23]);
            var yScale = d3.scale.linear().range([height, 0]).domain([0, d3.max(data.data, function(d) { return +d.attributes; })]);

            var xAxis = d3.svg.axis().scale(xScale).orient("bottom").ticks((width > 300 ? 12 : 6));
            var yAxis = d3.svg.axis().scale(yScale).orient("left").tickFormat(d3.format("d"));

            var line = d3.svg.line()
                .x(function(d) { return xScale((+d.id - tzoffset) % 24); })
                .y(function(d) { return yScale(+d.attributes); });

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
        });
    },


    chartDeliveryType : function() {

        var criteria = {'==': {'order.base.service.type': 'delivery'}};
        this.drawDonut("#order-deliverytype-data", 'order', 'order.base.service.code', criteria, '-order.ctime', 1000);
    },


    chartPaymentType : function() {

        var criteria = {'==': {'order.base.service.type': 'payment'}};
        this.drawDonut("#order-paymenttype-data", 'order', 'order.base.service.code', criteria, '-order.ctime', 1000);
    },


    drawDonut : function(panel, resource, key, criteria, sort, limit) {

        var lgspace = 190, txheight = 20,
            margin = {top: 20, right: 20, bottom: 20, left: 20},
            width = $(panel).width() - margin.left - margin.right,
            height = $(panel).height() - margin.top - margin.bottom - 10,
            radius = (width - lgspace > height ? Math.min(width - lgspace, height) : Math.min(width, height) ) / 2;

        var arc = d3.svg.arc()
            .outerRadius(radius)
            .innerRadius(radius - 50);

        var pie = d3.layout.pie()
            .sort(null)
            .value(function(d) { return +d.attributes; });


        Aimeos.Dashboard.getData(resource, key, criteria, sort, limit).then(function(data) {

            if( typeof data.data == 'undefined' ) {
                throw error;
            }

            var color = d3.scale.category20();
            var sum = d3.sum(data.data, function(d) { return d.attributes; });

            var svg = d3.select(panel)
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
        });
    }
};


$(function() {

	Aimeos.Dashboard.Order.init();
});
