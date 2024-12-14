/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2024
 */


Aimeos.Dashboard.Order = {

	theme: 'light',
	colorHour: '#30a0e0',
	colorDayHover: '#00b0a0',
	colorsBg: {dark: '#404570', light: '#ffffff'},
	colorsText: {dark: '#d0e8ff', light: '#505860'},
	colorScale: {
		dark: ['#404570', '#4a5082', '#545a92', '#5d64a2', '#6d73ab', '#7d83b5', '#8e92be', '#9ea1c7', '#aeb0d0', '#bec0da'],
		light: ['#f2f2f2', '#e6eff7', '#d4e1ed', '#c9def2', '#a5d2e8', '#7bbee6', '#54a4d7', '#3586ca', '#2069b4', '#2c5490']
	},
	paystatusColor: ['#d3d3d3', '#e15759', '#f28e2b', '#edc948', '#5bb3e6', '#30a0e0', '#00ccbb', '#00b0a0'],
	dayCellSize: 15,
	topLimit: 5,
	rtl: false,


	context(selector) {
		const canvas = document.querySelector(selector + ' .chart canvas');
		if(!canvas) {
			throw "Unable to create canvas for " + selector + " .chart canvas";
		}
		return canvas.getContext('2d');
	},


	done(selector) {
		document.querySelectorAll(selector + ' .loading').forEach(function(el) {
			el.classList.remove('loading');
		});
	},


	gradient(color, alpha, ctx) {
		const gradient = ctx.createLinearGradient(0,0 , 0,280);

		gradient.addColorStop(0, color + alpha);
		gradient.addColorStop(1, this.colorsBg[this.theme]);

		return gradient;
	},


	init() {
		this.theme = document.querySelector('body.dark') ? 'dark' : 'light';
		this.rtl = document.documentElement.getAttribute('dir') === 'rtl' ? true : false;

		Aimeos.lazy(".order-countday .chart", this.chartDay.bind(this));
		Aimeos.lazy(".order-counthour .chart", this.chartHour.bind(this));
		Aimeos.lazy(".order-countpaystatus .chart", this.chartPaymentStatus.bind(this));
		Aimeos.lazy(".order-countcountry .chart", this.chartCountry.bind(this));
	},


	chartDay() {

		const self = this;
		const ctx = this.context('.order-countday');

		const cellWidth = this.dayCellSize + 4;
		const width = document.querySelector('.order-countday .chart').clientWidth - 100;
		const weeks = Math.ceil((width - cellWidth) / cellWidth);

		const keys = "order.cdate";
		const startdate = moment().utc().startOf('day').subtract(weeks, 'weeks');
		const enddate = moment().utc().endOf('day');
		const criteria = {"&&": [
			{">": {"order.ctime": startdate.toISOString().substr(0, 19)}},
			{"<=": {"order.ctime": enddate.toISOString().substr(0, 19)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.ctime", 10000).then(function(map) {

			const data = [], date = startdate.clone();
			let max = 0;

			for(const key in map) {
				max = Math.max(max, map[key]);
			}

			do {
				data.push({
					x: date.format('YYYY-MM-DD'),
					y: date.format('e'),
					v: map[date.toISOString().substr(0, 10)] || 0
				});
				date.add(1, 'days');
			} while(date.isBefore(enddate, 'day') || date.isSame(enddate, 'day'));


			new Chart(ctx, {
				type: 'matrix',
				responsive: true,
				maintainAspectRatio: false,
				data: {
					datasets: [{
						data: data,
						backgroundColor: (ctx) => {
							return self.colorScale[self.theme][(ctx.dataset.data[ctx.dataIndex].v / max * 9).toFixed()];
						},
						borderColor: (ctx) => {
							return self.colorScale[self.theme][(ctx.dataset.data[ctx.dataIndex].v / max * 9).toFixed()];
						},
						borderWidth: 1,
						borderSkipped: false,
						hoverBackgroundColor: self.colorDayHover,
						hoverBorderColor: self.colorDayHover,
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
					plugins: {
						legend: {
							display: false
						},
						tooltip: {
							displayColors: false,
							callbacks: {
								title: () => '',
								label: (item) => {
									const entry = item.dataset.data[item.dataIndex] || {};
									return [moment(entry.x).format('ll') + ": " + entry.v];
								}
							},
							bodyAlign: self.rtl ? 'right' : 'left',
							rtl: self.rtl
						},
					},
					scales: {
						x: {
							type: 'time',
							position: 'bottom',
							time: {
								unit: 'month',
								round: 'week',
								displayFormats: {
									month: 'MMM'
								}
							},
							grid: {
								display: false,
							},
							ticks: {
								color: self.colorsText[self.theme]
							}
						},
						y: {
							type: 'time',
							offset: true,
							position: self.rtl ? 'right' : 'left',
							time: {
								unit: 'day',
								parser: 'e',
								displayFormats: {
									day: 'ddd'
								}
							},
							grid: {
								display: false,
							},
							ticks: {
								color: self.colorsText[self.theme]
							}
						}
					}
				}
			});

		}).then(function() {
			self.done('.order-countday');
		});
	},


	chartHour() {

		const self = this;
		const keys = "order.chour";
		const ctx = this.context('.order-counthour');
		const startdate = moment().utc().startOf('day').subtract(12, 'months');
		const enddate = moment().utc().endOf('day');
		const criteria = {"&&": [
			{">": {"order.ctime": startdate.toISOString().substr(0, 19)}},
			{"<=": {"order.ctime": enddate.toISOString().substr(0, 19)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.ctime", 10000).then(function(map) {

			const dset = [];

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
						backgroundColor: self.gradient(self.colorHour, 'ff', ctx),
						hoverBackgroundColor: self.gradient(self.colorHour, '80', ctx)
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					hover: {
						mode: 'index',
						intersect: false
					},
					plugins: {
						legend: {
							display: false
						},
						tooltip: {
							mode: 'index',
							intersect: false,
							position: 'nearest',
							rtl: self.rtl,
							callbacks: {
								title: () => '',
								label: (item) => {
									return [item.label + ':00 - ' + item.label + ':59' + " : " + item.formattedValue];
								}
							},
						},
					},
					scales: {
						x: {
							display: true,
							distribution: 'series',
							grid: {
								color: self.colorsText[self.theme],
								drawOnChartArea: false
							},
							ticks: {
								color: self.colorsText[self.theme]
							}
						},
						y: {
							display: true,
							grid: {
								color: self.colorsText[self.theme],
								drawOnChartArea: false
							},
							position: self.rtl ? 'right' : 'left',
							ticks: {
								min: 0,
								callback: (value) => {
									return Number.isInteger(value) ? value : '';
								},
								color: self.colorsText[self.theme]
							}
						}
					}
				}
			});

		}).then(function() {
			self.done('.order-counthour');
		});
	},


	chartPaymentStatus() {

		const self = this;
		const ctx = this.context('.order-countpaystatus');
		const keys = ["order.statuspayment", "order.cdate"];
		const labels = JSON.parse(document.querySelector('.order-countpaystatus').dataset.labels) || {};
		const startdate = moment().utc().startOf('day').subtract(30, 'days');
		const enddate = moment().utc().endOf('day');
		const criteria = {"&&": [
			{">": {"order.ctime": startdate.toISOString().substr(0, 19)}},
			{"<=": {"order.ctime": enddate.toISOString().substr(0, 19)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.ctime", 10000).then(function(entries) {

			const dsets = [];

			for(const id of [-1, 0, 1, 2, 3, 4, 5, 6]) {
				entries[id] = entries[id] || {};
			}

			for(const id in entries) {
				const data = [], date = startdate.clone();

				do {
					let day = date.toISOString().substr(0, 10);

					data.push({x: date.toISOString(), y: entries[id][day] || 0});
					date.add(1, 'days');
				} while(date.isBefore(enddate, 'day') || date.isSame(enddate, 'day'));

				dsets.push({
					id: id, data: data,
					label: labels[id], borderWidth: 0,
					backgroundColor: self.paystatusColor[Number(id)+1] + (self.theme == 'dark' ? 'bf' : 'ff'),
					hoverBackgroundColor: self.paystatusColor[Number(id)+1] + (self.theme == 'dark' ? 'ff' : 'bf'),
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
					hover: {
						mode: 'index',
						intersect: false
					},
					plugins: {
						legend: {
							labels: {
								color: self.colorsText[self.theme],
								position: self.rtl ? 'right' : 'left',
								usePointStyle: true,
							},
						},
						tooltip: {
							mode: 'index',
							intersect: false,
							position: 'nearest',
							rtl: self.rtl,
							callbacks: {
								title(item) {
									return moment.utc(item[0].raw.x).format('ll');
								}
							}
						},
					},
					scales: {
						x: {
							type: 'time',
							time: {
								unit: 'day'
							},
							display: true,
							distribution: 'series',
							grid: {
								color: self.colorsText[self.theme],
								drawOnChartArea: false
							},
							offset: true,
							stacked: true,
							ticks: {
								color: self.colorsText[self.theme]
							}
						},
						y: {
							display: true,
							grid: {
								color: self.colorsText[self.theme],
								drawOnChartArea: false
							},
							position: self.rtl ? 'right' : 'left',
							stacked: true,
							ticks: {
								min: 0,
								callback: (value) => {
									return Number.isInteger(value) ? value : '';
								},
								color: self.colorsText[self.theme]
							}
						}
					},
				}
			};

			new Chart(ctx, config);

		}).then(function() {
			self.done('.order-countpaystatus');
		});
	},


	chartCountry() {

		const self = this;
		const keys = "order.address.countryid";
		const ctx = this.context('.order-countcountry');
		const startdate = moment().utc().startOf('day').subtract(12, 'months');
		const enddate = moment().utc().endOf('day');
		const criteria = {"&&": [
			{">": {"order.ctime": startdate.toISOString().substr(0, 19)}},
			{"<=": {"order.ctime": enddate.toISOString().substr(0, 19)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.ctime", 10000).then(function(map) {

			let max = 1;
			const list = [];
			const labels = JSON.parse(document.querySelector('.order-countcountry').dataset.labels);
			const geo = JSON.parse(document.querySelector('.order-countcountry .chart').dataset.map);
			const countries = topojson.feature(geo, geo.objects.countries).features;

			for(const key in map) {
				list.push({id: key, val: map[key]});
				max = Math.max(max, map[key]);
			}

			new Chart(ctx, {
				type: 'choropleth',
				data: {
					labels: countries.map((c) => labels[c.id] || c.id),
					datasets: [{
						outline: countries,
						data: countries.map((c) => ({feature: c, value: map[c.id] || 0})),
						backgroundColor: (ctx) => {
							const v = ctx.dataIndex && ctx.dataset.data[ctx.dataIndex].value || 0;
							return self.colorScale[self.theme][(v / max * 9).toFixed()];
						},
				  }]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							display: false,
						}
					},
					scales: {
						color: {
							axis: 'x',
							display: false
						},
						projection: {
							axis: 'x',
							projection: 'naturalEarth1',
						}
					}
				}
			});


			const toplist = document.querySelector('.order-countcountry .toplist');

			if(toplist) {
				list.sort((a, b) => b.val - a.val);

				for(const entry of list.slice(0, self.topLimit)) {

					const country = document.createElement('td');
					country.classList.add('country');
					country.appendChild(document.createTextNode(labels[entry.id] || entry.id));

					const number = document.createElement('td');
					number.classList.add('number');
					number.appendChild(document.createTextNode(entry.val));

					const item = document.createElement('tr');
					item.classList.add('item');

					item.appendChild(country);
					item.appendChild(number);
					toplist.appendChild(item);
				}
			}

		}).then(function() {
			self.done('.order-countcountry');
		});
	}
};



document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Dashboard.Order.init();
});
