/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



var vmedia = new Vue({
	'el': '.item-image',
	'data': {
		'advanced': [],
		'items': $("#item-image-group").data("items"),
		'siteid': $("#item-image-group").data("siteid")
	},
	'methods': {

		checkSite : function(key, idx) {
			return this.items[key][idx] != this.siteid;
		},


		addItem : function(listPrefix) {

			var listtypeid = $('#item-image-group').data('listtypeid') || '';

			this.$set(this.items, listPrefix + 'id', (this.items[listPrefix + 'id'] || []).concat(['']));
			this.$set(this.items, listPrefix + 'siteid', (this.items[listPrefix + 'siteid'] || []).concat([this.siteid]));
			this.$set(this.items, listPrefix + 'typeid', (this.items[listPrefix + 'typeid'] || []).concat([listtypeid]));
			this.$set(this.items, listPrefix + 'datestart', (this.items[listPrefix + 'datestart'] || []).concat(['']));
			this.$set(this.items, listPrefix + 'dateend', (this.items[listPrefix + 'dateend'] || []).concat(['']));

			this.$set(this.items, 'media.siteid', (this.items['media.siteid'] || []).concat([this.siteid]));
			this.$set(this.items, 'media.preview', (this.items['media.preview'] || []).concat(['']));
			this.$set(this.items, 'media.url', (this.items['media.url'] || []).concat(['']));
			this.$set(this.items, 'media.label', (this.items['media.label'] || []).concat(['']));
			this.$set(this.items, 'media.status', (this.items['media.status'] || []).concat(['1']));
			this.$set(this.items, 'media.typeid', (this.items['media.typeid'] || []).concat(['']));
			this.$set(this.items, 'media.typename', (this.items['media.typename'] || []).concat(['']));
			this.$set(this.items, 'media.languageid', (this.items['media.languageid'] || []).concat([null]));
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
			return ( idx !== 0 && this.items['media.id'][idx] ? 'collapsed' : 'show' );
		},


		getLabel : function(idx) {
			var label = '';

			label += (this.items['media.languageid'][idx] ? this.items['media.languageid'][idx] + ': ' : '');
			label += (this.items['media.label'][idx] ? this.items['media.label'][idx] : '');
			label += (this.items['media.typename'][idx] ? ' (' + this.items['media.typename'][idx] + ')' : '');

			return label;
		},


		getUrl : function(prefix, url) {

			var str = url.substr(0, 4);
			return (str === 'http' || str === 'data' ? url : prefix + url);
		},


		updateFile : function(idx, files) {

			if(files.length > 0) {
				this.$set(this.items['media.label'], idx, files[0].name);
			}
		}
	}
});



var vprices = new Vue({
	'el': '.item-price',
	'data': {
		'advanced': [],
		'items': $("#item-price-group").data("items"),
		'siteid': $("#item-price-group").data("siteid")
	},
	'methods': {

		checkSite : function(key, idx) {
			return this.items[key][idx] != this.siteid;
		},


		addItem : function(siteid, listPrefix) {

			var listtypeid = $('#item-price-group').data('listtypeid') || '';
			var currencyid = $('#item-price-group').data('currencyid') || '';

			this.$set(this.items, listPrefix + 'id', (this.items[listPrefix + 'id'] || []).concat(['']));
			this.$set(this.items, listPrefix + 'siteid', (this.items[listPrefix + 'siteid'] || []).concat([this.siteid]));
			this.$set(this.items, listPrefix + 'typeid', (this.items[listPrefix + 'typeid'] || []).concat([listtypeid]));
			this.$set(this.items, listPrefix + 'datestart', (this.items[listPrefix + 'datestart'] || []).concat(['']));
			this.$set(this.items, listPrefix + 'dateend', (this.items[listPrefix + 'dateend'] || []).concat(['']));

			this.$set(this.items, 'price.siteid', (this.items['price.siteid'] || []).concat([this.siteid]));
			this.$set(this.items, 'price.value', (this.items['price.value'] || []).concat(['0.00']));
			this.$set(this.items, 'price.costs', (this.items['price.costs'] || []).concat(['0.00']));
			this.$set(this.items, 'price.status', (this.items['price.status'] || []).concat(['1']));
			this.$set(this.items, 'price.label', (this.items['price.label'] || []).concat(['']));
			this.$set(this.items, 'price.typeid', (this.items['price.typeid'] || []).concat(['']));
			this.$set(this.items, 'price.typename', (this.items['price.typename'] || []).concat(['']));
			this.$set(this.items, 'price.rebate', (this.items['price.rebate'] || []).concat(['0.00']));
			this.$set(this.items, 'price.taxrate', (this.items['price.taxrate'] || []).concat(['0.00']));
			this.$set(this.items, 'price.quantity', (this.items['price.quantity'] || []).concat(['1']));
			this.$set(this.items, 'price.currencyid', (this.items['price.currencyid'] || []).concat([currencyid]));
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
			return ( idx !== 0 && this.items['price.id'][idx] ? 'collapsed' : 'show' );
		},


		getLabel : function(idx) {
			var label = '';

			label += (this.items['price.quantity'][idx] ? this.items['price.quantity'][idx] + ' ~ ' : '');
			label += (this.items['price.value'][idx] ? this.items['price.value'][idx] : '');
			label += (this.items['price.costs'][idx] ? ' + ' + this.items['price.costs'][idx] : '');
			label += (this.items['price.currencyid'][idx] ? ' ' + this.items['price.currencyid'][idx] : '');
			label += (this.items['price.typename'][idx] ? ' (' + this.items['price.typename'][idx] + ')' : '');

			return label;
		}
	}
});



