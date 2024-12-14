/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


Aimeos.Price = {

	init() {
		const node = document.querySelector('#item-price-group');

		if(node) {
			Aimeos.apps['price'] = Aimeos.app({
				props: {
					data: {type: String, default: '[]'},
					domain: {type: String, default: ''},
					siteid: {type: String, default: ''},
				},
				data() {
					return {
						items: [],
					}
				},
				beforeMount() {
					this.Aimeos = Aimeos;
					this.items = JSON.parse(this.data);

					if(this.items[0]) {
						this.items[0]['_show'] = true;
					}
				},
				mixins: [this.mixins]
			}, {...node.dataset || {}}).mount(node);
		}
	},

	mixins: {
		methods: {
			active(idx) {
				return this.items[idx] && this.items[idx]['price.status'] > 0;
			},


			add(data = {}) {
				const entry = {};

				entry[this.domain + '.lists.id'] = null;
				entry[this.domain + '.lists.type'] = 'default';
				entry[this.domain + '.lists.siteid'] = this.siteid;
				entry[this.domain + '.lists.datestart'] = null;
				entry[this.domain + '.lists.dateend'] = null;

				entry['price.id'] = null;
				entry['price.label'] = null;
				entry['price.type'] = 'default';
				entry['price.siteid'] = this.siteid;
				entry['price.taxrates'] = {'': ''};
				entry['price.currencyid'] = null;
				entry['price.rebate'] = '0.00';
				entry['price.costs'] = '0.00';
				entry['price.value'] = null;
				entry['price.quantity'] = 1;
				entry['price.status'] = 1;

				entry['property'] = [];
				entry['config'] = [];
				entry['_show'] = true;

				this.items.push(Object.assign(entry, data));
			},


			can(action, idx) {
				return Aimeos.can(action, this.items[idx][this.domain + '.lists.siteid'] || null, this.siteid)
			},


			label(idx) {
				let label = '';

				if(this.items[idx]) {
					label += (this.items[idx]['price.quantity'] ? this.items[idx]['price.quantity'] + ' ~ ' : '');
					label += (this.items[idx]['price.value'] ? this.items[idx]['price.value'] : '...');
					label += (this.items[idx]['price.costs'] ? ' + ' + this.items[idx]['price.costs'] : '');
					label += (this.items[idx]['price.currencyid'] ? ' ' + this.items[idx]['price.currencyid'] : '');
					label += (this.items[idx]['price.type'] ? ' (' + this.items[idx]['price.type'] + ')' : '');
				}

				return label;
			},


			remove(idx) {
				this.items.splice(idx, 1);
			},


			toggle(what, idx) {
				if(this.items[idx]) {
					this.items[idx][what] = (!this.items[idx][what] ? true : false);
				}
			}
		}
	}
};


document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Price.init();
});
