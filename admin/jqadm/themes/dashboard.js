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


            var margin = {top: 20, right: 30, bottom: 20, left: 30},
                width = $("#order-paymentstatus-data").width() - margin.left - margin.right,
                height = $("#order-paymentstatus-data").height() - margin.top - margin.bottom;

            var xScale = d3.time.scale().range([0, width])
                .domain(d3.extent(entries, function(d) { return d.id; }));
            var yScale = d3.scale.linear().range([height, 0])
                .domain(d3.extent(entries, function(d) { return d.attributes; }));

            var xAxis = d3.svg.axis().scale(xScale).orient("bottom").ticks(3);
            var yAxis = d3.svg.axis().scale(yScale).orient("left").ticks(1);

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
    }
};


$(function() {

	Aimeos.Dashboard.Order.init();
});
