/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



$(function() {

	Aimeos.Address.init();
	Aimeos.Media.init();
	Aimeos.Price.init();
	Aimeos.Property.init();
	Aimeos.Text.init();
});



Aimeos.Address = {

	mixins : {
		'methods': {

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
	},


	init : function() {

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
	}
};



Aimeos.Media = {

	mixins : {
		'methods': {

			checkSite : function(key, idx) {
				return this.items[idx][key] != this.siteid;
			},


			addItem : function(prefix, data) {

				var idx = this.items.length;
				this.$set(this.items, idx, {});

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
				}

				var prefix = prefix || this.domain + '.lists.';
				this.$set(this.items[idx], prefix + 'type', data && data[prefix + 'type'] || $('#item-media-group').data('listtype'));
				this.$set(this.items[idx], prefix + 'siteid', this.siteid);
				this.$set(this.items[idx], 'media.siteid', this.siteid);
				this.$set(this.items[idx], 'media.languageid', null);
				this.$set(this.items[idx], 'media.status', 1);
			},


			removeItem : function(idx) {
				this.items.splice(idx, 1);
			},


			addConfig : function(idx) {

				if(!this.items[idx]['config']) {
					this.$set(this.items[idx], 'config', {'key': [], 'val': []});
				}

				this.items[idx]['config']['key'].push('');
				this.items[idx]['config']['val'].push('');
			},


			getConfig : function(idx) {

				 if(this.items[idx]['config'] && this.items[idx]['config']['key']) {
					 return this.items[idx]['config']['key'];
				 }
				 return [];
			},


			removeConfig : function(idx, pos) {
				this.items[idx]['config']['key'].splice(pos, 1);
				this.items[idx]['config']['val'].splice(pos, 1);
			},


			getCss : function(idx) {
				return ( idx !== 0 && this.items[idx] && this.items[idx]['media.id'] ? 'collapsed' : 'show' );
			},


			getLabel : function(idx) {
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


			getUrl : function(prefix, url) {

				var str = url.substr(0, 4);
				return (str === 'http' || str === 'data' ? url : prefix + url);
			},


			toggle : function(idx) {
				this.$set(this.advanced, idx, (!this.advanced[idx] ? true : false));
			},


			updateFile : function(idx, files) {

				if(files.length > 0) {

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
				}
			},


			addPropertyItem : function(idx) {

				if(!this.items[idx]) {
					this.$set(this.items, idx, {});
				}

				if(!this.items[idx]['property']) {
					this.$set(this.items[idx], 'property', []);
				}

				var len = this.items[idx]['property'].length;

				if(!this.items[idx]['property'][len]) {
					this.$set(this.items[idx]['property'], len, {});
				}

				var keys = ['media.property.id', 'media.property.languageid', 'media.property.type', 'media.property.value'];

				for(key in keys) {
					key = keys[key]; this.$set(this.items[idx]['property'][len], key, '');
				}

				this.$set(this.items[idx]['property'][len], 'media.property.siteid', this.siteid);
			},


			getPropertyData : function(idx) {

				if(this.items[idx] && this.items[idx]['property']) {
					return this.items[idx]['property'];
				}

				return [];
			},


			removePropertyItem : function(idx, propidx) {
				this.items[idx]['property'].splice(propidx, 1);
			}
		}
	},


	init : function() {

		this.media = new Vue({
			'el': '#item-media-group',
			'data': {
				'advanced': [],
				'items': $("#item-media-group").data("items"),
				'keys': $("#item-media-group").data("keys"),
				'siteid': $("#item-media-group").data("siteid"),
				'domain': $("#item-media-group").data("domain")
			},
			'mixins': [this.mixins]
		});
	}
};



Aimeos.Price = {

	mixins : {
		'methods': {

			checkSite : function(key, idx) {
				return this.items[idx][key] != this.siteid;
			},


			addItem : function(prefix, data) {

				var idx = this.items.length;
				this.$set(this.items, idx, {});

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
				}

				this.$set(this.items[idx], prefix + 'type', data && data[prefix + 'type'] || $('#item-price-group').data('listtype'));
				this.$set(this.items[idx], prefix + 'siteid', this.siteid);
				this.$set(this.items[idx], 'price.siteid', this.siteid);
				this.$set(this.items[idx], 'price.taxrates', {'': ''});
				this.$set(this.items[idx], 'price.quantity', '1');
				this.$set(this.items[idx], 'price.status', '1');
			},


			removeItem : function(idx) {
				this.items.splice(idx, 1);
			},


			addConfig : function(idx) {

				if(!this.items[idx]['config']) {
					this.$set(this.items[idx], 'config', {'key': [], 'val': []});
				}

				this.items[idx]['config']['key'].push('');
				this.items[idx]['config']['val'].push('');
			},


			getConfig : function(idx) {

				 if(this.items[idx]['config'] && this.items[idx]['config']['key']) {
					 return this.items[idx]['config']['key'];
				 }
				 return [];
			},


			removeConfig : function(idx, pos) {
				this.items[idx]['config']['key'].splice(pos, 1);
				this.items[idx]['config']['val'].splice(pos, 1);
			},


			getCss : function(idx) {
				return ( idx !== 0 && this.items[idx]['price.id'] ? 'collapsed' : 'show' );
			},


			getLabel : function(idx) {
				var label = '';
				var type = $('#item-price-group-data-' + idx + ' .item-type option[value="' + this.items[idx]['price.type'] + '"]').html();
				var currency = $('#item-price-group-data-' + idx + ' .item-currencyid').val();

				label += (this.items[idx]['price.quantity'] ? this.items[idx]['price.quantity'] + ' ~ ' : '');
				label += (this.items[idx]['price.value'] ? this.items[idx]['price.value'] : '');
				label += (this.items[idx]['price.costs'] ? ' + ' + this.items[idx]['price.costs'] : '');
				label += (currency ? ' ' + currency : (this.items[idx]['price.currencyid'] ? ' ' + this.items[idx]['price.currencyid'] : ''));
				label += (type ? ' (' + type.trim() + ')' : (this.items[idx]['price.type'] ? ' (' + this.items[idx]['price.type'] + ')' : ''));

				if(this.items[idx]['price.status'] < 1) {
					label = '<s>' + label + '</s>';
				}

				return label;
			},


			toggle : function(idx) {
				this.$set(this.advanced, idx, (!this.advanced[idx] ? true : false));
			},


			addPropertyItem : function(idx) {

				if(!this.items[idx]) {
					this.$set(this.items, idx, {});
				}

				if(!this.items[idx]['property']) {
					this.$set(this.items[idx], 'property', []);
				}

				var len = this.items[idx]['property'].length;

				if(!this.items[idx]['property'][len]) {
					this.$set(this.items[idx]['property'], len, {});
				}

				var keys = ['price.property.id', 'price.property.languageid', 'price.property.type', 'price.property.value'];

				for(key in keys) {
					key = keys[key]; this.$set(this.items[idx]['property'][len], key, '');
				}

				this.$set(this.items[idx]['property'][len], 'price.property.siteid', this.siteid);
			},


			getPropertyData : function(idx) {

				if(this.items[idx] && this.items[idx]['property']) {
					return this.items[idx]['property'];
				}

				return [];
			},


			removePropertyItem : function(idx, propidx) {
				this.items[idx]['property'].splice(propidx, 1);
			}
		}
	},


	init : function() {

		this.price = new Vue({
			'el': '#item-price-group',
			'data': {
				'advanced': [],
				'items': $("#item-price-group").data("items"),
				'keys': $("#item-price-group").data("keys"),
				'siteid': $("#item-price-group").data("siteid"),
				'domain': $("#item-price-group").data("domain")
			},
			'mixins': [this.mixins]
		});
	}
};



