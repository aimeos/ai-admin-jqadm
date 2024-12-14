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
					groups: {type: String, default: '{}'},
					siteid: {type: String, default: ''},
					super: {type: Number, default: 0},
				},
				data() {
					return {
						item: null,
						groupList: {}
					}
				},
				beforeMount() {
					this.Aimeos = Aimeos;
					this.item = JSON.parse(this.data) || {};
					this.groupList = JSON.parse(this.groups) || {};
				},
				mixins: [this.mixins]
			}, {...node.dataset || {}}).mount(node);
		}
	},


	mixins: {
		computed: {
			list() {
				const list = []
				for(const id of (this.item['groups'] || [])) {
					list.push({id: id, code: this.groupList[id] ? this.groupList[id]['group.code'] : id})
				}
				return list
			}
		},
		methods: {
			can(action) {
				return this.super || Aimeos.can(action, this.item['customer.siteid'] || null, this.siteid)
			},


			clear() {
				this.item['groups'] = []
			},


			deselect(option) {
				const idx = this.item.groups?.indexOf(option.id)
				if(idx !== -1) this.item.groups?.splice(idx, 1)
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
				const idx = this.item.groups.indexOf(option.id)
				if(idx === -1) this.item.groups.push(option.id)
			}
		}
	}
};



document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Customer.init();
});
