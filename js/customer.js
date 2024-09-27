/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 */



Aimeos.Customer = {

	init() {
		const node = document.querySelector('.item-customer #basic');

		if(node) {
			Aimeos.apps['customer'] = Aimeos.app({
				props: {
					data: {type: String, default: '{}'},
					siteid: {type: String, default: ''},
				},
				data() {
					return {
						item: null,
					}
				},
				beforeMount() {
					this.Aimeos = Aimeos;
					this.item = JSON.parse(this.data) || {};
				},
				mixins: [this.mixins]
			}, {...node.dataset || {}}).mount(node);
		}
	},


	mixins: {
		computed: {
			list() {
				const list = []
				for(const code in (this.item['groups'] || {})) {
					list.push({id: this.item['groups'][code], code: code})
				}
				return list
			}
		},
		methods: {
			can(action) {
				return Aimeos.can(action, this.item['customer.siteid'] || null, this.siteid)
			},


			clear() {
				this.item['groups'] = {}
			},


			deselect(option) {
				delete this.item.groups[option.code]
			},


			fetch(query) {
				const filter = {'||': [
					{'==': {'group.id': query}},
					{'=~': {'group.code': query}},
					{'=~': {'group.label': query}}
				]}
				return Aimeos.query(`query {
					searchGroups(filter: ` + JSON.stringify(JSON.stringify(filter)) + `) {
						items {
							id
							code
							label
						}
					}
				}`).then(result => {
					return (result?.searchGroups?.items || [])
				})
			},


			use(option) {
				this.item['groups'][option.code] = option.id
			}
		}
	}
};



(function() {
	Aimeos.Customer.init();
})();
