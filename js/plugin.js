/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */



Aimeos.Plugin = {

	init() {
		const { createApp } = Vue

		Aimeos.components['plugin'] = createApp({
			el: document.querySelector('.item-plugin #basic'),
			data() {
				return {
					item: null,
					cache: {},
					decorators: [],
					providers: [],
					siteid: null,
				}
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
				return Aimeos.can(action, this.item['plugin.siteid'] || null, this.siteid)
			},


			config(provider, type) {
				if(!provider) return []
				if(this.cache[provider]) return this.cache[provider]

				return this.cache[provider] = Aimeos.query(`query {
					getPluginConfig(provider: ` + JSON.stringify(provider) + `, type: ` + JSON.stringify(type) + `) {
						code
						label
						type
						default
						required
					}
				}`).then(result => {
					return (result?.getPluginConfig || []).map(entry => {
						entry.key = entry.code
						return entry
					})
				})
			},


			decorate(name) {
				if(!(new String(this.item['plugin.provider'])).includes(name)) {
					this.item['plugin.provider'] = this.item['plugin.provider'] + ',' + name
				}
			},
		}
	}
};



$(function() {

	Aimeos.Plugin.init();
});