Aimeos.Property = {

	mixins : {
		'methods': {

			/**
			 * @param {string} key
			 * @param {int} idx
			 * @deprecated 2020.01 Key will be removed, set "domain" instead
			 */
			checkSite : function(key, idx) {
				return this.items[idx][key || this.domain + '.property.siteid'] != this.siteid;
			},


			/**
			 * @param {string} prefix
			 * @param {object} data
			 * @deprecated 2020.01 Prefix will be removed, set "domain" instead
			 */
			addItem : function(prefix, data) {

				var idx = this.items.length;
				this.$set(this.items, idx, {});

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
				}

				if(this.domain) {
					this.$set(this.items[idx], this.domain  + '.property.siteid', this.siteid);
					this.$set(this.items[idx], this.domain  + '.property.languageid', null);
				} else {
					this.$set(this.items[idx], prefix + 'siteid', this.siteid);
					this.$set(this.items[idx], prefix + 'languageid', null);
				}
			},


			removeItem : function(idx) {
				this.items.splice(idx, 1);
			}
		}
	},


	init : function() {

		this.property = new Vue({
			'el': '.property-list',
			'data': {
				'items': $(".property-list").data("items"),
				'keys': $(".property-list").data("keys"),
				'siteid': $(".property-list").data("siteid"),
				'domain': $(".property-list").data("domain")
			},
			'mixins': [this.mixins]
		});
	}
};



