/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Aimeos.Service = {

	init() {
		const { createApp } = Vue

		Aimeos.apps['service'] = createApp({
			el: document.querySelector('.item-service #basic'),
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
				return Aimeos.can(action, this.item['service.siteid'] || null, this.siteid)
			},


			config(provider, type) {
				if(!provider) return []
				if(this.cache[provider]) return this.cache[provider]

				return this.cache[provider] = Aimeos.query(`query {
					getServiceConfig(provider: ` + JSON.stringify(provider) + `, type: ` + JSON.stringify(type) + `) {
						code
						label
						type
						default
						required
					}
				}`).then(result => {
					return (result?.getServiceConfig || []).map(entry => {
						entry.key = entry.code
						return entry
					})
				})
			},


			decorate(name) {
				if(!(new String(this.item['service.provider'])).includes(name)) {
					this.item['service.provider'] = this.item['service.provider'] + ',' + name
				}
			},
		}
	}
};



$(function() {

	Aimeos.Service.init();
});
