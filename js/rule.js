/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2023
 */



 Aimeos.Rule = {

	init() {
		Aimeos.components['rule'] = new Vue({
			el: document.querySelector('.item-rule #basic'),
			data: {
				item: null,
				cache: {},
				decorators: [],
				providers: [],
				siteid: null,
			},
			beforeMount() {
				this.Aimeos = Aimeos;
				this.decorators = JSON.parse(this.$el.dataset.decorators || '[]');
				this.providers = JSON.parse(this.$el.dataset.providers || '[]');
				this.item = JSON.parse(this.$el.dataset.item || '{}');
				this.siteid = this.$el.dataset.siteid;
			},
			mixins: [this.mixins]
		});
	},


	mixins: {
		methods: {
			can(action) {
				if(this.item['rule.siteid']) {
					let allow = (new String(this.item['rule.siteid'])).startsWith(this.siteid);

					switch(action) {
						case 'change': return allow;
					}
				}

				return false;
			},


			config(provider, type) {
				if(!provider) return []
				if(this.cache[provider]) return this.cache[provider]

				return this.cache[provider] = Aimeos.query(`query {
					getRuleConfig(provider: ` + JSON.stringify(provider) + `, type: ` + JSON.stringify(type) + `) {
						code
						label
						type
						default
						required
					}
				}`).then(result => {
					return (result?.getRuleConfig || []).map(entry => {
						entry.key = entry.code
						return entry
					})
				})
			},


			decorate(name) {
				if(!(new String(this.item['rule.provider'])).includes(name)) {
					this.item['rule.provider'] = this.item['rule.provider'] + ',' + name
				}
			},
		}
	}
};



$(function() {

	Aimeos.Rule.init();
});
