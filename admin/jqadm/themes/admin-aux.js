/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



$(function() {

	Aimeos.Address.init();
	Aimeos.Media.init();
	Aimeos.Price.init();
	Aimeos.Text.init();
});



Aimeos.Address = {

	instance: null,

	init: function() {

		this.address = new Vue({
			'el': '#item-address-group',
			'data': {
				'advanced': [],
				'items': $("#item-address-group").data("items"),
				'keys': $("#item-address-group").data("keys"),
				'siteid': $("#item-address-group").data("siteid"),
				'domain': $("#item-address-group").data("domain")
			},
			'mixins': [this.mixins]
		});
	},

	mixins: {
		methods: {

			checkSite : function(key, idx) {
				return this.items[idx][key] != this.siteid;
			},


			addItem : function(prefix, data) {

				var idx = this.items.length;

				if(!this.items[idx]) {
					this.$set(this.items, idx, {});
				}

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items, key, data && data[key] || '');
				}

				this.$set(this.items[idx], (prefix || this.domain + '.address.') + 'siteid', this.siteid);
			},


			duplicateItem : function(idx) {

				var len = this.items.length;

				if(!this.items[len]) {
					this.$set(this.items, len, {});
				}

				for(key in this.keys) {
					key = this.keys[key]; this.items[len][key] = this.items[idx][key];
				}
			},


			removeItem : function(idx) {
				this.items.splice(idx, 1);
			},


			getCountries : function() {
				return Aimeos.getCountries;
			},


			getCss : function(idx, prefix) {
				return ( idx !== 0 && this.items[idx] && this.items[idx][prefix + 'id'] ? 'collapsed' : 'show' );
			},


			getLabel : function(idx, prefix) {
				var label = '', addr = '';

				label += (this.items[idx][prefix + 'firstname'] ? this.items[idx][prefix + 'firstname'] + ' ' : '');
				label += (this.items[idx][prefix + 'lastname'] ? this.items[idx][prefix + 'lastname'] : '');

				addr += (this.items[idx][prefix + 'postal'] ? ' ' + this.items[idx][prefix + 'postal'] : '');
				addr += (this.items[idx][prefix + 'city'] ? ' ' + this.items[idx][prefix + 'city'] : '');

				if(addr && label) {
					return label + ' -' + addr;
				}

				return label + ' ' + addr;
			}
		}
	}
};



