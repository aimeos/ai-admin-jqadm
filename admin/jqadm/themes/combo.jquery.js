/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2018-2017
 */


(function( $ ) {

	$.widget( "ai.combobox", {

		_create: function() {

			this.wrapper = $( "<span>" ).addClass( "ai-combobox form-control" ).insertAfter( this.element );

			this._createAutocomplete(this.element.hide());
			this._createShowAll();
		},


		_createAutocomplete: function(select) {

			var selected = this.element.children(":selected");
			var value = selected.val() ? selected.text() : "";
			var self = this;

			this.input = $("<input>");
			this.input.appendTo(this.wrapper);
			this.input.val(String(value).trim());
			this.input.attr("title", "");
			this.input.attr("tabindex", select.attr("tabindex" ));
			this.input.prop("readonly", this.element.is("[readonly]"));
			this.input.prop("required", this.element.is("[required]"));
			this.input.addClass("ai-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left");

			this.input.autocomplete({
				delay: 0,
				minLength: 0,
				source: $.proxy( this, "_source" ),
				select: function(ev, ui) {
					self.element.val(ui.item.value).find("input").val(ui.item.label);
					ev.preventDefault();
				},
				focus: function(ev, ui) {
					self.element.val(ui.item.value).next().find("input").val(ui.item.label);
					ev.preventDefault();
				}
			});

			this.input.tooltip({
				tooltipClass: "ui-state-highlight"
			});

			this._on( this.input, {
				autocompleteselect: function( event, ui ) {
					ui.item.option.selected = true;
					this._trigger( "select", event, {
						item: ui.item.option
					});
				},

				autocompletechange: "_removeInvalid"
			});
		},


		_createShowAll: function() {

			var input = this.input;
			var wasOpen = false;

			var btn = $( '<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icons-only"><span class="ui-button-icon-primary ui-icon ui-icon-triangle-1-s"></span></button>' );

			btn.attr("tabindex", -1);
			btn.appendTo(this.wrapper);
			btn.button();
			btn.removeClass("ui-corner-all");
			btn.prop("disabled", this.element.is("[readonly]"));
			btn.addClass("ai-combobox-toggle ui-corner-right");

			btn.mousedown(function() {
				wasOpen = input.autocomplete( "widget" ).is( ":visible" );
			});

			btn.click(function(ev) {
				ev.stopPropagation();
				ev.preventDefault();
				input.focus();

				// Close if already visible
				if ( wasOpen ) {
					return;
				}

				// Pass empty string as value to search for, displaying all results
				input.autocomplete( "search", "" );
			});
		},


		_source: function( request, response ) {
			this.options.getfcn( request, response, this.element );
		},


		_removeInvalid: function( event, ui ) {

			// Selected an item, nothing to do
			if ( ui.item ) {
				return;
			}

			// Search for a match (case-insensitive)
			var valueLowerCase = this.input.val().toLowerCase();
			var valid = false;

			this.element.children( "option" ).each(function() {
				if ( $( this ).text().toLowerCase() === valueLowerCase ) {
					this.selected = valid = true;
					return false;
				}
			});

			// Found a match, nothing to do
			if ( valid ) {
				return;
			}

			// Remove invalid value
			this.input.val( "" );
			this.element.val( "" );
			this.input.autocomplete( "instance" ).term = "";
		},


		_destroy: function() {

			this.wrapper.remove();
			this.element.show();
		}
	});

})( jQuery );
