/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


Aimeos.components['page-offset'] = {
	template: `
		<ul class="page-offset pagination">
			<li v-bind:class="{disabled: first === null}" class="page-item">
				<a v-on:click.prevent="$emit('input', first)" class="page-link" v-bind:tabindex="tabindex" title="First" href="#">
					<span class="icon icon-first" aria-hidden="true"></span>
				</a>
			</li><li v-bind:class="{disabled: prev === null}" class="page-item">
				<a v-on:click.prevent="$emit('input', prev)" class="page-link" v-bind:tabindex="tabindex" title="Previous" href="#">
					<span class="icon icon-prev" aria-hidden="true"></span>
				</a>
			</li><li class="page-item disabled">
				<a class="page-link" v-bind:tabindex="tabindex">
					<span>{{ string }}</span>
				</a>
			</li><li v-bind:class="{disabled: next === null}" class="page-item">
				<a v-on:click.prevent="$emit('input', next)" class="page-link" v-bind:tabindex="tabindex" title="Next" href="#">
					<span class="icon icon-next" aria-hidden="true"></span>
				</a>
			</li><li v-bind:class="{disabled: last === null}" class="page-item">
				<a v-on:click.prevent="$emit('input', last)" class="page-link" v-bind:tabindex="tabindex" title="Last" href="#">
					<span class="icon icon-last" aria-hidden="true"></span>
				</a>
			</li>
		</ul>
	`,
	emits: ['input'],
	props: {
		'modelValue': {type: Number, required: true},
		'limit': {type: Number, required: true},
		'total': {type: Number, required: true},
		'tabindex': {type: String, default: '1'},
		'text': {type: String, default: '%1$d / %2$d'},
	},

	computed: {
		first() {
			return this.modelValue > 0 ? 0 : null;
		},
		prev() {
			return this.modelValue - this.limit >= 0 ? this.modelValue - this.limit : null;
		},
		next() {
			return this.modelValue + this.limit < this.total ? this.modelValue + this.limit : null;
		},
		last() {
			return Math.floor((this.total - 1) / this.limit) * this.limit > this.modelValue ? Math.floor((this.total - 1) / this.limit ) * this.limit : null;
		},
		current() {
			return Math.floor( this.modelValue / this.limit ) + 1;
		},
		pages() {
			return this.total != 0 ? Math.ceil(this.total / this.limit) : 1;
		},
		string() {
			return sprintf(this.text, this.current, this.pages);
		}
	}
};
