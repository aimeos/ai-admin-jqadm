/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2023
 */


Aimeos.Basket = {

	init() {
		Aimeos.components['basket'] = new Vue({
			el: document.querySelector('.item-basket .basket-item'),
			data: {
				item: {},
				siteid: null,
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
				if(this.item['order.basket.siteid']) {
					let allow = (new String(this.item['order.basket.siteid'])).startsWith(this.siteid);

					switch(action) {
						case 'change': return allow;
					}
				}

				return false;
			},
		}
	},
};



$(function() {

	Aimeos.Basket.init();
});
