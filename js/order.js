/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


Aimeos.Order = {

	init() {
		const { createApp } = Vue

		Aimeos.components['order'] = createApp({
			el: document.querySelector('.item-order .order-item'),
			data() {
				return {
					item: {},
					siteid: null,
				}
			},
			mounted() {
				this.Aimeos = Aimeos;
				this.item = JSON.parse(this.$el.dataset.item || '{}');
				this.siteid = this.$el.dataset.siteid;
			},
			mixins: [this.mixins]
		});
	},


	mixins: {
		methods: {
			can(action) {
				return Aimeos.can(action, this.item['order.siteid'] || null, this.siteid)
			},

			customer(input) {
				const filter = {
					'||': [
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
				this.$set(this.item, 'customer.code', ev['customer.code']);
				this.$set(this.item, 'customer.id', ev['customer.id']);
			},
		}
	},
};



$(function() {

	Aimeos.Order.init();
});
