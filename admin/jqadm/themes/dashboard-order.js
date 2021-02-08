/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2021
 */


Aimeos.Dashboard.Order = {

	color: ['#f2f2f2', '#e6eff7', '#d4e1ed', '#c9def2', '#a5d2e8', '#7bbee6', '#54a4d7', '#3586ca', '#2069b4', '#2c5490'],
	paystatusColor: ['#d3d3d3', '#e15759', '#f28e2b', '#edc948', '#5bb3e6', '#30a0e0', '#00ccbb', '#00b0a0'],
	hourColor: ['#ffffff', '#30a0e0'],
	dayColorHover: '#00b0a0',
	dayCellSize: 15,
	limit: 10000,
	topLimit: 5,


	addLegend: function(chart, selector) {
		const legend = chart.generateLegend();
		document.querySelector(selector + ' .chart-legend').appendChild(legend);

		legend.querySelectorAll('.item').forEach(function(item) {
			item.addEventListener('click', function() {
				const index = item.dataset.index;
				const meta = chart.getDatasetMeta(index);

				meta.hidden = !meta.hidden;
				item.classList.toggle('disabled');

				chart.update();
			});
		});
	},


	context: function(selector) {
		const canvas = document.querySelector(selector + ' .chart canvas');
		if(!canvas) {
			throw "Unable to create canvas for " + selector + " .chart canvas";
		}
		return canvas.getContext('2d');
	},

	done: function(selector) {
		document.querySelectorAll(selector + ' .loading').forEach(function(el) {
			el.classList.remove('loading');
		});
	},

	gradient: function(color, alpha, ctx) {
		const gradient = ctx.createLinearGradient(0,0 , 0,280);
		let colorHigh = color, colorLow = color;

		if(Array.isArray(color)) {
			[colorHigh, colorLow] = color;
			colorLow = colorLow || colorHigh;
		}

		gradient.addColorStop(0, Color(colorLow).alpha(alpha).rgbaString());
		gradient.addColorStop(1, colorHigh);

		return gradient;
	},

	legend: function(chart) {
		const legend = document.createElement('div');
		legend.classList.add('legend');

		chart.config.data.datasets.forEach(function(dset, idx) {

			const label = document.createElement('span');
			label.classList.add('label');
			label.appendChild(document.createTextNode(dset.label));

			const color = document.createElement('span');
			color.classList.add('color');
			color.style.backgroundColor = dset.backgroundColor;

			const item = document.createElement('div');
			item.classList.add('item');
			item.dataset.index = idx;

			item.appendChild(color);
			item.appendChild(label);
			legend.appendChild(item);
		});

		return legend;
	},

	log: function(response) {
		if(response.responseJSON && response.responseJSON.errors && response.responseJSON.errors[0] ) {
			console.error('[Aimeos] Failed fetching data:', response.responseJSON.errors[0].title);
		} else {
			console.error('Aimeos] Error:', response);
		}
	},


	init : function() {

		Aimeos.lazy(".order-countday .chart", this.chartDay.bind(this));
		Aimeos.lazy(".order-counthour .chart", this.chartHour.bind(this));
		Aimeos.lazy(".order-countpaystatus .chart", this.chartPaymentStatus.bind(this));
		Aimeos.lazy(".order-countcountry .chart", this.chartCountry.bind(this));
	},


	chartDay : function() {

		const self = this;
		const ctx = this.context('.order-countday');

		const cellWidth = this.dayCellSize + 4;
		const width = document.querySelector('.order-countday .chart').clientWidth - 100;
		const weeks = Math.ceil((width - cellWidth) / cellWidth);

		const keys = "order.cdate";
		const startdate = moment().utc().subtract(weeks, 'weeks').startOf('day');
		const enddate = moment().utc().startOf('day');
		const criteria = {"&&": [
			{">": {"order.cdate": startdate.toISOString().substr(0, 10)}},
			{"<=": {"order.cdate": enddate.toISOString().substr(0, 10)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.cdate", this.limit).then(function(response) {

			const data = [], map = {}, date = startdate.clone();
			let max = 0;

			for(const entry of response.data) {
				map[entry['id']] = entry['attributes'];
				max = Math.max(max, entry['attributes']);
			}

			do {
				data.push({
					x: date.format('YYYY-MM-DD'),
					y: date.format('e'),
					v: map[date.toISOString().substr(0, 10)] || 0
				});
				date.add(1, 'days');
			} while(date.isBefore(enddate, 'day'));


			new Chart(ctx, {
				type: 'matrix',
				responsive: true,
				maintainAspectRatio: false,
				data: {
					datasets: [{
						data: data,
						backgroundColor: function(ctx) {
							return self.color[(ctx.dataset.data[ctx.dataIndex].v / max * 9).toFixed()];
						},
						borderColor: function(ctx) {
							return self.color[(ctx.dataset.data[ctx.dataIndex].v / max * 9).toFixed()];
						},
						borderWidth: 1,
						borderSkipped: false,
						hoverBackgroundColor: self.dayColorHover,
						hoverBorderColor: self.dayColorHover,
						width: self.dayCellSize,
						height: self.dayCellSize
					}]
				},
				options: {
					animation: {
						duration: 0
					},
					responsive: true,
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					tooltips: {
						displayColors: false,
						callbacks: {
							title: function() { return '';},
							label: function(item, data) {
								const entry = data.datasets[item.datasetIndex].data[item.index] || {};
								return [moment(entry.x).format('ll') + ": " + entry.v];
							}
						}
					},
					scales: {
						xAxes: [{
							type: 'time',
							position: 'bottom',
							offset: true,
							time: {
								unit: 'month',
								round: 'week',
								displayFormats: {
									month: 'MMM'
								}
							},
							ticks: {
								maxRotation: 0,
								autoSkip: true
							},
							gridLines: {
								display: false,
								drawBorder: false,
								tickMarkLength: 0,
							}
						}],
						yAxes: [{
							type: 'time',
							offset: true,
							position: 'left',
							time: {
								unit: 'day',
								parser: 'e',
								displayFormats: {
									day: 'ddd'
								}
							},
							ticks: {
								 // workaround, see: https://github.com/chartjs/Chart.js/pull/6257
								maxRotation: 90,
								reverse: true,
							},
							gridLines: {
								display: false,
								drawBorder: false,
								tickMarkLength: 0
							}
						}]
					}
				}
			});

		}).catch(function(response) {
			self.log(response);
		}).then(function() {
			self.done('.order-countday');
		});
	},


	chartHour : function() {

		const self = this;
		const keys = "order.chour";
		const ctx = this.context('.order-counthour');
		const startdate = moment().utc().subtract(12, 'months');
		const enddate = moment().utc().startOf('day');
		const criteria = {"&&": [
			{">": {"order.cdate": startdate.toISOString().substr(0, 10)}},
			{"<=": {"order.cdate": enddate.toISOString().substr(0, 10)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.cdate", this.limit).then(function(response) {

			const dset = [], map = {};

			for(const entry of response.data) {
				map[entry['id']] = entry['attributes'];
			}

			for(let i=0; i<24; i++) {
				dset.push(map[i] || 0);
			}

			new Chart(ctx, {
				type: 'bar',
				data: {
					labels: [...Array(24).keys()],
					datasets: [{
						data: dset,
						borderWidth: 0,
						backgroundColor: self.gradient(self.hourColor, 1, ctx),
						hoverBackgroundColor: self.gradient(self.hourColor, 0.5, ctx)
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					legend: false,
					tooltips: {
						mode: 'index',
						intersect: false,
						position: 'nearest'
					},
					hover: {
						mode: 'index',
						intersect: false
					},
					scales: {
						xAxes: [{
							display: true,
							gridLines: {
								drawOnChartArea: false
							},
						}],
						yAxes: [{
							display: true,
							gridLines: {
								drawOnChartArea: false
							},
						}]
					}
				}
			});

		}).catch(function(response) {
			self.log(response);
		}).then(function() {
			self.done('.order-counthour');
		});
	},


	chartPaymentStatus : function() {

		const self = this;
		const keys = "order.statuspayment,order.cdate";
		const ctx = this.context('.order-countpaystatus');
		const labels = JSON.parse(document.querySelector('.order-countpaystatus').dataset.labels) || {};
		const startdate = moment().utc().subtract(30, 'days');
		const enddate = moment().utc().startOf('day');
		const criteria = {"&&": [
			{">": {"order.cdate": startdate.toISOString().substr(0, 10)}},
			{"<=": {"order.cdate": enddate.toISOString().substr(0, 10)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.cdate", this.limit).then(function(response) {

			const dsets = [];

			for(const entry of response.data) {
				const data = [], date = startdate.clone();

				do {
					let day = date.toISOString().substr(0, 10);

					data.push({x: date.toISOString(), y: entry['attributes'][day] || 0});
					date.add(1, 'days');
				} while(date.isBefore(enddate, 'day'));

				dsets.push({
					id: entry['id'], data: data,
					label: labels[entry['id']], borderWidth: 0,
					backgroundColor: Color(self.paystatusColor[entry['id']+1]).rgbString(),
					hoverBackgroundColor: Color(self.paystatusColor[entry['id']+1]).alpha(0.5).rgbaString(),
				});
			}

			const config = {
				type: 'bar',
				data: {
					datasets: dsets.sort((a, b) => a.id - b.id)
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					tooltips: {
						mode: 'index',
						intersect: false,
						position: 'nearest'
					},
					hover: {
						mode: 'index',
						intersect: false
					},
					scales: {
						xAxes: [{
							type: 'time',
							time: {
								unit: 'day'
							},
							display: true,
							distribution: 'series',
							gridLines: {
								drawOnChartArea: false
							},
							offset: true,
							stacked: true,
						}],
						yAxes: [{
							display: true,
							gridLines: {
								drawOnChartArea: false
							},
							stacked: true,
						}]
					},
					legend: false,
					legendCallback: self.legend
				}
			};

			self.addLegend(new Chart(ctx, config), '.order-countpaystatus');

		}).catch(function(response) {
			self.log(response);
		}).then(function() {
			self.done('.order-countpaystatus');
		});
	},


	chartCountry : function() {

		const self = this;
		const keys = "order.base.address.countryid";
		const ctx = this.context('.order-countcountry');
		const startdate = moment().utc().subtract(12, 'months').startOf('day');
		const enddate = moment().utc().startOf('day');
		const criteria = {"&&": [
			{">": {"order.cdate": startdate.toISOString().substr(0, 10)}},
			{"<=": {"order.cdate": enddate.toISOString().substr(0, 10)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.cdate", this.limit).then(function(response) {

			let max = 0;
			const map = {};
			const labels = JSON.parse(document.querySelector('.order-countcountry').dataset.labels);
			const geo = JSON.parse(document.querySelector('.order-countcountry .chart').dataset.map);
			const countries = ChartGeo.topojson.feature(geo, geo.objects.countries).features;

			for(const entry of response.data) {
				map[entry.id] = entry.attributes;
				max = Math.max(max, entry.attributes);
			}

			new Chart(ctx, {
				type: 'choropleth',
				data: {
					labels: countries.map((c) => labels[c.id] || c.id),
					datasets: [{
						outline: countries,
						data: countries.map((c) => ({feature: c, value: map[c.id] || 0})),
						backgroundColor: function(ctx) {
							const v = ctx.dataIndex && ctx.dataset.data[ctx.dataIndex].value || 0;
							return self.color[(v / max * 9).toFixed()];
						},
				  }]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					showOutline: false,
					showGraticule: false,
					legend: {
						display: false
					},
					scale: {
						projection: 'naturalEarth1'
					},
					geo: {
						colorScale: {
							display: false,
						}
					}
				}
			});


			const toplist = document.querySelector('.order-countcountry .toplist');

			if(toplist) {
				response.data.sort((a, b) => a.attributes - b.attributes);

				for(const entry of response.data.slice(0, self.topLimit)) {

					const country = document.createElement('td');
					country.classList.add('country');
					country.appendChild(document.createTextNode(labels[entry.id] || entry.id));

					const number = document.createElement('td');
					number.classList.add('number');
					number.appendChild(document.createTextNode(entry.attributes));

					const item = document.createElement('tr');
					item.classList.add('item');

					item.appendChild(country);
					item.appendChild(number);
					toplist.appendChild(item);
				}
			}

		}).catch(function(response) {
			self.log(response);
		}).then(function() {
			self.done('.order-countcountry');
		});
	}
};



$(function() {
	Aimeos.Dashboard.Order.init();
});
