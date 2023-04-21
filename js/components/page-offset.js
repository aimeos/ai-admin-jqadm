/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


Vue.component('page-offset', {
	template: '\
		<ul class="page-offset pagination"> \
			<li v-bind:class="{disabled: first === null}" class="page-item"> \
				<button v-on:click.prevent="$emit(\'input\', first)" class="page-link" v-bind:tabindex="tabindex" aria-label="First"> \
					<span class="fa icon-first" aria-hidden="true"></span> \
				</button> \
			</li><li v-bind:class="{disabled: prev === null}" class="page-item"> \
				<button v-on:click.prevent="$emit(\'input\', prev)" class="page-link" v-bind:tabindex="tabindex" aria-label="Previous"> \
					<span class="fa icon-prev" aria-hidden="true"></span> \
				</button> \
			</li><li class="page-item disabled"> \
				<button class="page-link" v-bind:tabindex="tabindex""> \
					{{ string }} \
				</button> \
			</li><li v-bind:class="{disabled: next === null}" class="page-item"> \
				<button v-on:click.prevent="$emit(\'input\', next)" class="page-link" v-bind:tabindex="tabindex" aria-label="Next"> \
					<span class="fa icon-next" aria-hidden="true"></span> \
				</button> \
			</li><li v-bind:class="{disabled: last === null}" class="page-item"> \
				<button v-on:click.prevent="$emit(\'input\', last)" class="page-link" v-bind:tabindex="tabindex" aria-label="Last"> \
					<span class="fa icon-last" aria-hidden="true"></span> \
				</button> \
			</li> \
		</ul> \
	',
	props: {
		'limit': {type: Number, required: true},
		'total': {type: Number, required: true},
		'value': {type: Number, required: true},
		'tabindex': {type: String, default: '1'},
		'text': {type: String, default: '%1$d / %2$d'}
	},

	computed: {
		first() {
			return this.value > 0 ? 0 : null;
		},
		prev() {
			return this.value - this.limit >= 0 ? this.value - this.limit : null;
		},
		next() {
			return this.value + this.limit < this.total ? this.value + this.limit : null;
		},
		last() {
			return Math.floor((this.total - 1) / this.limit) * this.limit > this.value ? Math.floor((this.total - 1) / this.limit ) * this.limit : null;
		},
		current() {
			return Math.floor( this.value / this.limit ) + 1;
		},
		pages() {
			return this.total != 0 ? Math.ceil(this.total / this.limit) : 1;
		},
		string() {
			return sprintf(this.text, this.current, this.pages);
		}
	}
});
