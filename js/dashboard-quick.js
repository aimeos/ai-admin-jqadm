/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2024
 */


if(document.querySelector('.order-quick-counttotal')) {
	Aimeos.apps['dashboard-order-quick-counttotal'] = Aimeos.app({
		data() {
			return {
				mood: '',
				state: '',
				before: 0,
				current: 0,
				enddate: null,
				startdate: null,
				lastdate: null,
			}
		},

		beforeMount() {
			this.enddate = moment().utc();
			this.startdate = moment().utc().subtract(7, 'days');
			this.lastdate = this.enddate.clone().subtract(this.enddate.diff(this.startdate, 'seconds') * 2, 'seconds');

			this.fetch();
		},

		computed: {
			percent() {
				const num = Math.round((this.current - this.before) * 100 / (this.before || 1));
				this.mood = num >= 1 ? 'positive' : (num < 0 ? 'negative' : 'neutral');
				return (num >= 1 ? '+' : '') + num + '%';
			},

			width() {
				return this.current - this.before < 0 ? 100 - (this.before - this.current) * 100 / (this.before || 1) : 100;
			}
		},

		methods: {
			criteria() {
				return {"&&": [
					{">": {"order.ctime": this.lastdate.toISOString().substr(0, 19)}},
					{"<=": {"order.ctime": this.enddate.toISOString().substr(0, 19)}},
				]};
			},

			fetch() {
				const self = this;
				self.state = 'load';

				Aimeos.Dashboard.getData("order", "order.cdate", self.criteria(), "-order.ctime", 10000).then(function(result) {
					self.update(result);
				}).then(function() {
					self.state = 'done';
				});
			},

			update(data) {
				let before = 0, current = 0;

				for(const key in data) {
					if(new Date(key) < this.startdate) {
						before += Number(data[key]);
					} else {
						current += Number(data[key]);
					}
				}

				this.before = before;
				this.current = current;
			}
		}
	}).mount('.order-quick-counttotal');
}


if(document.querySelector('.order-quick-countcompleted')) {
	Aimeos.apps['dashboard-order-quick-countcompleted'] = Aimeos.app({
		data() {
			return {
				mood: '',
				state: '',
				before: 0,
				current: 0,
				enddate: null,
				startdate: null,
				lastdate: null,
			}
		},

		beforeMount() {
			this.enddate = moment().utc();
			this.startdate = moment().utc().subtract(7, 'days');
			this.lastdate = this.enddate.clone().subtract(this.enddate.diff(this.startdate, 'seconds') * 2, 'seconds');

			this.fetch();
		},

		computed: {
			percent() {
				const num = Math.round((this.current - this.before) * 100 / (this.before || 1));
				this.mood = num >= 1 ? 'positive' : (num < 0 ? 'negative' : 'neutral');
				return (num >= 1 ? '+' : '') + num + '%';
			},

			width() {
				return this.current - this.before < 0 ? 100 - (this.before - this.current) * 100 / (this.before || 1) : 100;
			}
		},

		methods: {
			criteria() {
				return {"&&": [
					{">": {"order.ctime": this.lastdate.toISOString().substr(0, 19)}},
					{"<=": {"order.ctime": this.enddate.toISOString().substr(0, 19)}},
					{"==": {"order.statuspayment": {0: 4, 1: 5, 2:6}}},
				]};
			},

			fetch() {
				const self = this;
				self.state = 'load';

				Aimeos.Dashboard.getData("order", "order.cdate", self.criteria(), "-order.ctime", 10000).then(function(result) {
					self.update(result);
				}).then(function() {
					self.state = 'done';
				});
			},

			update(data) {
				let before = 0, current = 0;

				for(const key in data) {
					if(new Date(key) < this.startdate) {
						before += Number(data[key]);
					} else {
						current += Number(data[key]);
					}
				}

				this.before = before;
				this.current = current;
			}
		}
	}).mount('.order-quick-countcompleted');
}


if(document.querySelector('.order-quick-countunfinished')) {
	Aimeos.apps['dashboard-order-quick-countunfinished'] = Aimeos.app({
		data() {
			return {
				mood: '',
				state: '',
				before: 0,
				current: 0,
				enddate: null,
				startdate: null,
				lastdate: null,
			}
		},

		beforeMount() {
			this.enddate = moment().utc();
			this.startdate = moment().utc().subtract(7, 'days');
			this.lastdate = this.enddate.clone().subtract(this.enddate.diff(this.startdate, 'seconds') * 2, 'seconds');

			this.fetch();
		},

		computed: {
			percent() {
				const num = Math.round((this.current - this.before) * 100 / (this.before || 1));
				this.mood = num >= 1 ? 'negative' : (num < 0 ? 'positive' : 'neutral');
				return (num >= 1 ? '+' : '') + num + '%';
			},

			width() {
				return this.current - this.before < 0 ? 100 - (this.before - this.current) * 100 / (this.before || 1) : 100;
			}
		},

		methods: {
			criteria() {
				return {"&&": [
					{">": {"order.ctime": this.lastdate.toISOString().substr(0, 19)}},
					{"<=": {"order.ctime": this.enddate.toISOString().substr(0, 19)}},
					{"==": {"order.statuspayment": -1}},
				]};
			},

			fetch() {
				const self = this;
				self.state = 'load';

				Aimeos.Dashboard.getData("order", "order.cdate", self.criteria(), "-order.ctime", 10000).then(function(result) {
					self.update(result);
				}).then(function() {
					self.state = 'done';
				});
			},

			update(data) {
				let before = 0, current = 0;

				for(const key in data) {
					if(new Date(key) < this.startdate) {
						before += Number(data[key]);
					} else {
						current += Number(data[key]);
					}
				}

				this.before = before;
				this.current = current;
			}
		}
	}).mount('.order-quick-countunfinished');
}


if(document.querySelector('.order-quick-countcustomer')) {
	Aimeos.apps['dashboard-order-quick-countcustomer'] = Aimeos.app({
		data() {
			return {
				mood: '',
				state: '',
				current: 0,
				enddate: null,
				startdate: null,
			}
		},

		beforeMount() {
			this.enddate = moment().utc();
			this.startdate = moment().utc().subtract(7, 'days');

			this.fetch();
		},

		computed: {
			percent() {
				return '';
			},

			width() {
				return this.current ? 100 : 0;
			}
		},

		methods: {
			criteria() {
				return {"&&": [
					{">": {"customer.ctime": this.startdate.toISOString().substr(0, 19)}},
					{"<=": {"customer.ctime": this.enddate.toISOString().substr(0, 19)}},
				]};
			},

			fetch() {
				const self = this;
				self.state = 'load';

				Aimeos.Dashboard.getData("customer", "customer.status", self.criteria(), "-customer.ctime", 10000).then(function(result) {
					self.update(result);
				}).then(function() {
					self.state = 'done';
				});
			},

			update(data) {
				let current = 0;

				for(const key in data) {
					current += Number(data[key]);
				}

				this.current = current;
			}
		}
	}).mount('.order-quick-countcustomer');
}