Aimeos.Text = {

	mixins : {
		'methods': {

			checkSite : function(key, idx) {
				return this.items[idx][key] != this.siteid;
			},


			addItem : function(prefix, data) {

				var idx = this.items.length;
				this.$set(this.items, idx, {});

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items[idx], key, data && data[key] || '');
				}

				this.$set(this.items[idx], prefix + 'type', data && data[prefix + 'type'] || $('#item-text-group').data('listtype'));
				this.$set(this.items[idx], prefix + 'siteid', this.siteid);
				this.$set(this.items[idx], 'text.siteid', this.siteid);
				this.$set(this.items[idx], 'text.status', '1');
			},


			removeItem : function(idx) {
				this.items.splice(idx, 1);
			},


			addConfig : function(idx) {

				if(!this.items[idx]['config']) {
					this.$set(this.items[idx], 'config', {'key': [], 'val': []});
				}

				this.items[idx]['config']['key'].push('');
				this.items[idx]['config']['val'].push('');
			},


			getConfig : function(idx) {

				 if(this.items[idx]['config'] && this.items[idx]['config']['key']) {
					 return this.items[idx]['config']['key'];
				 }
				 return [];
			},


			removeConfig : function(idx, pos) {
				this.items[idx]['config']['key'].splice(pos, 1);
				this.items[idx]['config']['val'].splice(pos, 1);
			},


			getCss : function(idx) {
				return ( idx !== 0 && this.items[idx]['text.id'] ? 'collapsed' : 'show' );
			},


			getLabel : function(idx) {
				var label = '';
				var type = $('#item-text-group-data-' + idx + ' .item-type option[value="' + this.items[idx]['text.type'] + '"]').html();

				label += (this.items[idx]['text.languageid'] ? this.items[idx]['text.languageid'].toUpperCase() : '');
				label += (type ? ' (' + type.trim() + ')' : (this.items[idx]['text.type'] ? ' (' + this.items[idx]['text.type'] + ')' : ''));

				if(this.items[idx]['text.content']) {
					var tmp = document.createElement("span");
					tmp.innerHTML = this.items[idx]['text.content'];
					label += ': ' + (tmp.innerText || tmp.textContent || "").substr(0, 40);
				}

				if(this.items[idx]['text.status'] < 1) {
					label = '<s>' + label + '</s>';
				}

				return label;
			},


			toggle : function(idx) {
				this.$set(this.advanced, idx, (!this.advanced[idx] ? true : false));
			},


			translate : function(idx, langid) {

				if(!this.$options.translate) {
					alert('No translation service configured');
					return;
				}

				if(!this.$options.translate['url']) {
					alert('No translation URL for DeepL configured');
					return;
				}

				if(!this.$options.translate['key']) {
					alert('No translation credentials for DeepL configured');
					return;
				}

				var self = this;
				var data = {
					'auth_key': this.$options.translate['key'],
					'text' : this.items[idx]['text.content'],
					'target_lang' : langid.toUpperCase()
				};

				if(this.items[idx]['text.languageid']) {
					data['source_lang'] = this.items[idx]['text.languageid'].toUpperCase();
				}


				$.getJSON(this.$options.translate['url'] + '/translate', data).done(function(data) {

					var item = {
						'text.content': data['translations'] && data['translations'][0] && data['translations'][0]['text'] || '',
						'text.type': self.items[idx]['text.type'],
						'text.languageid': langid.toLowerCase()
					};

					item[(self.prefix || self.domain + '.lists.') + 'siteid'] = self.siteid;
					self.addItem(null, item);

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
	},


	init : function() {

		this.text = new Vue({
			'el': '#item-text-group',
			'translate': $("#item-text-group").data("translate"),
			'data': {
				'advanced': [],
				'items': $("#item-text-group").data("items"),
				'keys': $("#item-text-group").data("keys"),
				'siteid': $("#item-text-group").data("siteid"),
				'domain': $("#item-text-group").data("domain")
			},
			'mixins': [this.mixins]
		});
	}
};
