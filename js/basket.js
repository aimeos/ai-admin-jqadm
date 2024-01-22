/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org)2023-2024
 */


Aimeos.Basket = {

	init() {
		const { createApp } = Vue

		Aimeos.components['basket'] = createApp({
			el: document.querySelector('.item-basket .basket-item'),
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
				return Aimeos.can(action, this.item['order.basket.siteid'] || null, this.siteid)
			},
		}
	},
};



$(function() {

	Aimeos.Basket.init();
});
