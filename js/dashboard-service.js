/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2024
 */


Aimeos.Dashboard.Service = {

	theme: 'light',
	colorBg: {dark: '#202550', light: '#ffffff'},
	colorsText: {dark: '#d0e8ff', light: '#505860'},
	colors: ['#30a0e0', '#00b0a0', '#ff7f0e', '#e03028', '#00c8f0', '#00d0b0', '#c8d830', '#f8b820'],
	rtl: false,


	color(index) {
		return this.colors[index % this.colors.length];
	},


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


	gradient(alpha, context) {
		const chartArea = context.chart.chartArea;
		const ctx = context.chart.ctx;

		if (!chartArea) { // not available on initial chart load
			return null;
		}

		var centerX = (chartArea.left + chartArea.right) / 2;
		var centerY = (chartArea.top + chartArea.bottom) / 2;
		var r = Math.max(Math.min((chartArea.right - chartArea.left) / 2, (chartArea.bottom - chartArea.top) / 2), 0);

		gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, r);
		gradient.addColorStop(1, this.color(context.dataIndex) + alpha);
		gradient.addColorStop(0, this.theme == 'dark' ? '#000000' : '#ffffff');

		return gradient;
	},


	init() {
		this.theme = document.querySelector('body.dark') ? 'dark' : 'light';
		this.rtl = document.documentElement.getAttribute('dir') === 'rtl' ? true : false;

		Aimeos.lazy(".order-servicepayment .chart", this.chartPayment.bind(this));
		Aimeos.lazy(".order-servicedelivery .chart", this.chartDelivery.bind(this));
	},


	chartDelivery() {

		const self = this;
		const keys = "order.service.code";
		const ctx = this.context('.order-servicedelivery');
		const startdate = moment().utc().startOf('day').subtract(12, 'months');
		const enddate = moment().utc().endOf('day');
		const criteria = {"&&": [
			{"==": {"order.service.type": "delivery"}},
			{">": {"order.ctime": startdate.toISOString().substr(0, 19)}},
			{"<=": {"order.ctime": enddate.toISOString().substr(0, 19)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.ctime", 10000).then(function(result) {

			let num = 0;
			const data = [], labels = [];

			for(const key in result) {
				data.push(result[key]);
				labels.push(key);
				num++;
			}

			const config = {
				type: 'doughnut',
				data: {
					labels: labels,
					datasets: [{
						hoverBackgroundColor: self.gradient.bind(self, self.theme == 'dark' ? 'ff' : 'bf'),
						backgroundColor: self.gradient.bind(self, self.theme == 'dark' ? 'bf' : 'ff'),
						borderColor: self.colorBg[self.theme],
						data: data
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					cutoutPercentage: 75,
					plugins: {
						legend: {
							labels: {
								color: self.colorsText[self.theme],
								usePointStyle: true,
							},
							position: self.rtl ? 'left' : 'right',
							rtl: self.rtl
						},
						tooltip: {
							callbacks: {
								title: () => '',
								label: (item) => {
									return item.label + ": " + item.formattedValue;
								}
							},
						},
					},
				},
			}

			new Chart(ctx, config);

		}).then(function() {
			self.done('.order-servicedelivery');
		});
	},


	chartPayment() {

		const self = this;
		const keys = "order.service.code";
		const ctx = this.context('.order-servicepayment');
		const startdate = moment().utc().startOf('day').subtract(12, 'months');
		const enddate = moment().utc().endOf('day');
		const criteria = {"&&": [
			{"==": {"order.service.type": "payment"}},
			{">": {"order.ctime": startdate.toISOString().substr(0, 19)}},
			{"<=": {"order.ctime": enddate.toISOString().substr(0, 19)}},
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.ctime", 10000).then(function(result) {

			let num = 0;
			const data = [], labels = [];

			for(const key in result) {
				data.push(result[key]);
				labels.push(key);
				num++;
			}

			const config = {
				type: 'doughnut',
				data: {
					labels: labels,
					datasets: [{
						hoverBackgroundColor: self.gradient.bind(self, self.theme == 'dark' ? 'ff' : 'bf'),
						backgroundColor: self.gradient.bind(self, self.theme == 'dark' ? 'bf' : 'ff'),
						borderColor: self.colorBg[self.theme],
						data: data
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					cutoutPercentage: 75,
					plugins: {
						legend: {
							labels: {
								color: self.colorsText[self.theme],
								usePointStyle: true,
							},
							position: self.rtl ? 'left' : 'right',
							rtl: self.rtl
						},
						tooltip: {
							callbacks: {
								title: () => '',
								label: (item) => {
									return item.label + ": " + item.formattedValue;
								}
							},
						},
					},
				},
			}

			new Chart(ctx, config);

		}).then(function() {
			self.done('.order-servicepayment');
		});
	}
};



document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Dashboard.Service.init();
});
