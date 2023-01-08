/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


Vue.component('catalog-tree', {
	template: `
		<div class="tree-menu-filter">
			<div class="filter input-group tree-toolbar">
				<button v-if="remove" v-on:click.stop="$emit('remove')" type="button" class="btn btn-secondary fa act-delete" tabindex="1"></button>
				<input class="form-control" v-bind:placeholder="placeholder" v-on:input="$emit('filter', $event.target.value)" />
				<button v-if="create" v-on:click.stop="$emit('create')" type="button" class="btn btn-primary fa act-add" tabindex="1"></button>
			</div>
		</div>
	`,

	props: {
		placeholder: {type: String, default: 'Find category'},
		current: {type: String, default: ''},
		create: {type: Boolean, default: false},
		remove: {type: Boolean, default: false},
	}
});
