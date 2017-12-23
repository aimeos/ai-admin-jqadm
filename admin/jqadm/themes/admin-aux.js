/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
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
				return this.items[key][idx] != this.siteid;
			},


			addItem : function(prefix) {

				var idx = (this.items[prefix + 'id'] || []).length;

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items, key, (this.items[key] || []).concat(['']));
				}

				this.$set(this.items[prefix + 'siteid'], idx, this.siteid);
			},


			duplicateItem : function(idx) {

				for(key in this.items) {
					this.items[key].push(this.items[key][idx]);
				}
			},


			removeItem : function(idx) {

				for(key in this.items) {
					this.items[key].splice(idx, 1);
				}
			},


			getCountries : function() {
				return Aimeos.getCountries;
			},


			getCss : function(idx, prefix) {
				return ( idx !== 0 && this.items[prefix + 'id'] && this.items[prefix + 'id'][idx] ? 'collapsed' : 'show' );
			},


			getLabel : function(idx, prefix) {
				var label = '', addr = '';

				label += (this.items[prefix + 'firstname'][idx] ? this.items[prefix + 'firstname'][idx] + ' ' : '');
				label += (this.items[prefix + 'lastname'][idx] ? this.items[prefix + 'lastname'][idx] : '');

				addr += (this.items[prefix + 'postal'][idx] ? ' ' + this.items[prefix + 'postal'][idx] : '');
				addr += (this.items[prefix + 'city'][idx] ? ' ' + this.items[prefix + 'city'][idx] : '');

				if(addr && label) {
					return label + ' -' + addr;
				}

				return label + ' ' + addr;
			}
		}
	},


	init : function() {

		this.vaddress = new Vue({
			'el': '.item-address',
			'data': {
				'advanced': [],
				'items': $("#item-address-group").data("items"),
				'keys': $("#item-address-group").data("keys"),
				'siteid': $("#item-address-group").data("siteid")
			},
			'mixins': [this.mixins]
		});
	}
};



Aimeos.Media = {

	mixins : {
		'methods': {

			checkSite : function(key, idx) {
				return this.items[key][idx] != this.siteid;
			},


			addItem : function(prefix) {

				var idx = (this.items[prefix + 'id'] || []).length;
				var listtypeid = $('#item-image-group').data('listtypeid') || '';

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items, key, (this.items[key] || []).concat(['']));
				}

				this.$set(this.items[prefix + 'siteid'], idx, this.siteid);
				this.$set(this.items[prefix + 'typeid'], idx, listtypeid);
				this.$set(this.items['media.siteid'], idx, this.siteid);
				this.$set(this.items['media.languageid'], idx, null);
			},


			removeItem : function(idx) {

				for(key in this.items) {
					this.items[key].splice(idx, 1);
				}
			},


			addConfig : function(idx) {

				if(!this.items['config']) {
					this.$set(this.items, 'config', {});
				}

				if(!this.items['config'][idx]) {
					this.$set(this.items['config'], idx, {'key': [], 'val': []});
				}

				this.items['config'][idx]['key'].push('');
			},


			getConfig : function(idx) {

				 if(this.items['config'] && this.items['config'][idx] && this.items['config'][idx]['key']) {
					 return this.items['config'][idx]['key'];
				 }
				 return [];
			},


			removeConfig : function(idx, pos) {
				this.items['config'][idx]['key'].splice(pos, 1);
				this.items['config'][idx]['val'].splice(pos, 1);
			},


			getCss : function(idx) {
				return ( idx !== 0 && this.items['media.id'] && this.items['media.id'][idx] ? 'collapsed' : 'show' );
			},


			getLabel : function(idx) {
				var label = '';

				label += (this.items['media.languageid'][idx] ? this.items['media.languageid'][idx] + ': ' : '');
				label += (this.items['media.label'][idx] ? this.items['media.label'][idx] : '');
				label += (this.items['media.typename'] && this.items['media.typename'][idx] ? ' (' + this.items['media.typename'][idx] + ')' : '');

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
					this.$set(this.items['media.label'], idx, files[0].name);
				}
			}
		},
		'mounted' : function() {
			var el = document.getElementById('item-image-group');
			if(el) { Sortable.create(el, {handle: '.act-move'}); }
		}
	},


	init : function() {

		this.vmedia = new Vue({
			'el': '.item-image',
			'data': {
				'advanced': [],
				'items': $("#item-image-group").data("items"),
				'keys': $("#item-image-group").data("keys"),
				'siteid': $("#item-image-group").data("siteid")
			},
			'mixins': [this.mixins]
		});
	}
};



