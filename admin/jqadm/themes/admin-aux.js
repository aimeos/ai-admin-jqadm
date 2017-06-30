/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



Aimeos.Image = {

	init : function() {

		this.addFocus();
		this.addLines();
		this.removeLine();
	},


	addFocus : function() {

		$(".item-image .btn").on("focus", ".fileupload", function(ev) {
			$(ev.delegateTarget).addClass("focus");
		});

		$(".item-image .btn").on("blur", ".fileupload", function(ev) {
			$(ev.delegateTarget).removeClass("focus");
		});
	},


	addLines : function() {

		$(".item-image").on("change", ".fileupload", function(ev) {

			$(this).each( function(idx, el) {
				var line = $(".prototype", ev.delegateTarget);

				for(i=0; i<el.files.length; i++) {

					var img = new Image();
					var file = el.files[i];
					var clone = Aimeos.addClone(line, Aimeos.getOptionsLanguages);

					clone.addClass("upload");
					$("input.item-label", clone).val(el.files[i].name);

					img.src = file;
					$(".image-preview", clone).append(img);

					var reader = new FileReader();
					reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
					reader.readAsDataURL(file);
				}
			});
		});
	},


	removeLine : function() {

		$(".item-image").on("click", ".act-delete", function(ev) {
			Aimeos.focusBefore($(this).parents("tr")).remove();
		});
	}
};



Aimeos.Price = {

	init : function() {

		this.addBlock();
		this.removeBlock();
		this.updateHeader();
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
			$(this).parents(".group-item").remove();
		});
	},


	updateHeader : function() {

		$(".item-price").on("blur", "input.item-label", function() {
			var item = $(this).parents(".group-item");
			var value = $(this).val();

			$(".header .item-label", item).html(value);
		});
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

			return false;
		});
	},


	removeBlock : function() {

		$(".item-text").on("click", ".header .act-delete", function() {
			$(this).parents(".group-item").remove();
		});
	},


	setupComponents : function() {

		$(".item-text .combobox").combobox({getfcn: Aimeos.getOptionsLanguages});
		$(".item-text .htmleditor").ckeditor({toolbar: Aimeos.Text.editorcfg});
	},


	updateHeader : function() {

		$(".item-text").on("blur", "input.item-name-content", function() {
			var item = $(this).parents(".group-item");
			var value = $(this).val();

			$(".header .item-name-content", item).html(value);
		});
	}
};




$(function() {

	Aimeos.Image.init();
	Aimeos.Price.init();
	Aimeos.Text.init();
});