Aimeos.Media = {

	init: function() {

		Aimeos.components['media'] = new Vue({
			el: '#item-media-group',
			data: {
				items: [],
				siteid: null,
				domain: null
			},
			mounted: function() {
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
			add: function() {
				let entry = {};

				entry[this.domain + '.lists.id'] = null;
				entry[this.domain + '.lists.type'] = 'default';
				entry[this.domain + '.lists.siteid'] = this.siteid;
				entry[this.domain + '.lists.datestart'] = null;
				entry[this.domain + '.lists.dateend'] = null;

				entry['media.id'] = null;
				entry['media.label'] = null;
				entry['media.type'] = 'default';
				entry['media.siteid'] = this.siteid;
				entry['media.languageid'] = null;
				entry['media.preview'] = null;
				entry['media.url'] = null;
				entry['media.status'] = 1;

				entry['property'] = [];
				entry['config'] = [];
				entry['_show'] = true;
				entry['_nosort'] = true;

				this.items.push(entry);
			},


			files: function(idx, files) {

				if(!files.length) {
					return;
				}

				var cnt = sum = 0;
				$( "input:file" ).each(function() {
					for(var i=0; i<this.files.length; i++) {
						sum += this.files[i].size;
						cnt++;
					}
				});

				if($("#problem .file_uploads").data("value") != 1) {
					$("#problem .file_uploads").show();
					$("#problem").modal("show");
				}

				if(sum > $("#problem .post_max_size").data("value")) {
					$("#problem .upload_max_filesize").show();
					$("#problem").modal("show");
				}

				if(cnt > $("#problem .max_file_uploads").data("value")) {
					$("#problem .max_file_uploads").show();
					$("#problem").modal("show");
				}

				for(var i=0; i<files.length; i++) {
					if(files[i].size > $("#problem .upload_max_filesize").data("value")) {
						$("#problem .upload_max_filesize").show();
						$("#problem").modal("show");
					}
				}

				this.$set(this.items[idx], 'media.label', files[0].name);
			},


			label: function(idx) {
				var label = '';

				if(this.items[idx]) {
					label += (this.items[idx]['media.languageid'] ? this.items[idx]['media.languageid'] + ': ' : '');
					label += (this.items[idx]['media.label'] ? this.items[idx]['media.label'] : '');
					label += (this.items[idx]['media.type'] ? ' (' + this.items[idx]['media.type'] + ')' : '');
				}

				if(this.items[idx]['media.status'] < 1) {
					label = '<s>' + label + '</s>';
				}

				return label;
			},


			remove: function(idx) {
				this.items.splice(idx, 1);
			},


			toggle: function(what, idx) {
				this.$set(this.items[idx], what, (!this.items[idx][what] ? true : false));
			},

			url: function(prefix, url) {

				var str = url.substr(0, 4);
				return (str === 'http' || str === 'data' ? url : prefix + url);
			}
		}
	}
};



Aimeos.Price = {

	init: function() {

		Aimeos.components['price'] = new Vue({
			el: '#item-price-group',
			data: {
				items: [],
				siteid: null,
				domain: null
			},
			mounted: function() {
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
			add: function(data) {
				let entry = {};

				entry[this.domain + '.lists.id'] = null;
				entry[this.domain + '.lists.type'] = 'default';
				entry[this.domain + '.lists.siteid'] = this.siteid;
				entry[this.domain + '.lists.datestart'] = null;
				entry[this.domain + '.lists.dateend'] = null;

				entry['price.id'] = null;
				entry['price.label'] = null;
				entry['price.type'] = 'default';
				entry['price.siteid'] = this.siteid;
				entry['price.taxrates'] = {'': ''};
				entry['price.currencyid'] = null;
				entry['price.rebate'] = '0.00';
				entry['price.costs'] = '0.00';
				entry['price.value'] = null;
				entry['price.quantity'] = 1;
				entry['price.status'] = 1;

				entry['property'] = [];
				entry['config'] = [];
				entry['_show'] = true;
				entry['_nosort'] = true;

				this.items.push(Object.assign(entry, data));
			},


			label: function(idx) {
				var label = '';

				label += (this.items[idx]['price.quantity'] ? this.items[idx]['price.quantity'] + ' ~ ' : '');
				label += (this.items[idx]['price.value'] ? this.items[idx]['price.value'] : '');
				label += (this.items[idx]['price.costs'] ? ' + ' + this.items[idx]['price.costs'] : '');
				label += (this.items[idx]['price.currencyid'] ? ' ' + this.items[idx]['price.currencyid'] : '');
				label += (this.items[idx]['price.type'] ? ' (' + this.items[idx]['price.type'] + ')' : '');

				if(this.items[idx]['price.status'] < 1) {
					label = '<s>' + label + '</s>';
				}

				return label;
			},


			remove: function(idx) {
				this.items.splice(idx, 1);
			},


			toggle: function(what, idx) {
				this.$set(this.items[idx], what, (!this.items[idx][what] ? true : false));
			}
		}
	}
};



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
			add: function(data) {
				let entry = {};

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
				var label = '';

				label += (this.items[idx]['text.languageid'] ? this.items[idx]['text.languageid'].toUpperCase() : '');
				label += (this.items[idx]['text.type'] ? ' (' + this.items[idx]['text.type'] + ')' : '');

				if(this.items[idx]['text.label']) {
					label += ': ' + this.items[idx]['text.label'].substr(0, 40);
				} else if(this.items[idx]['text.content']) {
					var tmp = document.createElement("span");
					tmp.innerHTML = this.items[idx]['text.content'];
					label += ': ' + (tmp.innerText || tmp.textContent || "").substr(0, 40);
				}

				if(this.items[idx]['text.status'] < 1) {
					label = '<s>' + label + '</s>';
				}

				return label;
			},


			remove: function(idx) {
				this.items.splice(idx, 1);
			},


			toggle: function(what, idx) {
				this.$set(this.items[idx], what, (!this.items[idx][what] ? true : false));
			},


			translate : function(idx, langid) {

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

				var self = this;
				var data = {
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
					var msg = '';

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
