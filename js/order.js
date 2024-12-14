/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


Aimeos.Order = {

	init() {
		const node = document.querySelector('.item-order .order-item')

		if(node) {
			Aimeos.apps['order'] = Aimeos.app({
				props: {
					data: {type: String, default: '{}'},
					siteid: {type: String, default: ''},
				},
				data() {
					return {
						item: {},
					}
				},
				beforeMount() {
					this.Aimeos = Aimeos;
					this.item = JSON.parse(this.data || '{}');
				},
				mixins: [this.mixins]
			}, {...node.dataset || {}}).mount(node);
		}
	},


	mixins: {
		methods: {
			can(action) {
				return Aimeos.can(action, this.item['order.siteid'] || null, this.siteid)
			},

			customer(input) {
				const filter = {}

				if(input) {
					filter['||'] = [
						{'=~': {'customer.label': input}},
						{'=~': {'customer.code': input}},
						{'==': {'customer.id': input}}
					]
				}

				return Aimeos.query(`query {
					searchCustomers(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, sort: ["customer.code"]) {
						items {
							id
							code
						}
					}
				  }
				`).then(result => {
					return (result?.searchCustomers?.items || []).map(item => {
						return {'customer.id': item.id, 'customer.code': item.code}
					})
				})
			},


			useCustomer(ev) {
				this.item['customer.code'] = ev['customer.code'];
				this.item['customer.id'] = ev['customer.id'];
			},
		}
	},
};



document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Order.init();
});
