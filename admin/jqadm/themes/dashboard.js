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
            margin = {top: 20, bottom: 20, left: 15, right: 25},
            cellSize = 16, cellPad = 2, cellWidth = cellSize + cellPad,
            width = $(selector).width() - margin.left - margin.right,
            height = $(selector).height() - margin.top - margin.bottom - 10;

        var weeks = Math.ceil((width - cellWidth) / cellWidth),
            firstdate = new Date(new Date().getTime() - weeks * 7 * 86400 * 1000 + (6 - new Date().getDay()) * 86400 * 1000),
            dateRange = d3.time.day.utc.range(firstdate, new Date());


        var criteria = {">=": {"order.cdate": firstdate.toISOString().substr(0, 10)}};

        Aimeos.Dashboard.getData("order", "order.cdate", criteria, "-order.cdate", 10000).then(function(data) {

            if( typeof data.data == "undefined" ) {
                throw error;
            }

            var entries = {};
            data.data.forEach(function(d) { entries[d.id] = d.attributes; });

            var color = d3.scale.quantize()
                .range(d3.range(10).map(function(d) { return "q" + d; }))
                .domain([0, d3.max(data.data, function(d) { return +d.attributes; })]);

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
                        var first = d3.time.weekOfYear(new Date(d.getUTCFullYear(), 0, 1));
                        var weeks = d3.time.weekOfYear(new Date(firstdate.getUTCFullYear(), 11, 31));

                        if(weeks == 1) { weeks = d3.time.weekOfYear(new Date(d.getUTCFullYear(), 11, 24)); }
                        if(first == 0) { weeks += 1; }

                        var result = d3.time.weekOfYear(d) - d3.time.weekOfYear(firstdate) + (weeks * (d.getUTCFullYear() - firstdate.getUTCFullYear()));
                        return result * cellWidth;
                    })
                    .attr("y", function(d) { return d.getUTCDay() * cellWidth; })
                    .datum(d3.time.format("%Y-%m-%d"));

            cell.attr("class", function(d) { return "day " + color(entries[d] || 0); })
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
                .data(d3.time.month.utc.range(firstdate, new Date()))
                .enter().append("text")
                    .text(function (d) { var num = d.getMonth() + 1; return (num < 10 ? "0" + num : num); })
                    .attr("class", "x axis")
                    .attr("y", -10)
                    .attr("x", function (d) {
                        var idx = dateNumbers.indexOf(+d);
                        if(idx !== -1) { return Math.floor(idx / 7) * cellWidth + 20; }
                    });


            // outline of months in the heat map
            svg.selectAll(".path-month")
                .data(function() { return d3.time.months(firstdate, new Date()); })
                .enter().append("path")
                    .attr("class", "path-month")
                    .attr("d", function(t0) {

                        if(t0.getFullYear() === new Date().getFullYear() && t0.getMonth() === new Date().getMonth()) {
                            return;
                        }

                        var t1 = new Date(t0.getFullYear(), t0.getMonth() + 1, 0),
                            first = d3.time.weekOfYear(new Date(t0.getUTCFullYear(), 0, 1)),
                            weeks = d3.time.weekOfYear(new Date(firstdate.getUTCFullYear(), 11, 31)),
                            d0 = t0.getDay(), d1 = t1.getDay();

                        if(weeks == 1) { weeks = d3.time.weekOfYear(new Date(t0.getUTCFullYear(), 11, 24)); }
                        if(first == 0) { weeks += 1; }

                        var w0 = d3.time.weekOfYear(t0) - d3.time.weekOfYear(firstdate) + (weeks * (t1.getUTCFullYear() - firstdate.getUTCFullYear()));
                        var w1 = d3.time.weekOfYear(t1) - d3.time.weekOfYear(firstdate) + (weeks * (t1.getUTCFullYear() - firstdate.getUTCFullYear()));

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

            var xScale = d3.scale.linear().range([0, width]).domain([0,23]);
            var yScale = d3.scale.linear().range([height, 0]).domain([0, d3.max(data.data, function(d) { return +d.attributes; })]);

            var xAxis = d3.svg.axis().scale(xScale).orient("bottom").ticks((width > 300 ? 12 : 6));
            var yAxis = d3.svg.axis().scale(yScale).orient("left").tickFormat(d3.format("d"));

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

        var dates = [], numdays = 7,
            selector = "#order-paymentstatus-data",
            translation = $(selector).data("translation"),
            margin = {top: 20, right: 30, bottom: 30, left: 40},
            width = $(selector).width() - margin.left - margin.right,
            height = $(selector).height() - margin.top - margin.bottom - 10,
            statusrange = ["pay-unfinished", "pay-deleted", "pay-canceled", "pay-refused", "pay-refund", "pay-pending", "pay-authorized", "pay-received"],
            statuslist = [-1, 0, 1, 2, 3, 4, 5, 6];

        for( var i = 0; i < numdays; i++ ) {
            dates.push(new Date(new Date().getTime() - (numdays - i - 1) * 86400 * 1000 ).toISOString().substr(0, 10));
        }


        var criteria = {"&&": [{">=": {"order.cdate": dates[0]}}, {"<=": {"order.cdate": dates[dates.length-1]}}]};

        Aimeos.Dashboard.getData("order", "order.cdate", criteria, '-order.cdate', 100000).then(function(data) {

            if( typeof data.data == "undefined" ) {
                throw error;
            }

            var dateParser = d3.time.format("%Y-%m-%d").parse;

            var color = d3.scale.ordinal().domain(statuslist).range(statusrange);
            var xScale = d3.time.scale().range([0, width]).domain([dateParser(dates[0]), dateParser(dates[dates.length-1])]);
            var yScale = d3.scale.linear().range([height, 0]).domain([0, d3.max(data.data, function(d) { return +d.attributes; })]);

            var xAxis = d3.svg.axis().scale(xScale).orient("bottom").ticks(Math.round(numdays / 2));
            var yAxis = d3.svg.axis().scale(yScale).orient("left").tickFormat(d3.format("d"));

            var area = d3.svg.area()
                .interpolate("monotone")
                .x(function(d) { return xScale(d.key); })
                .y0(function(d) { return yScale(d.y0); })
                .y1(function(d) { d.y0 = d.y1; return yScale(d.y1); }); // y0 = y1: set new base line for next layer

            var line = d3.svg.line()
                .interpolate("monotone")
                .x(function(d) { return xScale(d.key); })
                .y(function(d) { return yScale(d.y1); });

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


            var responses = [], entries = {};

            dates.forEach(function(date) {
                entries[date] = {'key': dateParser(date), 'status': {}, 'y0': 0, 'y1': 0};
            });

            // create a JSON request for every status value (-1 till 6)
            statuslist.forEach(function(status) {
                var criteria = {"&&": [
                    {">=": {"order.cdate": dates[0]}},
                    {"<=": {"order.cdate": dates[dates.length-1]}},
                    {"==": {"order.statuspayment": status}}
                ]};

                responses.push({'promise': Aimeos.Dashboard.getData("order", "order.cdate", criteria, '-order.cdate', 10000), 'status': status});
            });

            // draw a new layer for each status value, order is maintained by waiting for the promise of the status value
            responses.forEach(function(entry) {
                entry.promise.then(function(data) {

                    if( typeof data.data == "undefined" ) {
                        throw error;
                    }

                    data.data.forEach(function(d) {
                        entries[d.id]['status'][entry.status] = d.attributes;
                        entries[d.id]['y1'] += +d.attributes;
                    });

                    var list = [];
                    dates.forEach(function(d) {
                        list.push(entries[d]);
                    });

                    svg.append("path")
                        .datum(list)
                        .attr("class", function(d) { return "layer " + color(entry.status); })
                        .attr("d", area);

                    svg.append("path")
                        .datum(list)
                        .attr("class", function(d) { return "line " + color(entry.status); })
                        .attr("d", line);

                });
            });


            // interactive chart details
            responses[responses.length-1].promise.then(function() {

                statuslist.reverse(); // print counts per status in descending order

                var focus = svg.append("line")
                    .attr("x1", 0).attr("x2", 0)
                    .attr("y1", 0).attr("y2", height);

                var tooltip = $('<div class="tooltip" />').appendTo($(selector));

                svg.append("rect")
                    .attr("class", "overlay")
                    .attr("width", width)
                    .attr("height", height)
                    .on("mouseover", function() { focus.classed("focus", true); tooltip.css("display", "block"); })
                    .on("mouseout", function() { focus.classed("focus", false); tooltip.css("display", "none"); })
                    .on("mousemove", function() {

                        // move the focus line to the nearest date in the diagram
                        var x0 = xScale.invert(d3.mouse(this)[0]),
                            i = d3.bisector(function(d) { return dateParser(d); }).left(dates, x0),
                            mouseX = xScale(x0);

                        i = (i === 0 ? 1 : (i === dates.length ? dates.length - 1 : i));

                        var date,
                            d0 = dateParser(dates[i]),
                            d1 = dateParser(dates[i-1]);

                        if(Math.abs(x0 - d0) > Math.abs(d1 - x0) ) {
                            xval = xScale(d1); date = dates[i-1];
                        } else {
                            xval = xScale(d0); date = dates[i];
                        }

                        focus.attr("transform", "translate(" + xval + ",0)");

                        var html = '<h1 class="head">' + dates[i] + '</h1><table class="values">';
                        statuslist.forEach(function(status) {
                            html += '<tr><th>' + translation[status] + "</th><td>" + (entries[date]['status'][status] || 0) + '</td></tr>';
                        });
                        html += '</table>';

                        // avoid pointer being inside the tooltip to prevent flickering
                        // dispay tooltip right or left of the pointer depending on the position in the diagram
                        tooltip.html(html)
                            .css("top", margin.top)
                            .css("left", (mouseX - width / 2 > 0 ? mouseX - tooltip.outerWidth() : mouseX + 20) + margin.left);
                    });

            }).done(function() {
                $(selector).removeClass("loading");
            });
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

            var color = d3.scale.category20();
            var sum = d3.sum(data.data, function(d) { return d.attributes; });

            var arc = d3.svg.arc()
                .outerRadius(radius)
                .innerRadius(radius - 50);

            var pie = d3.layout.pie()
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
