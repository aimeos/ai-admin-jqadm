/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


Vue.component('select-component', {
	template: '\
		<select v-on:input="$emit(\'input\', $event.target.value)" v-bind:value="value"> \
			<option v-if="text" value="">{{ text }}</option> \
			<option v-if="value && !items[value]" v-bind:value="value">{{ value }}</option> \
			<option v-if="all" v-bind:value="null" v-bind:selected="value === null">{{ all }}</option> \
			<option v-for="(label, key) in items" v-bind:key="key" v-bind:value="key"> \
				{{ label || key }} \
			</option> \
		</select> \
	',
	props: {
		'all': {type: String, default: ''},
		'items': {type: Object, required: true},
		'text': {type: String, default: ''},
		'value': {required: true}
	}
});
