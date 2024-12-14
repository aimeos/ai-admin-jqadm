/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org)2023-2024
 */


Aimeos.Basket = {

	init() {
		const node = document.querySelector('.item-basket .basket-item')

		if(node) {
			Aimeos.apps['basket'] = Aimeos.app({
				props: {
					data: {type: String, default: '{}'},
					siteid: {type: String, default: ''},
				},
				data() {
					return {
						item: {}
					}
				},
				beforeMount() {
					this.Aimeos = Aimeos;
					this.item = JSON.parse(this.data);
				},
				mixins: [this.mixins]
			}, {...node.dataset || {}}).mount(node);
		}
	},


	mixins: {
		methods: {
			can(action) {
				return Aimeos.can(action, this.item['order.basket.siteid'] || null, this.siteid)
			},
		}
	},
};



document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Basket.init();
});
