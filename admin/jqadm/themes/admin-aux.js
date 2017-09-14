/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



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
			var clone = Aimeos.addClone($(".prototype", ev.delegateTarget), Aimeos.getOptionsLanguages);

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



Aimeos.Price = {

	init : function() {

		this.addBlock();
		this.removeBlock();
		this.update();
	},


	addBlock : function() {

		$(".item-price").on("click", ".card-tools-more .act-add", function(ev) {
			ev.stopPropagation();

			var number = Math.floor((Math.random() * 1000));
			var clone = Aimeos.addClone($(".prototype", ev.delegateTarget), Aimeos.getOptionsLanguages);

			$(".card-block", clone).attr("id", "item-price-group-data-" + number);
			$(".card-header", clone).attr("id", "item-price-group-item-" + number);
			$(".card-header", clone).attr("data-target", "#item-price-group-data-" + number);
			$(".card-header", clone).attr("aria-controls", "item-price-group-data-" + number);

			return false;
		});
	},


	removeBlock : function() {

		$(".item-price").on("click", ".header .act-delete", function() {
			$(this).closest(".group-item").remove();
		});
	},


	update : function() {

		$(".item-price").on("blur", ".item-value", function() {
			Aimeos.Price.updateHeader($(this).closest(".group-item"));
		});

		$(".item-price").on("blur", ".item-costs", function() {
			Aimeos.Price.updateHeader($(this).closest(".group-item"));
		});

		$(".item-price").on("change", ".item-currencyid", function() {
			Aimeos.Price.updateHeader($(this).closest(".group-item"));
		});
	},


	updateHeader : function(item) {

		var label = $(".card-block .item-value", item).val();
		var costs = $(".card-block .item-costs", item).val();
		var currency = $(".card-block .item-currencyid", item).val();

		if(typeof label == 'undefined') {
			label = '';
		}

		if(currency) {
			label = currency + ': ' + label;
		}

		if(costs) {
			label += ' + ' + costs;
		}

		$(".header .item-label", item).html(label);
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
	Aimeos.Price.init();
	Aimeos.Text.init();
});
