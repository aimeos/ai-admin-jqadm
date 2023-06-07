/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


Aimeos.Order = {

	init() {
		Aimeos.components['order'] = new Vue({
			el: document.querySelector('#order'),
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

		this.addServcieAttributeLine();
		this.deleteServcieAttributeLine();
		this.setupServiceCodeSuggest();
	},


	mixins: {
		methods: {
			can(action) {
				if(this.item['order.siteid']) {
					let allow = (new String(this.item['order.siteid'])).startsWith(this.siteid);

					switch(action) {
						case 'change': return allow;
					}
				}

				return false;
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
					searchCustomers(filter: "` + JSON.stringify(filter).replace(/"/g, '\\"') + `") {
					  id
					  code
					}
				  }
				`).then(result => {
					return (result?.searchCustomers || []).map(item => {
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


	addServiceSuggest(input, node) {

		$(input).autocomplete({
			source: node.data("codes").split(','),
			minLength: 0,
			delay: 0
		});
	},


	addServcieAttributeLine() {

		var self = this;
		$(".aimeos .item-order .service-attr").on("click", ".act-add", function(ev) {

			var id = $(ev.delegateTarget).data("id");
			var node = Aimeos.addClone($(".prototype", ev.delegateTarget));
			self.addServiceSuggest($(".service-attr-code", node), $(ev.delegateTarget));

			$("input", node).each(function() {
				$(this).attr("name", $(this).attr("name").replace("_id_", id));
			});
		});
	},


	deleteServcieAttributeLine() {

		$(".aimeos .item-order .service-attr").on("click", ".act-delete", function(ev) {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	setupServiceCodeSuggest() {

		var node = $(".aimeos .item-order .service-attr");

		if( node.length > 0 ) {
			this.addServiceSuggest($(".service-attr-code", node), node);

			$(".aimeos .item-order .service-attr").on("click", ".service-attr-code", function(ev) {
				$(this).autocomplete("search", "");
			});
		}
	}
};



$(function() {

	Aimeos.Order.init();
});
