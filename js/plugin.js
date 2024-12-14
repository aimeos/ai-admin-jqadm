/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */



Aimeos.Plugin = {

	init() {
		const node = document.querySelector('.item-plugin #basic');

		if(node) {
			Aimeos.apps['plugin'] = Aimeos.app({
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
						entry.default = JSON.parse(entry.default)
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



document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Plugin.init();
});
