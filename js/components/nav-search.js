/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


Vue.component('nav-search', {
	template: '#nav-search',
	props: {
		'attributes': {type: Object, required: true},
		'filter': {type: Object, required: true},
		'name': {type: String, required: true},
		'operators': {type: Object, required: true},
		'show': {type: Boolean, default: false},
		'url': {type: String, required: true},
	},
	data() {
		return {
			'key': null,
			'op': null,
			'ops': {
				'string': ['=~', '~=', '==', '!='],
				'integer': ['==', '!=', '>', '<', '>=', '<='],
				'datetime': ['>', '<', '>=', '<=', '==', '!='],
				'date': ['>', '<', '>=', '<=', '==', '!='],
				'float': ['>', '<', '>=', '<=', '==', '!='],
				'boolean': ['==', '!=']
			},
			'type': 'text',
		}
	},
	beforeMount() {
		this.key = this.filter['key'] && this.filter['key'][0] || Object.keys(this.attributes).shift();
		this.op = this.filter['op'] && this.filter['op'][0] || null;
	},
	computed: {
		oplist() {
			const type = this.key && this.attributes[this.key] && this.attributes[this.key]['type'] || 'string';
			let entries = {};

			(this.ops[type] || []).forEach( function(val) {
				entries[val] = this.operators[val] || '';
			}, this);

			return entries;
		}
	},
	watch: {
		key(key) {
			const type = this.attributes[key] && this.attributes[key]['type'] || 'string';

			switch(type) {
				case 'boolean':
				case 'integer':
				case 'float':
					this.type = 'number'; break;
				case 'date':
					this.type = 'date'; break;
				case 'datetime':
					this.type = 'datetime-local'; break;
				default:
					this.type = 'text'; break;
			}

			if((this.ops[type] || []).indexOf(this.op) === -1) {
				[this.op] = this.ops[type];
			}
		}
	}
});
