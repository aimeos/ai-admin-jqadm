/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2025
 */


Aimeos.Text = {

	init() {
		const node = document.querySelector('#item-text-group');

		if(node) {
			Aimeos.apps['text'] = Aimeos.app({
				props: {
					data: {type: String, default: '[]'},
					domain: {type: String, default: ''},
					siteid: {type: String, default: ''},
					prompt: {type: String, default: 'Please enter what kind of text you want to generate'},
				},
				data() {
					return {
						items: [],
					}
				},
				beforeMount() {
					this.Aimeos = Aimeos;
					this.CKEditor = ClassicEditor;
					this.items = JSON.parse(this.data);

					if(this.items[0]) {
						this.items[0]['_show'] = true;
					}
				},
				mixins: [this.mixins]
			}, {...node.dataset || {}}).mount(node);
		}
	},

	mixins: {
		methods: {
			active(idx) {
				return this.items[idx] && this.items[idx]['text.status'] > 0;
			},


			add(data = {}) {
				const entry = {};

				entry[this.domain + '.lists.id'] = null;
				entry[this.domain + '.lists.type'] = 'default';
				entry[this.domain + '.lists.siteid'] = this.siteid;
				entry[this.domain + '.lists.datestart'] = null;
				entry[this.domain + '.lists.dateend'] = null;

				entry['text.id'] = null;
				entry['text.type'] = null;
				entry['text.languageid'] = '';
				entry['text.siteid'] = this.siteid;
				entry['text.content'] = '';
				entry['text.label'] = '';
				entry['text.status'] = 1;

				entry['property'] = [];
				entry['config'] = [];
				entry['_show'] = true;

				this.items.push(Object.assign(entry, data));
			},


			can(action, idx) {
				return Aimeos.can(action, this.items[idx][this.domain + '.lists.siteid'] || null, this.siteid)
			},


			async generate(idx) {

				if(!this.items[idx]) {
					return;
				}

				if(!(this.items[idx]['text.content'] || '').trim().length) {
					this.items[idx]['text.content'] = this.prompt;
					return;
				}

				this.items[idx]['_loading'] = true;

				await Aimeos.graphql(`mutation($prompt: String!) {
					write(prompt: $prompt)
				}`, {
					prompt: this.items[idx]['text.content']
				}).then(result => {
					this.items[idx]['text.content'] = (result.write || '').trim();
				}).finally(() => {
					this.items[idx]['_loading'] = false;
				}).catch((error) => {
					alert(error);
				});
			},


			label(idx) {
				let label = '';

				if(this.items[idx]) {
					label += (this.items[idx]['text.type'] ? this.items[idx]['text.type'] : '');
					label += (this.items[idx]['text.languageid'] ? ' (' + this.items[idx]['text.languageid'].toUpperCase() + ')' : '');

					if(this.items[idx]['text.label']) {
						label += ' : ' + this.items[idx]['text.label'].substr(0, 40);
					} else if(this.items[idx]['text.content']) {
						const doc = new DOMParser().parseFromString(this.items[idx]['text.content'], 'text/html');
						label += ' : ' + (doc.body.textContent || "").substr(0, 40);
					}
				}

				return label;
			},


			remove(idx) {
				if(this.items[idx]) {
					this.items.splice(idx, 1);
				}
			},


			toggle(what, idx) {
				if(this.items[idx]) {
					this.items[idx][what] = (!this.items[idx][what] ? true : false);
				}
			},


			async translate(idx, langid) {

				if(!this.items[idx]) {
					return;
				}

				await Aimeos.graphql(`mutation($texts: [String!]!, $to: String!, $from: String) {
					translate(texts: $texts, to: $to, from: $from)
				}`, {
					texts: [this.items[idx]['text.content']],
					to: langid.toUpperCase().replace(/_/g, '-'),
					from: this.items[idx]['text.languageid']?.toUpperCase().replace(/_/g, '-') || null
				}).then(result => {
					this.add({
						'text.content': result.translate?.[0] || '',
						'text.languageid': langid.toLowerCase().replace(/-/g, '_'),
						'text.type': this.items[idx]['text.type'] || '',
						'text.label': (this.items[idx]['text.label'] || '') + ' (' + langid + ')'
					});
				}).catch(error => {
					alert(error);
				});
			},


			update(element, ev, editor) {
				const text = editor.getData();
				if(text.indexOf('<p>', 3) === -1 && text.lastIndexOf('</p>', 4) === -1) {
					element['text.content'] = text.replace(/^<p>/, '').replace(/<\/p>$/, '')
				} else {
					element['text.content'] = text
				}
			}
		}
	}
};


document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Text.init();
});
