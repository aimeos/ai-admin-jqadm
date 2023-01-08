/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


Vue.component('site-tree', {
	template: `
		<div class="tree-menu-filter">
			<div class="filter">
				<input class="form-control" v-bind:placeholder="placeholder" v-model:value="filter" />
			</div>
			<site-tree-items v-bind:url="url" v-bind:promise="promise"
				v-bind:filter="filter" v-bind:current="current">
			</site-tree-items>
		</div>
	`,

	props: {
		url: {type: String, required: true},
		promise: {type: Object, required: true },
		placeholder: {type: String, default: 'Find site'},
		current: {type: String, default: ''},
	},

	data() {
		return {
			filter: ''
		}
	},

	inject: ['Aimeos']
});
