/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */



Aimeos.Coupon = {

	init() {
		const node = document.querySelector('.item-coupon #basic')

		if(node) {
			Aimeos.apps['coupon'] = Aimeos.app({
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

		Aimeos.Coupon.Code.init();
	},


	mixins: {
		methods: {
			can(action) {
				return Aimeos.can(action, this.item['coupon.siteid'] || null, this.siteid)
			},


			config(provider) {
				if(!provider) return []
				if(this.cache[provider]) return this.cache[provider]

				return this.cache[provider] = Aimeos.query(`query {
					getCouponConfig(provider: ` + JSON.stringify(provider) + `) {
						code
						label
						type
						default
						required
					}
				}`).then(result => {
					return (result?.getCouponConfig || []).map(entry => {
						entry.default = JSON.parse(entry.default)
						entry.key = entry.code
						return entry
					})
				})
			},


			decorate(name) {
				if(!(new String(this.item['coupon.provider'])).includes(name)) {
					this.item['coupon.provider'] = this.item['coupon.provider'] + ',' + name
				}
			},
		}
	}
};



Aimeos.Coupon.Code = {

	init() {
		const node = document.querySelector('.item-coupon .coupon-code-list')

		if(node) {
			Aimeos.apps['coupon'] = Aimeos.app({
				'mixins': [Aimeos.Coupon.Code.mixins]
			}, {...node.dataset || {}}).mount(node);
		}

		Aimeos.lazy('.item-coupon .coupon-code-list', function() {
			Aimeos.apps['coupon.code'] && Aimeos.apps['coupon.code'].reset();
		});
	},


	mixins: {
		props: {
			parentid: {type: String, required: true},
			siteid: {type: String, required: true},
			fields: {type: String, required: true},
		},
		data() {
			return {
				items: [],
				fieldlist: [],
				filter: {},
				offset: 0,
				limit: 25,
				total: 0,
				order: '',
				colselect: false,
				checked: false,
				loading: true
			}
		},


		beforeMount() {
			this.Aimeos = Aimeos;
			this.order = 'coupon.code.id';
			this.filter['coupon.code.parentid'] = {'==': {'coupon.code.parentid': this.parentid}};

			const fieldkey = 'aimeos/jqadm/couponcode/fields';
			this.fieldlist = this.columns(this.fields || [], fieldkey);

			this.fetch();
		},


		methods: {
			add() {
				const obj = {};

				obj['id'] = null;
				obj['siteid'] = this.siteid;
				obj['code'] = '';
				obj['count'] = 1;
				obj['datestart'] = null;
				obj['dateend'] = null;
				obj['ref'] = null;
				obj['edit'] = true;

				this.items.unshift(obj);
			},


			can(action, idx) {
				return Aimeos.can(action, this.items[idx]['siteid'] || null, this.siteid)
			},


			columns(json, key) {
				let list = [];
				try {
					if(window.sessionStorage) {
						list = JSON.parse(window.sessionStorage.getItem(key)) || [];
					}
					if(!list.length) {
						list = JSON.parse(json);
					}
				} catch(e) {
					console.error('[Aimeos] Failed to get list of columns: ' + e);
				}
				return list;
			},


			css(key) {
				return 'coupon-code-' + key;
			},


			edit(idx) {
				if(this.can('change', idx)) {
					this.items[idx]['edit'] = true;
				}
			},


			find(ev, key, op) {
				const value = ev.target ? ev.target.value : ev;
				if(value) {
					const expr = {};
					expr[op || '=='] = {};
					expr[op || '==']['coupon.code.' + key] = value;
					this.filter['coupon.code.' + key] = expr;
				} else {
					delete this.filter['coupon.code.' + key];
				}

				return this;
			},


			fetch() {
				const filter = {'&&': Object.values(this.filter)};
				this.loading = true;

				return Aimeos.query(`query {
					searchCouponCodes(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, sort: ` + JSON.stringify([this.order]) + `, offset: ` + this.offset + `, limit: ` + this.limit + `) {
						items {
							id
							siteid
							parentid
							code
							count
							dateend
							datestart
							ref
							mtime
							ctime
							editor
						}
						total
					}
				  }
				`).then((result) => {
					this.total = result?.searchCouponCodes?.total || 0;
					this.items = result?.searchCouponCodes?.items || [];
					this.loading = false;
				});
			},


			remove(idx) {
				const map = {};

				this.loading = true;
				this.checked = false;

				if(idx !== undefined) {
					map[this.items[idx]['id']] = idx;
				} else {
					for(const pos in this.items) {
						if(this.items[pos].checked) {
							map[this.items[pos]['id']] = pos;
						}
					}
				}

				return Aimeos.query(`mutation {
					deleteCouponCodes(id: ` + JSON.stringify(Object.keys(map)) + `)
				  }
				`).then((result) => {
					for(const id of (result?.deleteCouponCodes || [])) {
						this.items.splice(map[id], 1)
					}
					this.loading = false;
				});
			},


			reset() {
				this.filter = {'base': {'==': {'coupon.code.parentid': this.parentid}}};
				return this.fetch();
			},


			sort(key) {
				this.order = this.order === 'coupon.code.' + key ? '-' + 'coupon.code.' + key : 'coupon.code.' + key;
				return this.fetch();
			},


			sortclass(key) {
				return this.order === 'coupon.code.' + key ? 'sort-desc' : (this.order === '-' + 'coupon.code.' + key ? 'sort-asc' : '');
			},


			toggle(fields) {
				this.fieldlist = fields;

				if(window.sessionStorage) {
					window.sessionStorage.setItem(
						'aimeos/jqadm/couponcode/fields',
						JSON.stringify(this.fieldlist)
					);
				}

				return this.fetch();
			},


			value(key) {
				const op = Object.keys(this.filter['coupon.code.' + key] || {}).pop();
				return this.filter['coupon.code.' + key] && this.filter['coupon.code.' + key][op]['coupon.code.' + key] || '';
			}
		},


		watch: {
			checked() {
				for(let item of this.items) {
					item['checked'] = this.checked;
				}
			},


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
	Aimeos.Coupon.init();
});
