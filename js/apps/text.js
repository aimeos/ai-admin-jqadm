/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
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
					openai: {type: String, default: '{}'},
					deepl: {type: String, default: '{}'},
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

				const config = JSON.parse(this.openai || '{}');

				if(!config['key']) {
					alert('Add the OpenAI API key in the Setting > API panel first');
					return;
				}

				if(!(this.items[idx]['text.content'] || '').trim().length) {
					this.items[idx]['text.content'] = this.prompt;
					return;
				}

				const self = this;
				const params = {
					model: config['model'] || 'gpt-4o-mini',
					messages: [{
						role: 'system',
						content: config['context'] || 'You are a professional writer for product texts and blog articles and create descriptions and articles in the language of the input without markup'
					}, {
						role: 'user',
						content: this.items[idx]['text.content']
					}]
				};

				this.items[idx]['loading'] = true;

				await fetch(config['url'] || 'https://api.openai.com/v1/chat/completions', {
					body: JSON.stringify(params),
					headers: {
						'Content-Type': 'application/json',
						'Authorization': 'Bearer ' + config['key']
					},
					method: 'POST'
				}).then(response => {
					if(!response.ok) {
						throw new Error(`${response.status}: ${response.statusText}`)
					}
					return response.json();
				}).then(data => {
					self.items[idx]['text.content'] = (data['choices'] && data['choices'][0] && data['choices'][0]['message'] && data['choices'][0]['message']['content'] || '').trim();
				}).finally(() => {
					this.items[idx]['loading'] = false;
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
						const tmp = document.createElement("span");
						tmp.innerHTML = this.items[idx]['text.content'];
						label += ' : ' + (tmp.innerText || tmp.textContent || "").substr(0, 40);
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

				const config = JSON.parse(this.deepl || '{}');

				if(!config['key']) {
					alert('Add the DeepL API key in the Setting > API panel first');
					return;
				}

				const self = this;
				const url = (config['url'] || 'https://api-free.deepl.com/v2') + '/translate?auth_key=' + encodeURIComponent(config['key']);
				let body = 'text=' + encodeURIComponent([this.items[idx]['text.content']]) + '&target_lang=' + langid.toUpperCase().replace(/_/g, '-');

				if(this.items[idx]['text.languageid']) {
					body += '&source_lang=' + this.items[idx]['text.languageid'].toUpperCase().replace(/_/g, '-');
				}

				await fetch(url, {
					method: "POST",
					body: body,
					headers: {"Content-Type": "application/x-www-form-urlencoded"},
				}).then(response => {
					if(!response.ok) {
						let msg = '';
						switch(response.status) {
							case 200: break;
							case 400: msg = 'Bad request: ' + response.statusText; break;
							case 403: msg = 'Invalid DeepL API token'; break;
							case 413: msg = 'The text size exceeds the limit'; break;
							case 429: msg = 'Too many requests. Please wait and resend your request.'; break;
							case 456: msg = 'Quota exceeded. The character limit has been reached.'; break;
							case 503: msg = 'Resource currently unavailable. Try again later.'; break;
							default: msg = 'Unexpected response code: ' + response.status + ' => ' + response.statusText;
						}
						throw new Error(msg);
					}
					return response.json();
				}).then(data => {
					self.add({
						'text.content': data['translations'] && data['translations'][0] && data['translations'][0]['text'] || '',
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
