/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */

Aimeos.components['site-tree-items'] = {
	template: `
		<ul v-if="Object.keys(items).length" class="tree-menu">
			<li v-for="(item, id) in items" v-bind:key="id" v-bind:class="{active: current == id}">
				<a v-bind:href="url.replace('_code_', item['locale.site.code']).replace('_id_', id)">
					<i v-bind:class="'status-' + item['locale.site.status']"></i>
					<span class="name">{{ item['locale.site.label'] }}</span>
				</a><!--
				--><span v-if="isTogglable(item)"
					v-on:click.stop="toggle(id)" class="icon"
					v-bind:class="{
						'icon-open': !item.isOpen,
						'icon-close': item.isOpen,
						'icon-loading fa-pulse': item.isLoading
					}">
				</span>
				<site-tree-items v-if="isAvailable(item) && item.isOpen"
					v-on:loading="loading(id, $event)"
					v-bind:initial="item.children || {}"
					v-bind:promise="promise"
					v-bind:current="current"
					v-bind:level="level + 1"
					v-bind:filter="filter"
					v-bind:parent="id"
					v-bind:tree="tree"
					v-bind:url="url"
					v-bind="$attrs">
				</site-tree-items>
			</li>
			<li v-if="more()" class="more" v-on:click="next()"></li>
		</ul>
	`,

	emits: ['loading', 'select'],

	props: {
		url: {type: String, required: true},
		tree: {type: Boolean, default: false},
		promise: {type: Object, required: true},
		initial: {type: Object, default: () => ({})},
		current: {type: String, default: ''},
		parent: {type: String, default: '0'},
		filter: {type: String, default: ''},
		level: {type: Number, default: 0}
	},

	data() {
		return {
			items: {},
			limit: 25,
			offset: 0,
			total: null,
			timer: null
		}
	},

	mounted() {
		this.fetch = this.debounce(this.fetch, 300);

		if(this.filter || Object.keys(this.initial).length) {
			this.items = this.initial;

			for(const id in this.initial) {
				if(id == this.current) {
					this.$emit('select', this.initial[id]);
				}
			}
		} else {
			this.fetch();
		}
	},

	methods: {
		debounce(func, delay) {
			return function() {
				const context = this;
				const args = arguments;

				clearTimeout(this.timer);
				this.timer = setTimeout(() => func.apply(context, args), delay);
			};
		},

		fetch() {
			const self = this;
			self.$emit('loading', true);

			this.promise.then(function(response) {
				if(!response.meta.resources['locale/site']) {
					return;
				}

				const param = {filter: {'==': {'locale.site.parentid': self.parent}}};

				if(self.filter) {
					param['filter'] = {'&&': [
						param['filter'],
						{'||': [
							{'=~': {'locale.site.siteid': self.filter}},
							{'=~': {'locale.site.code': self.filter}},
							{'=~': {'locale.site.label': self.filter}}
						]}
					]};
				}

				param['fields'] = {'locale/site': 'locale.site.siteid,locale.site.code,locale.site.label,locale.site.status,locale.site.hasChildren'};
				param['page'] = {'offset': self.offset, 'limit': self.limit};
				param['sort'] = 'locale.site.position';

				let params = {};
				let url = response.meta.resources['locale/site'] + (response.meta.resources['locale/site'].includes('?') ? '&' : '?');

				if(response.meta.prefix && response.meta.prefix) {
					params[response.meta.prefix] = param;
				} else {
					params = param;
				}

				fetch(url + serialize(params)).then(function(response) {
					if(!response.ok) {
						throw new Error(response.statusText);
					}
					return response.json();
				}).then(function(response) {
					for(const entry of (response.data || [])) {
						self.items[entry['id']] = entry['attributes'];
					}

					self.total = response.meta && response.meta.total || 0;

				}).then(function() {
					self.$emit('loading', false);
				});
			});
		},

		isAvailable(item) {
			return this.tree && (item.isOpen || Object.keys(item.children || {}).length);
		},

		isTogglable(item) {
			return this.tree && (item['locale.site.hasChildren'] || Object.keys(item.children || {}).length);
		},

		loading(id, val) {
			this.items[id]['isLoading'] = val;
		},

		more() {
			return (!this.filter || !Object.keys(this.initial).length) && (this.total === null || this.offset + this.limit < this.total);
		},

		next() {
			if(this.total) {
				this.offset += this.limit;
			}
			this.fetch();
		},

		toggle(id) {
			this.items[id]['isOpen'] = !this.items[id].isOpen;
		}
	},

	watch: {
		initial: {
			deep: true,
			handler: function(items) {
				if(Object.keys(this.initial).length) {
					this.items = items;
				}
			}
		},

		filter: {
			deep: true,
			handler: function(val) {
				if(val && this.level === 0) {
					this.items = {};
					this.fetch();
				}
			}
		}
	}
};
