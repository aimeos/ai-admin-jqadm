/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2022
 */


$(function() {
	Aimeos.Text.init();
});


Aimeos.Text = {

	init: function() {

		Aimeos.components['text'] = new Vue({
			el: '#item-text-group',
			data: {
				items: [],
				siteid: null,
				domain: null
			},
			mounted: function() {
				this.Aimeos = Aimeos;
				this.CKEditor = ClassicEditor;
				this.items = JSON.parse(this.$el.dataset.items || '{}');
				this.siteid = this.$el.dataset.siteid;
				this.domain = this.$el.dataset.domain;

				if(this.items[0]) {
					this.$set(this.items[0], '_show', true);
				}
			},
			mixins: [this.mixins]
		});
	},

	mixins: {
		methods: {
			active: function(idx) {
				return this.items[idx] && this.items[idx]['text.status'] > 0;
			},


			add: function(data) {
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
				entry['_nosort'] = true;

				this.items.push(Object.assign(entry, data));
			},


			label: function(idx) {
				let label = '';

				if(this.items[idx]) {
					label += (this.items[idx]['text.languageid'] ? this.items[idx]['text.languageid'].toUpperCase() : '');
					label += (this.items[idx]['text.type'] ? ' (' + this.items[idx]['text.type'] + ')' : '');

					if(this.items[idx]['text.label']) {
						label += ': ' + this.items[idx]['text.label'].substr(0, 40);
					} else if(this.items[idx]['text.content']) {
						const tmp = document.createElement("span");
						tmp.innerHTML = this.items[idx]['text.content'];
						label += ': ' + (tmp.innerText || tmp.textContent || "").substr(0, 40);
					}
				}

				return label;
			},


			remove: function(idx) {
				if(this.items[idx]) {
					this.items.splice(idx, 1);
				}
			},


			toggle: function(what, idx) {
				if(this.items[idx]) {
					this.$set(this.items[idx], what, (!this.items[idx][what] ? true : false));
				}
			},


			translate : function(idx, langid) {

				if(!this.items[idx]) {
					return;
				}

				if(!this.$el.dataset.translate) {
					alert('No translation service configured');
					return;
				}

				config = JSON.parse(this.$el.dataset.translate);

				if(!config['url']) {
					alert('No translation URL for DeepL configured');
					return;
				}

				if(!config['key']) {
					alert('No translation credentials for DeepL configured');
					return;
				}

				const self = this;
				const data = {
					'auth_key': config['key'],
					'text' : this.items[idx]['text.content'],
					'target_lang' : langid.toUpperCase()
				};

				if(this.items[idx]['text.languageid']) {
					data['source_lang'] = this.items[idx]['text.languageid'].toUpperCase();
				}


				$.getJSON(config['url'] + '/translate', data).done(function(data) {
					self.add({
						'text.content': data['translations'] && data['translations'][0] && data['translations'][0]['text'] || '',
						'text.languageid': langid.toLowerCase()
					});
				}).fail(function(jqxhr, status, error) {
					let msg = '';

					switch(jqxhr.status) {
						case 200: break;
						case 400: msg = 'Bad request: ' + error; break;
						case 403: msg = 'Invalid DeepL API token'; break;
						case 413: msg = 'The text size exceeds the limit'; break;
						case 429: msg = 'Too many requests. Please wait and resend your request.'; break;
						case 456: msg = 'Quota exceeded. The character limit has been reached.'; break;
						case 503: msg = 'Resource currently unavailable. Try again later.'; break;
						default: msg = 'Unexpected response code: ' + jqxhr.status;
					}

					alert(msg);
				});
			}
		}
	}
};
