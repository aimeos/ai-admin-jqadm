/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */


 Aimeos.Dashboard.Service = {

	theme: 'light',
	colorBg: {dark: '#202550', light: '#ffffff'},
	colors: ['#30a0e0', '#00b0a0', '#ff7f0e', '#e03028', '#00c8f0', '#00d0b0', '#c8d830', '#f8b820'],

	config: {
		type: 'doughnut',
		data: {},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			cutoutPercentage: 75,
			legendCallback: self.legend,
			legend: false,
			plugins: {
				doughnutlabel: {
					labels: []
				}
			},
			tooltips: {
				callbacks: {}
			},
		},
	},

	limit: 10000,


	addLegend: function(chart, selector) {
		const legend = chart.generateLegend();
		document.querySelector(selector + ' .chart-legend').appendChild(legend);

		legend.querySelectorAll('.item').forEach(function(item) {
			item.addEventListener('click', function() {
				const index = item.dataset.index;
				const ilen = (chart.data.datasets || []).length;

				for(let i = 0; i < ilen; ++i) {
					const meta = chart.getDatasetMeta(i);
					if (meta.data[index]) {
						meta.data[index].hidden = !meta.data[index].hidden;
						item.classList.toggle('disabled');
					}
				}

				chart.update();
			});
		});
	},

	color: function(index) {
		return this.colors[index % this.colors.length];
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

	gradient: function(alpha, context) {
		const chartArea = context.chart.chartArea;
		const ctx = context.chart.ctx;

		if (!chartArea) { // not available on initial chart load
			return null;
		}

		var centerX = (chartArea.left + chartArea.right) / 2;
		var centerY = (chartArea.top + chartArea.bottom) / 2;
		var r = Math.max(Math.min((chartArea.right - chartArea.left) / 2, (chartArea.bottom - chartArea.top) / 2), 0);

		gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, r);
		gradient.addColorStop(1, Color(this.color(context.dataIndex)).alpha(alpha).rgbaString());
		gradient.addColorStop(0, Color(this.theme == 'dark' ? '#000000' : '#ffffff').alpha(1).rgbaString());

		return gradient;
	},

	legend: function(chart) {
		const self = this;
		const labels = chart.config.data.labels || [];

		const legend = document.createElement('div');
		legend.classList.add('legend');

		chart.config.data.datasets.forEach(function(dset) {
			dset.data.forEach(function(val, idx) {

				const label = document.createElement('span');
				label.classList.add('label');
				label.appendChild(document.createTextNode(labels[idx] || ''));

				const color = document.createElement('span');
				color.classList.add('color');
				color.style.backgroundColor = Color(self.color(idx)).alpha(self.theme == 'dark' ? 0.75 : 1).rgbaString();

				const item = document.createElement('div');
				item.classList.add('item');
				item.dataset.index = idx;

				item.appendChild(color);
				item.appendChild(label);
				legend.appendChild(item);
			});
		});

		return legend;
	},


	init : function() {

		this.theme = document.querySelector('body.dark') ? 'dark' : 'light';

		Aimeos.lazy(".order-servicepayment .chart", this.chartPayment.bind(this));
		Aimeos.lazy(".order-servicedelivery .chart", this.chartDelivery.bind(this));
	},



	chartDelivery : function() {

		const self = this;
		const keys = "order.base.service.code";
		const ctx = this.context('.order-servicedelivery');
		const startdate = moment().utc().startOf('day').subtract(12, 'months');
		const enddate = moment().utc().endOf('day');
		const criteria = {"&&": [
			{"==": {"order.base.service.type": "delivery"}},
			{">": {"order.ctime": startdate.toISOString().substr(0, 19)}},
			{"<=": {"order.ctime": enddate.toISOString().substr(0, 19)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.ctime", this.limit).then(function(response) {

			let num = 0;
			const data = [], labels = [];

			for(const entry of response.data) {
				data.push(entry['attributes']);
				labels.push(entry['id']);
				num++;
			}

			const config = JSON.parse(JSON.stringify(self.config)); // deep copy

			config.data.labels = labels;
			config.data.datasets = [{
				hoverBackgroundColor: self.gradient.bind(self, self.theme == 'dark' ? 1 : 0.75),
				backgroundColor: self.gradient.bind(self, self.theme == 'dark' ? 0.75 : 1),
				borderColor: self.colorBg[self.theme],
				data: data
			}];

			config.options.legendCallback = self.legend.bind(self);
			config.options.tooltips.callbacks.labelColor = function(item) {
				return {borderColor: '#000', backgroundColor: self.color(item.index)};
			};

			if(data.length) {
				config.options.plugins.doughnutlabel.labels.push({
					text: document.querySelector('.order-servicedelivery').dataset.title,
					font: {size: '16'},
					color: 'grey'
				});
			}

			self.addLegend(new Chart(ctx, config), '.order-servicedelivery');

		}).then(function() {
			self.done('.order-servicedelivery');
		});
	},



	chartPayment : function() {

		const self = this;
		const keys = "order.base.service.code";
		const ctx = this.context('.order-servicepayment');
		const startdate = moment().utc().startOf('day').subtract(12, 'months');
		const enddate = moment().utc().endOf('day');
		const criteria = {"&&": [
			{"==": {"order.base.service.type": "payment"}},
			{">": {"order.ctime": startdate.toISOString().substr(0, 19)}},
			{"<=": {"order.ctime": enddate.toISOString().substr(0, 19)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.ctime", this.limit).then(function(response) {

			let num = 0;
			const data = [], labels = [];

			for(const entry of response.data) {
				data.push(entry['attributes']);
				labels.push(entry['id']);
				num++;
			}

			const config = JSON.parse(JSON.stringify(self.config)); // deep copy

			config.data.labels = labels;
			config.data.datasets = [{
				hoverBackgroundColor: self.gradient.bind(self, self.theme == 'dark' ? 1 : 0.75),
				backgroundColor: self.gradient.bind(self, self.theme == 'dark' ? 0.75 : 1),
				borderColor: self.colorBg[self.theme],
				data: data
			}];

			config.options.legendCallback = self.legend.bind(self);
			config.options.tooltips.callbacks.labelColor = function(item) {
				return {borderColor: '#000', backgroundColor: self.color(item.index)};
			};

			if(data.length) {
				config.options.plugins.doughnutlabel.labels.push({
					text: document.querySelector('.order-servicepayment').dataset.title,
					font: {size: '16'},
					color: 'grey'
				});
			}

			self.addLegend(new Chart(ctx, config), '.order-servicepayment');

		}).then(function() {
			self.done('.order-servicepayment');
		});
	}
};



$(function() {
	Aimeos.Dashboard.Service.init();
});
