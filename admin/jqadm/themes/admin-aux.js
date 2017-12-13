/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



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
			this.$set(this.items, 'price.typeid', (this.items['price.typeid'] || []).concat(['']));
			this.$set(this.items, 'price.type', (this.items['price.type'] || []).concat(['']));
			this.$set(this.items, 'price.label', (this.items['price.label'] || []).concat(['']));
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
			this.items['config'][idx]['key'].splice(pos, 1)
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
			label += (this.items['price.type'][idx] ? ' (' + this.items['price.type'][idx] + ')' : '');

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



Aimeos.Image = {

	init : function() {

		this.addBlock();
		this.removeBlock();
		this.selectImage();
		this.update();
	},


	addBlock : function() {

		$(".item-image").on("click", ".card-tools-more .act-add", function(ev) {
			ev.stopPropagation();

			var number = Math.floor((Math.random() * 1000));
			var clone = Aimeos.addClone($(".card.prototype", ev.delegateTarget), Aimeos.getOptionsLanguages);

			$(".card-block", clone).attr("id", "item-image-group-data-" + number);
			$(".card-header", clone).attr("id", "item-image-group-item-" + number);
			$(".card-header", clone).attr("data-target", "#item-image-group-data-" + number);
			$(".card-header", clone).attr("aria-controls", "item-image-group-data-" + number);

			return false;
		});
	},


	removeBlock : function() {

		$(".item-image").on("click", ".header .act-delete", function() {
			$(this).closest(".group-item").remove();
		});
	},


	selectImage : function() {

		$(".item-image").on("change", ".fileupload", function(ev) {

			if(this.files.length > 0) {

				var item = $(this).closest(".group-item");
				var file = this.files[0];
				var img = new Image();

				img.src = file;

				$(".image-preview img", item).remove();
				$(".image-preview", item).append(img);

				$("input.item-label", item).val(this.files[0].name);
				Aimeos.Image.updateHeader(item);

				var reader = new FileReader();
				reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
				reader.readAsDataURL(file);
			}
		});
	},


	update : function() {

		$(".item-image").on("blur", "input.item-label", function() {
			Aimeos.Image.updateHeader($(this).closest(".group-item"));
		});

		$(".item-image").on("change", ".item-languageid", function() {
			Aimeos.Image.updateHeader($(this).closest(".group-item"));
		});
	},


	updateHeader : function(item) {

		var label = $(".card-block .item-label", item).val();
		var lang = $(".card-block .item-languageid", item).val();

		$(".header .item-label", item).html(lang ? lang + ': ' + label : label);
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
	Aimeos.Image.init();
	Aimeos.Property.init();
	Aimeos.Text.init();

});
