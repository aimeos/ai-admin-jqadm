/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */



Aimeos.Coupon = {

	init() {
		Aimeos.components['coupon'] = new Vue({
			el: document.querySelector('.item-coupon #basic'),
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
		const node = document.querySelector('.item-coupon .coupon-code-list');

		if(node) {
			Aimeos.components['coupon.code'] = new Vue({
				'el': node,
				'mixins': [Aimeos.Coupon.Code.mixins]
			});
		}

		Aimeos.lazy('.item-coupon .coupon-code-list', function() {
			Aimeos.components['coupon.code'] && Aimeos.components['coupon.code'].reset();
		});
	},


	mixins: {
		'data'() {
			return {
				'parentid': null,
				'siteid': '',
				'items': [],
				'fields': [],
				'filter': {},
				'offset': 0,
				'limit': 25,
				'total': 0,
				'order': '',
				'colselect': false,
				'checked': false,
				'loading': true
			}
		},


		beforeMount() {
			this.Aimeos = Aimeos;
			try {
				if(!this.$el.dataset) {
					throw 'Missing "data" attributes';
				}
				if(!this.$el.dataset.siteid) {
					throw 'Missing "data-siteid" attribute';
				}
				if(!this.$el.dataset.parentid) {
					throw 'Missing "data-parentid" attribute';
				}

				this.siteid = this.$el.dataset.siteid;
				this.parentid = this.$el.dataset.parentid;
				this.order = 'coupon.code.id';

				const fieldkey = 'aimeos/jqadm/couponcode/fields';
				this.fields = this.columns(this.$el.dataset.fields || [], fieldkey);
			} catch(e) {
				console.error( '[Aimeos] Init coupon code list failed: ' + e);
			}
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
					this.$set(this.items[idx], 'edit', true);
				}
			},


			find(ev, key, op) {
				const value = ev.target ? ev.target.value : ev;
				if(value) {
					const expr = {};
					expr[op || '=='] = {};
					expr[op || '==']['coupon.code.' + key] = value;
					this.$set(this.filter, 'coupon.code.' + key, expr);
				} else {
					this.$delete(this.filter, 'coupon.code.' + key);
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
						this.$delete(this.items, map[id]);
					}
					this.loading = false;
				});
			},


			reset() {
				Object.assign(this.$data, {filter: {'base': {'==': {'coupon.code.parentid': this.parentid}}}});
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
				this.fields = fields;

				if(window.sessionStorage) {
					window.sessionStorage.setItem(
						'aimeos/jqadm/couponcode/fields',
						JSON.stringify(this.fields)
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
					this.$set(item, 'checked', this.checked);
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



$(function() {
	Aimeos.Coupon.init();
});
