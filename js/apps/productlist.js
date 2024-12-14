/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


Aimeos.ProductList = {

	init() {
		const node = document.querySelector('.item-product .productlist');

		if(node) {
			Aimeos.apps['productlist'] = Aimeos.app({
				'mixins': [Aimeos.ProductList.mixins]
			}, {...node.dataset || {}}).mount(node);

			Aimeos.lazy('.item-product .productlist', function() {
				Aimeos.apps['productlist'].fetch();
			});
		}
	},


	mixins: {
		props: {
			status: {type: String, required: true},
			types: {type: String, required: true},
			fields: {type: String, required: true},
			domain: {type: String, required: true},
			siteid: {type: String, required: true},
			refid: {type: String, required: true},
		},

		data() {
			return {
				items: [],
				fieldlist: [],
				filter: {},
				offset: 0,
				limit: 25,
				total: 0,
				typelist: {},
				statuslist: {},
				colselect: false,
				loading: true
			}
		},


		beforeMount() {
			this.Aimeos = Aimeos;
			this.typelist = JSON.parse(this.types) || {};
			this.statuslist = JSON.parse(this.status) || {};
			this.fieldlist = this.columns(null, JSON.parse(this.fields) || []);
		},


		computed: {
			prefix() {
				return 'product.lists.';
			}
		},


		methods: {
			can(action, item) {
				return Aimeos.can(action, item['siteid'] || null, this.siteid)
			},


			columns(list, deflist = []) {
				if(window.sessionStorage) {
					if(list) {
						window.sessionStorage.setItem('aimeos/jqadm/' + this.domain + '/product/fields', JSON.stringify(list));
					} else {
						list = JSON.parse(window.sessionStorage.getItem('aimeos/jqadm/' + this.domain + '/product/fields')) || deflist;
					}
				}
				return list;
			},


			css(key) {
				return 'product-lists-' + key;
			},


			edit(item) {
				if(item && this.can('change', item)) {
					item._edit = true;
					return this;
				}

				this.items.forEach(item => {
					if(item.lists[this.domain]) {
						item.lists[this.domain].forEach(litem => {
							if(litem._checked) {
								litem._edit = true
							}
						})
					}
				})
			},


			fetch() {
				this.loading = true;

				const filter = {'&&': [{'==': {}}]}
				filter['&&'][0]['==']['index.' + this.domain + '.id'] = this.refid

				return Aimeos.query(`query {
					searchIndex(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, include: ["` + this.domain + `"], sort: ["sort:index.` + this.domain + `:position()"], offset: ` + this.offset + `, limit: ` + this.limit + `) {
						items {
							id
							code
							label
							lists {
								` + this.domain + ` {
									id
									type
									siteid
									config
									datestart
									dateend
									position
									status
									refid
									ctime
									mtime
									editor
								}
							}
						}
						total
					}
				  }
				`).then(result => {
					this.total = result?.searchIndex?.total || 0
					this.items = result?.searchIndex?.items || []
					this.loading = false;
				})
			},


			remove(idx, index) {
				if(idx !== 'undefined' && index !== 'undefined' && this.items[idx] && this.items[idx].lists && this.items[idx].lists[this.domain] && this.items[idx].lists[this.domain][index]) {
					this.items[idx].lists[this.domain].splice(index, 1);
					this.save([this.items[idx]])

					if(!this.items[idx].lists[this.domain].length) {
						this.items.splice(idx, 1);
					}
					return
				}

				const modified = []

				this.items.forEach(item => {
					const litems = item.lists[this.domain].filter(litem => {
						return !litem._checked
					})

					if(item.lists[this.domain].length !== litems.length) {
						modified.push(item)
					}

					item.lists[this.domain] = litems
				})

				this.items = this.items.filter(item => {
					return item.lists[this.domain].length
				})

				this.save(modified)
			},


			save(items) {
				if(!Array.isArray(items)) {
					throw new Error('Parameter "items" in save() must be an array')
				}

				if(!items.length) {
					return
				}

				let input = ''

				input += `[\n`
				for(const item of items) {
					input += `{\n`
					input += `	id: "` + item.id + `"\n`
					input += `	lists: {\n`
					input += `		` + this.domain + `: [\n`

					for(const litem of (item.lists[this.domain] || [])) {
						input += `		{\n`
						input += `			id: "` + litem.id + `"\n`
						input += `			type: "` + litem.type + `"\n`
						input += `			config: ` + JSON.stringify(litem.config) + `\n`
						input += `			datestart: ` + (litem.datestart ? `"` + litem.datestart + `"` : 'null') + `\n`
						input += `			dateend: ` + (litem.dateend ? `"` + litem.dateend + `"` : 'null') + `\n`
						input += `			position: ` + litem.position + `\n`
						input += `			status: ` + litem.status + `\n`
						input += `			refid: "` + litem.refid + `"\n`
						input += `		}\n`
					}

					input += `		]\n`
					input += `	}\n`
					input += `}\n`
				}
				input += `]\n`

				Aimeos.query(`mutation {
					saveProducts(input: ` + input + `) {
						id
					}
				  }
				`)
			},


			title(item) {
				if(item['siteid']) {
					return 'Site ID: ' + item['siteid'] + "\n"
						+ 'Editor: ' + item['editor'] + "\n"
						+ 'Created: ' + item['ctime'] + "\n"
						+ 'Modified: ' + item['mtime'];
				}
				return ''
			},


			toggle() {
				this.items.forEach(item => {
					if(item.lists[this.domain]) {
						item.lists[this.domain].forEach(litem => {
							litem._checked = !litem._checked
						})
					}
				})
			}
		},


		watch: {
			limit() {
				this.fetch();
			},


			offset() {
				this.fetch();
			}
		}
	}
};


document.addEventListener("DOMContentLoaded", function() {
	Aimeos.ProductList.init();
});
