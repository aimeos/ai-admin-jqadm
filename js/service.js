/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2025
 */



Aimeos.Service = {

	init() {
		const node = document.querySelector('.item-service #basic')

		if(node) {
			Aimeos.apps['service'] = Aimeos.app({
				props: {
					data: {type: String, default: '{}'},
					siteid: {type: String, default: ''},
					providers: {type: String, default: '[]'},
					decorators: {type: String, default: '[]'},
				},
				data() {
					return {
						item: null,
						cache: {},
						show: false
					}
				},
				beforeMount() {
					this.Aimeos = Aimeos;
					this.item = JSON.parse(this.data);
					this.show = Aimeos.session('aimeos/jqadm/item/form') == 1
				},
				mixins: [this.mixins]
			}, {...node.dataset || {}}).mount(node);
		}
	},


	mixins: {
		methods: {
			can(action) {
				return Aimeos.can(action, this.item['service.siteid'] || null, this.siteid)
			},


			config(provider, type) {
				if(!provider) return []
				if(this.cache[provider]) return this.cache[provider]

				return this.cache[provider] = Aimeos.graphql(`query {
					getServiceConfig(provider: ` + JSON.stringify(provider) + `, type: ` + JSON.stringify(type) + `) {
						code
						label
						type
						default
						required
					}
				}`).then(result => {
					return (result?.getServiceConfig || []).map(entry => {
						entry.default = JSON.parse(entry.default)
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


			toggle() {
				this.show = Aimeos.session('aimeos/jqadm/item/form', +!this.show)
			}
		}
	}
};



document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Service.init();
});
