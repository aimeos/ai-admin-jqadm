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
        this.chartHour();
        this.chartPaymentType();
        this.chartDeliveryType();
    },


    chartPaymentStatus : function() {

        var dateParser = d3.time.format("%Y-%m-%d").parse;
        var i, date, entries = [];

        for( i = 0; i <= 7; i++ ) {
            date = new Date( new Date().getTime() - (7-i) * 86400 * 1000 ).toISOString().substr(0, 10);
            entries[i] = {'key': date, '-1': 0, '0': 0, '1': 0, '2': 0, '3': 0, '4': 0, '5': 0, '6': 0};
        }


        var margin = {top: 20, right: 30, bottom: 20, left: 40},
            width = $("#order-paymentstatus-data").width() - margin.left - margin.right,
            height = $("#order-paymentstatus-data").height() - margin.top - margin.bottom - 10;

        var xScale = d3.time.scale().range([0, width]).domain(d3.extent(entries, function(d) { return dateParser(d.key); }));
        var yScale = d3.scale.linear().range([height, 0]).domain([0, 10]);

        var xAxis = d3.svg.axis().scale(xScale).orient("bottom").ticks(3);
        var yAxis = d3.svg.axis().scale(yScale).orient("left").ticks(0);

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


        var addLine = function(data, status, color) {

            if( typeof data.data == 'undefined' ) {
                throw error;
            }

            data.data.forEach(function(item) {
                entries.forEach(function(entry, idx) {
                    if(item.id === entry.key) {
                        entries[idx][status] = +item.attributes;
                    }
                });
            });

            var line = d3.svg.line()
                .x(function(d) { return xScale(dateParser(d.key)); })
                .y(function(d) { return yScale(d[status]); });

            svg.append("path")
                .datum(entries)
                .attr("class", "line")
                .attr("stroke", color)
                .attr("d", line);

        };


        var start = new Date( new Date().getTime() - 7 * 86400 * 1000 ).toISOString().substr(0, 10) + ' 00:00:00';

        Aimeos.Dashboard.getData(function(data) {
            addLine(data, '-1', '#c7c7c7'); // unfinished: light grey
        }, 'order', 'order.cdate', {'&&': [{'>=': {'order.ctime': start}}, {'==': {'order.statuspayment': -1}}]}, '-order.ctime', 10000);

        Aimeos.Dashboard.getData(function(data) {
            addLine(data, '0', '#ff000'); // deleted: red
        }, 'order', 'order.cdate', {'&&': [{'>=': {'order.ctime': start}}, {'==': {'order.statuspayment': 0}}]}, '-order.ctime', 10000);

        Aimeos.Dashboard.getData(function(data) {
            addLine(data, '1', '#7f7f7f'); // canceled: dark grey
        }, 'order', 'order.cdate', {'&&': [{'>=': {'order.ctime': start}}, {'==': {'order.statuspayment': 1}}]}, '-order.ctime', 10000);

        Aimeos.Dashboard.getData(function(data) {
            addLine(data, '2', '#ff7f0e'); // refused: orange
        }, 'order', 'order.cdate', {'&&': [{'>=': {'order.ctime': start}}, {'==': {'order.statuspayment': 2}}]}, '-order.ctime', 10000);

        Aimeos.Dashboard.getData(function(data) {
            addLine(data, '3', '#ffbb78'); // refund: skin
        }, 'order', 'order.cdate', {'&&': [{'>=': {'order.ctime': start}}, {'==': {'order.statuspayment': 3}}]}, '-order.ctime', 10000);

        Aimeos.Dashboard.getData(function(data) {
            addLine(data, '4', '#1f77b4'); // pending: blue
        }, 'order', 'order.cdate', {'&&': [{'>=': {'order.ctime': start}}, {'==': {'order.statuspayment': 4}}]}, '-order.ctime', 10000);

        Aimeos.Dashboard.getData(function(data) {
            addLine(data, '5', '#bcbd22'); // authorized: olive
        }, 'order', 'order.cdate', {'&&': [{'>=': {'order.ctime': start}}, {'==': {'order.statuspayment': 5}}]}, '-order.ctime', 10000);

        Aimeos.Dashboard.getData(function(data) {
            addLine(data, '6', '#2ca02c'); // received: green
        }, 'order', 'order.cdate', {'&&': [{'>=': {'order.ctime': start}}, {'==': {'order.statuspayment': 6}}]}, '-order.ctime', 10000);
    },


    chartHour : function() {

        var margin = {top: 20, right: 30, bottom: 20, left: 40},
            width = $("#order-hour-data").width() - margin.left - margin.right,
            height = $("#order-hour-data").height() - margin.top - margin.bottom - 10;

        var svg = d3.select("#order-hour-data")
            .append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
            .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


        Aimeos.Dashboard.getData(function(data) {

            if( typeof data.data == 'undefined' ) {
                throw error;
            }

            var tzoffset = Math.floor((new Date()).getTimezoneOffset() / 60); // orders are stored with UTC timestamps

            var xScale = d3.scale.linear().range([0, width]).domain([0,23]);
            var yScale = d3.scale.linear().range([height, 0]).domain([0, d3.max(data.data, function(d) { return +d.attributes; })]);

            var xAxis = d3.svg.axis().scale(xScale).orient("bottom").ticks((width > 300 ? 12 : 6));
            var yAxis = d3.svg.axis().scale(yScale).orient("left").tickFormat(d3.format("d"));;

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

        }, 'order', 'order.chour', {}, '-order.ctime', 1000);
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
            width = $(panel).width(),
            height = $(panel).height() - 10,
            radius = (width - lgspace > height ? Math.min(width - lgspace, height) : Math.min(width, height) ) / 2;

        var arc = d3.svg.arc()
            .outerRadius(radius)
            .innerRadius(radius - 50);

        var pie = d3.layout.pie()
            .sort(null)
            .value(function(d) { return +d.attributes; });


        Aimeos.Dashboard.getData(function(data) {

            if( typeof data.data == 'undefined' ) {
                throw error;
            }

            var color = d3.scale.category20();
            var sum = d3.sum(data.data, function(d) { return d.attributes; });

            var svg = d3.select(panel)
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

        }, resource, key, criteria, sort, limit);
    }
};


$(function() {

	Aimeos.Dashboard.Order.init();
});