Aimeos.Price = {

	mixins : {
		'methods': {

			checkSite : function(key, idx) {
				return this.items[key][idx] != this.siteid;
			},


			addItem : function(prefix) {

				var idx = (this.items[prefix + 'id'] || []).length;
				var listtypeid = $('#item-price-group').data('listtypeid') || '';
				var currencyid = $('#item-price-group').data('currencyid') || '';

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items, key, (this.items[key] || []).concat(['']));
				}

				this.$set(this.items[prefix + 'siteid'], idx, this.siteid);
				this.$set(this.items[prefix + 'typeid'], idx, listtypeid);
				this.$set(this.items['price.currencyid'], idx, currencyid);
				this.$set(this.items['price.siteid'], idx, this.siteid);
				this.$set(this.items['price.quantity'], idx, '1');
				this.$set(this.items['price.status'], idx, '1');
			},


			removeItem : function(idx) {

				for(key in this.items) {
					this.items[key].splice(idx, 1);
				}
			},


			addConfig : function(idx) {

				if(!this.items['config']) {
					this.$set(this.items, 'config', {});
				}

				if(!this.items['config'][idx]) {
					this.$set(this.items['config'], idx, {'key': [], 'val': []});
				}

				this.items['config'][idx]['key'].push('');
			},


			getConfig : function(idx) {

				 if(this.items['config'] && this.items['config'][idx] && this.items['config'][idx]['key']) {
					 return this.items['config'][idx]['key'];
				 }
				 return [];
			},


			removeConfig : function(idx, pos) {
				this.items['config'][idx]['key'].splice(pos, 1);
				this.items['config'][idx]['val'].splice(pos, 1);
			},


			getCss : function(idx) {
				return ( idx !== 0 && this.items['price.id'] && this.items['price.id'][idx] ? 'collapsed' : 'show' );
			},


			getLabel : function(idx) {
				var label = '';

				label += (this.items['price.quantity'][idx] ? this.items['price.quantity'][idx] + ' ~ ' : '');
				label += (this.items['price.value'][idx] ? this.items['price.value'][idx] : '');
				label += (this.items['price.costs'][idx] ? ' + ' + this.items['price.costs'][idx] : '');
				label += (this.items['price.currencyid'][idx] ? ' ' + this.items['price.currencyid'][idx] : '');
				label += (this.items['price.typename'] && this.items['price.typename'][idx] ? ' (' + this.items['price.typename'][idx] + ')' : '');

				return label;
			},


			toggle : function(idx) {
				this.$set(this.advanced, idx, (!this.advanced[idx] ? true : false));
			}
		},
		'mounted' : function() {
			var el = document.getElementById('item-price-group');
			if(el) { Sortable.create(el, {handle: '.act-move'}); }
		}
	},


	init : function() {

		this.vprice = new Vue({
			'el': '.item-price',
			'data': {
				'advanced': [],
				'items': $("#item-price-group").data("items"),
				'keys': $("#item-price-group").data("keys"),
				'siteid': $("#item-price-group").data("siteid")
			},
			'mixins': [this.mixins]
		});
	}
};



Aimeos.Property = {

	mixins : {
		'methods': {

			checkSite : function(key, idx) {
				return this.items[key][idx] != this.siteid;
			},


			addItem : function(prefix) {

				var idx = (this.items[prefix + 'id'] || []).length;

				for(var key in this.keys) {
					key = this.keys[key]; this.$set(this.items, key, (this.items[key] || []).concat(['']));
				}

				this.$set(this.items[prefix + 'siteid'], idx, this.siteid);
				this.$set(this.items[prefix + 'languageid'], idx, null);
			},


			removeItem : function(idx) {

				for(key in this.items) {
					this.items[key].splice(idx, 1);
				}
			}
		}
	},


	init : function() {

		this.vproperty = new Vue({
			'el': '.property-list',
			'data': {
				'items': $(".property-list").data("items"),
				'keys': $(".property-list").data("keys"),
				'siteid': $(".property-list").data("siteid")
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


			addItem : function(listKey) {
				var entry = {
					'text.siteid': this.siteid,
					'text.languageid': '',
					'text.content': {}
				};
				entry[listKey] = {};

				for(type in this.types) {
					entry['text.content'][type] = '';
					entry[listKey][type] = '';
				}

				this.items.push(entry);
			},


			removeItem : function(idx) {
				this.items.splice(idx, 1);
			},


			getCss : function(idx) {
				return ( idx !== 0 && this.items[idx]['text.languageid'] ? 'collapsed' : 'show' );
			},


			getLabel : function(idx) {
				var label = '';

				label += (this.items[idx]['text.languageid'] ? this.items[idx]['text.languageid'] + ': ' : '');
				label += (this.items[idx]['text.content']['name'] ? this.items[idx]['text.content']['name'] : '');

				return label;
			}
		},
		'mounted' : function() {
			var el = document.getElementById('item-text-group');
			if(el) { Sortable.create(el, {handle: '.act-move'}); }
		}
	},


	init : function() {

		this.vtext = new Vue({
			'el': '.item-text',
			'data': {
				'items': $("#item-text-group").data("items"),
				'siteid': $("#item-text-group").data("siteid"),
				'types': $("#item-text-group").data("types")
			},
			'mixins': [this.mixins]
		});
	}
};
