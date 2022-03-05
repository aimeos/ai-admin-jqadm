/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2022
 */


Vue.component('taxrates', {
	template: '\
		<div> \
			<table> \
				<tr v-for="(val, type) in taxrates" v-bind:key="type"> \
					<td class="input-group"> \
						<input class="form-control item-taxrate" required="required" step="0.01" type="number" v-bind:placeholder="placeholder" \
							v-bind:readonly="readonly" v-bind:tabindex="tabindex" v-bind:name="name + \'[\' + type + \']\'" \
							v-bind:value="val" v-on:input="update(type, $event.target.value)" /> \
						<div v-if="type != 0" class="input-group-append"><span class="input-group-text">{{ type.toUpperCase() }}</span></div> \
					</td> \
					<td v-if="!readonly && types.length" class="actions"> \
						<div v-if="!readonly && type == 0 && types.length" class="dropdown"> \
							<button class="btn act-add fa" v-bind:tabindex="tabindex" \
								type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> \
							</button> \
							<div class="dropdown-menu dropdown-menu-right"> \
								<a v-for="(rate, code) in types" class="dropdown-item" href="#" v-on:click="add(code, rate)">{{ code.toUpperCase() }}</a> \
							</div> \
						</div> \
						<div v-if="!readonly && type != 0" class="btn act-delete fa" v-on:click="remove(type)"></div> \
					</td> \
				</tr> \
			</table> \
		</div> \
	',
	props: ['name', 'placeholder', 'readonly', 'tabindex', 'taxrates', 'types'],

	created: function() {
		delete this.types[''];
	},

	methods: {
		add: function(type, val) {
			this.$set(this.taxrates, type, val);
		},

		remove: function(type) {
			this.$delete(this.taxrates, type);
		},

		update: function(type, val) {
			this.$set(this.taxrates, type, val);
		}
	}
});
