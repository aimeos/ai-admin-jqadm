/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021
 */


Vue.component('dashboard-order-quick-counttotal', {
	props: {
		limit: {type: Number, default: 10000},
	},

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

	mounted() {
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
				{">": {"order.cdate": this.startdate.toISOString().substr(0, 10)}},
				{"<=": {"order.cdate": this.enddate.toISOString().substr(0, 10)}},
			]};
		},

		fetch() {
			const self = this;
			self.state = 'load';

			Aimeos.Dashboard.getData("order", "order.cdate", self.criteria(), "-order.cdate", self.limit).then(function(response) {
				self.update(response.data);
			}).then(function() {
				self.state = 'done';
			});
		},

		update(data) {
			let before = 0, current = 0;

			for(const entry of data) {
				if(new Date(entry['id']) < this.startdate) {
					before += Number(entry['attributes']);
				} else {
					current += Number(entry['attributes']);
				}
			}

			this.before = before;
			this.current = current;
		}
	}
});