Aimeos.Address = {

	init : function() {

		this.addBlock();
		this.copyBlock();
		this.removeBlock();
		this.setupComponents();
		this.updateHeader();
	},


	addBlock : function() {

		$(".item-address").on("click", ".card-tools-more .act-add", function(ev) {
			ev.stopPropagation();

			var number = Math.floor((Math.random() * 1000));
			var node = $(".group-item.prototype", ev.delegateTarget);
			var clone = Aimeos.addClone(node, Aimeos.getCountries, Aimeos.Address.select);

			$(".card-block", clone).attr("id", "item-address-group-data-" + number);
			$(".card-header", clone).attr("id", "item-address-group-item-" + number);
			$(".card-header", clone).attr("data-target", "#item-address-group-data-" + number);
			$(".card-header", clone).attr("aria-controls", "item-address-group-data-" + number);
		});
	},


	copyBlock : function() {

		$(".item-address").on("click", ".header .act-copy", function(ev) {
			ev.stopPropagation();

			var number = Math.floor((Math.random() * 1000));
			var block = $(this).closest(".group-item");
			var clone = Aimeos.addClone(block, Aimeos.getCountries, Aimeos.Address.select);

			$(".card-block", clone).attr("id", "item-address-group-data-" + number);
			$(".card-header", clone).attr("id", "item-address-group-item-" + number);
			$(".card-header", clone).attr("data-target", "#item-address-group-data-" + number);
			$(".card-header", clone).attr("aria-controls", "item-address-group-data-" + number);

			$("input.item-id", clone).val('');
			$(".card-header .header-label", clone).empty();
		});
	},


	removeBlock : function() {

		$(".item-address").on("click", ".header .act-delete", function() {
			Aimeos.focusBefore($(this).closest(".group-item")).remove();
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("card-block").find("input.item-countryid").val(node.val());
	},


	setupComponents : function() {

		$(".item-address .item-countryid.combobox").combobox({
			getfcn: Aimeos.getCountries,
			select: Aimeos.Address.select
		});
	},

	updateHeader : function() {

		$(".item-address").on("blur", "input.item-firstname,input.item-lastname,input.item-postal,input.item-city", function() {
			var item = $(this).closest(".group-item");
			var value = $("input.item-firstname", item).val() + ' ' + $("input.item-lastname", item).val()
				+ ' - ' + $("input.item-postal", item).val() + ' ' + $("input.item-city", item).val();

			$(".header .item-label", item).html(value);
		});
	}

};



Aimeos.Property = {

	init : function() {

		this.addLine();
		this.removeLine();
		this.setupComponents();
	},


	addLine : function() {

		$(".item-property").on("click", ".act-add", function(ev) {
			Aimeos.addClone($(".prototype", ev.delegateTarget), Aimeos.getOptionsLanguages);
		});
	},


	removeLine : function() {

		$(".item-property").on("click", ".act-delete", function() {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	setupComponents : function() {
		$(".item-property .combobox").combobox({getfcn: Aimeos.getOptionsLanguages});
	}
};



Aimeos.Text = {

	editorcfg : [
		{ name: 'clipboard', items: [ 'Undo', 'Redo' ] },
		{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
		{ name: 'insert', items: [ 'Image', 'SpecialChar' ] },
		{ name: 'tools', items: [ 'Maximize' ] },
		{ name: 'document', items: [ 'Source' ] },
		'/',
		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
		{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Blockquote' ] },
		{ name: 'styles', items: [ 'Format' ] }
	],


	init : function() {

		this.addBlock();
		this.removeBlock();
		this.setupComponents();
		this.updateHeader();
	},


	addBlock : function() {

		$(".item-text").on("click", ".card-tools-more .act-add", function(ev) {
			ev.stopPropagation();

			var number = Math.floor((Math.random() * 1000));
			var clone = Aimeos.addClone($(".prototype", ev.delegateTarget), Aimeos.getOptionsLanguages);

			$(".card-block", clone).attr("id", "item-text-group-data-" + number);
			$(".card-header", clone).attr("id", "item-text-group-item-" + number);
			$(".card-header", clone).attr("data-target", "#item-text-group-data-" + number);
			$(".card-header", clone).attr("aria-controls", "#item-text-group-data-" + number);

			$(".htmleditor-prototype", clone).ckeditor({toolbar: Aimeos.Text.editorcfg});
		});
	},


	removeBlock : function() {

		$(".item-text").on("click", ".header .act-delete", function() {
			$(this).closest(".group-item").remove();
		});
	},


	setupComponents : function() {

		$(".item-text .combobox").combobox({getfcn: Aimeos.getOptionsLanguages});
		$(".item-text .htmleditor").ckeditor({toolbar: Aimeos.Text.editorcfg});
	},


	updateHeader : function() {

		$(".item-text").on("blur change", "input.item-name-content,.text-langid", function() {
			var item = $(this).closest(".group-item");
			var value = $(".text-langid", item).val() + ': ' + $("input.item-name-content", item).val();

			$(".header .item-name-content", item).html(value);
		});
	}
};




$(function() {

	Aimeos.Address.init();
	Aimeos.Property.init();
	Aimeos.Text.init();

});
