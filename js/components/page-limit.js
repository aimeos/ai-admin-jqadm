/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


Aimeos.components['page-limit'] = {
	template: `
		<div class="page-limit btn-group dropup" role="group">
			<button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
				v-bind:tabindex="tabindex" aria-haspopup="true" aria-expanded="false">
				{{ modelValue }} <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li class="dropdown-item">
					<a class="dropdown-link" v-on:click.prevent="$emit('update:modelValue', 25)" href="#" v-bind:tabindex="tabindex">25</a>
				</li>
				<li class="dropdown-item">
					<a class="dropdown-link" v-on:click.prevent="$emit('update:modelValue', 50)" href="#" v-bind:tabindex="tabindex">50</a>
				</li>
				<li class="dropdown-item">
					<a class="dropdown-link" v-on:click.prevent="$emit('update:modelValue', 100)" href="#" v-bind:tabindex="tabindex">100</a>
				</li>
				<li class="dropdown-item">
					<a class="dropdown-link" v-on:click.prevent="$emit('update:modelValue', 250)" href="#" v-bind:tabindex="tabindex">250</a>
				</li>
			</ul>
		</div>
	`,
	emits: ['update:modelValue'],
	props: {
		modelValue: {type: Number, required: true},
		tabindex: {type: String, default: '1'}
	}
};
