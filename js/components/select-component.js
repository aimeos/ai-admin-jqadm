/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


Aimeos.components['select-component'] = {
	template: `
		<select @change="$emit('update:modelValue', $event.target.value)">
			<option v-if="text" value="">{{ text }}</option>
			<option v-if="modelValue && !items[modelValue]" v-bind:value="modelValue" selected>{{ modelValue }}</option>
			<option v-if="all" value="" v-bind:selected="modelValue === null">{{ all }}</option>
			<option v-for="(label, key) in items" v-bind:key="key" v-bind:value="key" v-bind:selected="String(modelValue) === String(key)">
				{{ label || key }}
			</option>
		</select>
	`,
	emits: ['update:modelValue'],
	props: {
		'all': {type: String, default: ''},
		'text': {type: String, default: ''},
		'items': {type: Object, required: true},
		'modelValue': {required: true},
	}
};
