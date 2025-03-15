/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2025
 */

Aimeos.components['site-tree-items'] = {
	template: `
		<ul v-if="Object.keys(items).length" class="tree-menu">
			<li v-for="(item, id) in items" v-bind:key="id" v-bind:class="{active: current == id}">
				<a v-bind:href="url.replace('_code_', item['code']).replace('_id_', id)">
					<i v-bind:class="'status-' + item['status']"></i>
					<span class="name">{{ item['label'] }}</span>
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
			this.$emit('loading', true);

			const self = this;
			let filter = {'==': {'locale.site.parentid': self.parent}};

			if(this.filter) {
				filter = {'&&': [
					filter,
					{'||': [
						{'=~': {'locale.site.siteid': this.filter}},
						{'=~': {'locale.site.code': this.filter}},
						{'=~': {'locale.site.label': this.filter}}
					]}
				]};
			}

			Aimeos.graphql(`query {
				searchLocaleSites(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, sort: ["sort:locale.site:position"]) {
					items {
						id
						siteid
						code
						label
						status
					}
					total
				}
			}`).then(result => {
				for(const entry of (result?.searchLocaleSites?.items || [])) {
					self.items[entry['id']] = entry;
				}

				self.total = result?.searchLocaleSites?.total || (result?.searchLocaleSites?.items || []).length;
			}).then(function() {
				self.$emit('loading', false);
			})
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
