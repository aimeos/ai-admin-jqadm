/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2024
 */


Aimeos.Dashboard.Sales = {

	theme: 'light',
	colors: ['#30a0e0', '#00b0a0', '#ff7f0e', '#e03028', '#00c8f0', '#00d0b0', '#c8d830', '#f8b820'],
	colorsText: {'dark': '#d0e8ff', 'light': '#505860'},
	rtl: false,

	config: {
		type: 'line',
		data: {},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			tension: 0.33,
			hover: {
				mode: 'nearest',
				intersect: true
			},
			plugins: {
				legend: {
					labels: {
						usePointStyle: true,
					},
				},
				tooltip: {
					axis: 'x',
					mode: 'index',
					position: 'nearest',
					intersect: false,
					rtl: this.rtl,
					callbacks: {}
				},
			},
			scales: {
				x: {
					distribution: 'series',
					grid: {
						drawOnChartArea: false
					},
					ticks: {
					}
				},
				y: {
					grid: {
						drawOnChartArea: false
					},
					min: 0,
					position: 'left',
					ticks: {
					}
				}
			},
		}
	},


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


	gradient(ctx, index) {
		const gradient = ctx.createLinearGradient(0,0 , 0,280);

		gradient.addColorStop(0, this.color(index) + '80');
		gradient.addColorStop(0.66, this.color(index) + '00');
		gradient.addColorStop(1, '#000000ff');

		return gradient;
	},


	init() {
		this.theme = document.querySelector('body.dark') ? 'dark' : 'light';
		this.rtl = document.documentElement.getAttribute('dir') === 'rtl' ? true : false;

		if(this.rtl) {
			this.config.options.scales.x.position = 'right';
		}

		if(this.theme === 'dark') {
			this.config.options.plugins.legend.labels.color = this.colorsText['dark'];
			this.config.options.scales.x.ticks.color = this.colorsText['dark'];
			this.config.options.scales.y.ticks.color = this.colorsText['dark'];
			this.config.options.scales.x.grid.color = this.colorsText['dark'];
			this.config.options.scales.y.grid.color = this.colorsText['dark'];
		} else {
			this.config.options.plugins.legend.labels.color = this.colorsText['light'];
			this.config.options.scales.x.ticks.color = this.colorsText['light'];
			this.config.options.scales.y.ticks.color = this.colorsText['light'];
			this.config.options.scales.x.grid.color = this.colorsText['light'];
			this.config.options.scales.y.grid.color = this.colorsText['light'];
		}

		Aimeos.lazy(".order-salesday .chart", this.chartDay.bind(this));
		Aimeos.lazy(".order-salesmonth .chart", this.chartMonth.bind(this));
		Aimeos.lazy(".order-salesweekday .chart", this.chartWeekday.bind(this));
	},


	chartDay() {

		const self = this;
		const ctx = this.context('.order-salesday');
		const keys = ["order.currencyid", "order.cdate"];
		const startdate = moment().utc().startOf('day').subtract(30, 'days');
		const enddate = moment().utc().endOf('day');
		const criteria = {"&&": [
			{">=": {"order.statuspayment": 5}},
			{">": {"order.ctime": startdate.toISOString().substr(0, 19)}},
			{"<=": {"order.ctime": enddate.toISOString().substr(0, 19)}},
			{"==": {"order.product.orderproductid": null}},
			{"||": [
				{"==": {"order.product.statuspayment": -1}},
				{">=": {"order.product.statuspayment": 5}}
			]}
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.ctime", 10000, "agg:order.product:total()", "sum").then(function(result) {

			let num = 0;
			const dsets = [];

			if(Object.keys(result).length === 0) {
				result[''] = {};
			}

			for(const currencyid in result) {
				let data = [];
				let date = startdate.clone();

				do {
					let day = date.toISOString().substr(0, 10);

					data.push({x: date.toISOString(), y: Number(result[currencyid][day] || 0).toFixed(2)});
					date.add(1, 'days');
				} while(date.isBefore(enddate, 'day') || date.isSame(enddate, 'day'));

				dsets.push({
					data: data,
					label: currencyid,
					backgroundColor: self.gradient(ctx, num),
					borderColor: self.color(num) + (self.theme == 'dark' ? 'bf' : 'ff'),
					pointRadius: 2,
				});
				num++;
			}

			const config = JSON.parse(JSON.stringify(self.config)); // deep copy

			config.data.datasets = dsets;
			config.options.scales.x.type = 'time';
			config.options.scales.x.time = {unit: 'day'};
			config.options.plugins.tooltip.callbacks.title = function(item) {
				return moment.utc(item[0].raw.x).format('ll');
			};

			new Chart(ctx, config);

		}).then(function() {
			self.done('.order-salesday');
		});
	},


	chartMonth() {

		const self = this;
		const ctx = this.context('.order-salesmonth');
		const keys = ["order.currencyid", "order.cmonth"];
		const startdate = moment().utc().startOf('month').subtract(12, 'months');
		const enddate = moment().utc().endOf('day');
		const criteria = {"&&": [
			{">=": {"order.statuspayment": 5}},
			{">": {"order.ctime": startdate.toISOString().substr(0, 19)}},
			{"<=": {"order.ctime": enddate.toISOString().substr(0, 19)}},
			{"==": {"order.product.orderproductid": null}},
			{"||": [
				{"==": {"order.product.statuspayment": -1}},
				{">=": {"order.product.statuspayment": 5}}
			]}
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.cmonth", 10000, "agg:order.product:total()", "sum").then(function(result) {

			let num = 0;
			const dsets = [];

			if(Object.keys(result).length === 0) {
				result[''] = {};
			}

			for(const currencyid in result) {
				let data = [];
				let date = startdate.clone();

				do {
					let month = date.toISOString().substr(0, 7);

					data.push({x: date.toISOString(), y: Number(result[currencyid][month] || 0).toFixed(2)});
					date.add(1, 'months');
				} while(date.isBefore(enddate, 'month') || date.isSame(enddate, 'month'));

				dsets.push({
					data: data,
					label: currencyid,
					backgroundColor: self.gradient(ctx, num),
					borderColor: self.color(num) + (self.theme == 'dark' ? 'bf' : 'ff'),
					pointRadius: 2,
				});
				num++;
			}

			const config = JSON.parse(JSON.stringify(self.config)); // deep copy

			config.data.datasets = dsets;
			config.options.scales.x.type = 'time';
			config.options.scales.x.time = {unit: 'month'};
			config.options.scales.x.ticks.callback = function(item) {
				return moment(item).format('MMM');
			};
			config.options.plugins.tooltip.callbacks.title = function(item) {
				return moment(item[0].raw.x).format('MMM YYYY');
			};

			new Chart(ctx, config);

		}).then(function() {
			self.done('.order-salesmonth');
		});
	},


	chartWeekday() {

		const self = this;
		const ctx = this.context('.order-salesweekday');
		const keys = ["order.currencyid", "order.cwday"];
		const startdate = moment().utc().startOf('day').subtract(12, 'months');
		const enddate = moment().utc().endOf('day');
		const criteria = {"&&": [
			{">=": {"order.statuspayment": 5}},
			{">": {"order.ctime": startdate.toISOString().substr(0, 19)}},
			{"<=": {"order.ctime": enddate.toISOString().substr(0, 19)}},
			{"==": {"order.product.orderproductid": null}},
			{"||": [
				{"==": {"order.product.statuspayment": -1}},
				{">=": {"order.product.statuspayment": 5}}
			]}
		]};

		Aimeos.Dashboard.getData("order", keys, criteria, "-order.ctime", 10000, "agg:order.product:total()", "sum").then(function(result) {

			let num = 0;
			const dsets = [];

			if(Object.keys(result).length === 0) {
				result[''] = {};
			}

			for(const currencyid in result) {
				let data = [];

				for(const wday in [...Array(7).keys()]) {
					data[wday] = Number(result[currencyid][wday] || 0).toFixed(2);
				}

				dsets.push({
					data: data,
					label: currencyid,
					backgroundColor: self.gradient(ctx, num),
					borderColor: self.color(num) + (self.theme == 'dark' ? 'bf' : 'ff'),
					pointRadius: 2,
				});
				num++;
			}

			const config = JSON.parse(JSON.stringify(self.config)); // deep copy

			config.data.datasets = dsets;
			config.data.labels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

			new Chart(ctx, config);

		}).then(function() {
			self.done('.order-salesweekday');
		});
	}
};



document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Dashboard.Sales.init();
});
