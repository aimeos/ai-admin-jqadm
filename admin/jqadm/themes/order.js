/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



/**
 * alexei/sprintf.js is licensed under the
 * BSD 3-clause "New" or "Revised" License
 */

!function() {
	'use strict'

	var re = {
		not_string: /[^s]/,
		not_bool: /[^t]/,
		not_type: /[^T]/,
		not_primitive: /[^v]/,
		number: /[diefg]/,
		numeric_arg: /[bcdiefguxX]/,
		json: /[j]/,
		not_json: /[^j]/,
		text: /^[^\x25]+/,
		modulo: /^\x25{2}/,
		placeholder: /^\x25(?:([1-9]\d*)\$|\(([^\)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-gijostTuvxX])/,
		key: /^([a-z_][a-z_\d]*)/i,
		key_access: /^\.([a-z_][a-z_\d]*)/i,
		index_access: /^\[(\d+)\]/,
		sign: /^[\+\-]/
	}

	function sprintf(key) {
		// `arguments` is not an array, but should be fine for this call
		return sprintf_format(sprintf_parse(key), arguments)
	}

	function vsprintf(fmt, argv) {
		return sprintf.apply(null, [fmt].concat(argv || []))
	}

	function sprintf_format(parse_tree, argv) {
		var cursor = 1, tree_length = parse_tree.length, arg, output = '', i, k, match, pad, pad_character, pad_length, is_positive, sign
		for (i = 0; i < tree_length; i++) {
			if (typeof parse_tree[i] === 'string') {
				output += parse_tree[i]
			}
			else if (Array.isArray(parse_tree[i])) {
				match = parse_tree[i] // convenience purposes only
				if (match[2]) { // keyword argument
					arg = argv[cursor]
					for (k = 0; k < match[2].length; k++) {
						if (!arg.hasOwnProperty(match[2][k])) {
							throw new Error(sprintf('[sprintf] property "%s" does not exist', match[2][k]))
						}
						arg = arg[match[2][k]]
					}
				}
				else if (match[1]) { // positional argument (explicit)
					arg = argv[match[1]]
				}
				else { // positional argument (implicit)
					arg = argv[cursor++]
				}

				if (re.not_type.test(match[8]) && re.not_primitive.test(match[8]) && arg instanceof Function) {
					arg = arg()
				}

				if (re.numeric_arg.test(match[8]) && (typeof arg !== 'number' && isNaN(arg))) {
					throw new TypeError(sprintf('[sprintf] expecting number but found %T', arg))
				}

				if (re.number.test(match[8])) {
					is_positive = arg >= 0
				}

				switch (match[8]) {
					case 'b':
						arg = parseInt(arg, 10).toString(2)
						break
					case 'c':
						arg = String.fromCharCode(parseInt(arg, 10))
						break
					case 'd':
					case 'i':
						arg = parseInt(arg, 10)
						break
					case 'j':
						arg = JSON.stringify(arg, null, match[6] ? parseInt(match[6]) : 0)
						break
					case 'e':
						arg = match[7] ? parseFloat(arg).toExponential(match[7]) : parseFloat(arg).toExponential()
						break
					case 'f':
						arg = match[7] ? parseFloat(arg).toFixed(match[7]) : parseFloat(arg)
						break
					case 'g':
						arg = match[7] ? String(Number(arg.toPrecision(match[7]))) : parseFloat(arg)
						break
					case 'o':
						arg = (parseInt(arg, 10) >>> 0).toString(8)
						break
					case 's':
						arg = String(arg)
						arg = (match[7] ? arg.substring(0, match[7]) : arg)
						break
					case 't':
						arg = String(!!arg)
						arg = (match[7] ? arg.substring(0, match[7]) : arg)
						break
					case 'T':
						arg = Object.prototype.toString.call(arg).slice(8, -1).toLowerCase()
						arg = (match[7] ? arg.substring(0, match[7]) : arg)
						break
					case 'u':
						arg = parseInt(arg, 10) >>> 0
						break
					case 'v':
						arg = arg.valueOf()
						arg = (match[7] ? arg.substring(0, match[7]) : arg)
						break
					case 'x':
						arg = (parseInt(arg, 10) >>> 0).toString(16)
						break
					case 'X':
						arg = (parseInt(arg, 10) >>> 0).toString(16).toUpperCase()
						break
				}
				if (re.json.test(match[8])) {
					output += arg
				}
				else {
					if (re.number.test(match[8]) && (!is_positive || match[3])) {
						sign = is_positive ? '+' : '-'
						arg = arg.toString().replace(re.sign, '')
					}
					else {
						sign = ''
					}
					pad_character = match[4] ? match[4] === '0' ? '0' : match[4].charAt(1) : ' '
					pad_length = match[6] - (sign + arg).length
					pad = match[6] ? (pad_length > 0 ? pad_character.repeat(pad_length) : '') : ''
					output += match[5] ? sign + arg + pad : (pad_character === '0' ? sign + pad + arg : pad + sign + arg)
				}
			}
		}
		return output
	}

	var sprintf_cache = Object.create(null)

	function sprintf_parse(fmt) {
		if (sprintf_cache[fmt]) {
			return sprintf_cache[fmt]
		}

		var _fmt = fmt, match, parse_tree = [], arg_names = 0
		while (_fmt) {
			if ((match = re.text.exec(_fmt)) !== null) {
				parse_tree.push(match[0])
			}
			else if ((match = re.modulo.exec(_fmt)) !== null) {
				parse_tree.push('%')
			}
			else if ((match = re.placeholder.exec(_fmt)) !== null) {
				if (match[2]) {
					arg_names |= 1
					var field_list = [], replacement_field = match[2], field_match = []
					if ((field_match = re.key.exec(replacement_field)) !== null) {
						field_list.push(field_match[1])
						while ((replacement_field = replacement_field.substring(field_match[0].length)) !== '') {
							if ((field_match = re.key_access.exec(replacement_field)) !== null) {
								field_list.push(field_match[1])
							}
							else if ((field_match = re.index_access.exec(replacement_field)) !== null) {
								field_list.push(field_match[1])
							}
							else {
								throw new SyntaxError('[sprintf] failed to parse named argument key')
							}
						}
					}
					else {
						throw new SyntaxError('[sprintf] failed to parse named argument key')
					}
					match[2] = field_list
				}
				else {
					arg_names |= 2
				}
				if (arg_names === 3) {
					throw new Error('[sprintf] mixing positional and named placeholders is not (yet) supported')
				}
				parse_tree.push(match)
			}
			else {
				throw new SyntaxError('[sprintf] unexpected placeholder')
			}
			_fmt = _fmt.substring(match[0].length)
		}
		return sprintf_cache[fmt] = parse_tree
	}

	if (typeof window !== 'undefined') {
		window['sprintf'] = sprintf
		window['vsprintf'] = vsprintf
	}
}();



Aimeos.Order = {

	init : function() {

		this.addServcieAttributeLine();
		this.deleteServcieAttributeLine();
		this.setupServiceCodeSuggest();
		this.createShortAddress();
		this.toggleAddressForm();
		this.updateShortAddress();
		this.setupCustomer();

		Aimeos.Order.Invoice.init();
	},


	selectCustomer: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("form-group").find("select.item-customerid").val(node.val());
	},


	setupCustomer : function() {

		$(".item-order .item-customer.combobox").combobox({
			getfcn: Aimeos.getOptionsCustomers,
			select: Aimeos.Order.selectCustomer
		});
	},


	addServiceSuggest : function(input, node) {

		$(input).autocomplete({
			source: node.data("codes").split(','),
			minLength: 0,
			delay: 0
		});
	},


	addServcieAttributeLine : function() {

		var self = this;
		$(".aimeos .item-order .service-attr").on("click", ".act-add", function(ev) {

			var id = $(ev.delegateTarget).data("id");
			var node = Aimeos.addClone($(".prototype", ev.delegateTarget));
			self.addServiceSuggest($(".service-attr-code", node), $(ev.delegateTarget));

			$("input", node).each(function() {
				$(this).attr("name", $(this).attr("name").replace("_id_", id));
			});
		});
	},


	deleteServcieAttributeLine : function() {

		$(".aimeos .item-order .service-attr").on("click", ".act-delete", function(ev) {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	setupServiceCodeSuggest : function() {

		var node = $(".aimeos .item-order .service-attr");

		if( node.length > 0 ) {
			this.addServiceSuggest($(".service-attr-code", node), node);

			$(".aimeos .item-order .service-attr").on("click", ".service-attr-code", function(ev) {
				$(this).autocomplete("search", "");
			});
		}
	},


	createShortAddress : function() {

		$(".aimeos .item-order .item-address").each(function() {

			var form = $(".address-form", this);
			var node = $(".address-text", this);
			var format = node.data("format");

			node.html(sprintf(format,
				'<span class="company">' + $("[data-field=company]", form).val() + '</span>',
				'<span class="firstname">' + $("[data-field=firstname]", form).val() + '</span>',
				'<span class="lastname">' + $("[data-field=lastname]", form).val() + '</span>',
				'<span class="address1">' + $("[data-field=address1]", form).val() + '</span>',
				'<span class="address2">' + $("[data-field=address2]", form).val() + '</span>',
				'<span class="postal">' + $("[data-field=postal]", form).val() + '</span>',
				'<span class="city">' + $("[data-field=city]", form).val() + '</span>',
				'<span class="state">' + $("[data-field=state]", form).val() + '</span>',
				'<span class="countryid">' + $("[data-field=countryid]", form).val() + '</span>',
				'<span class="email">' + $("[data-field=email]", form).val() + '</span>',
				'<span class="telephone">' + $("[data-field=telephone]", form).val() + '</span>',
				'<span class="vatid">' + $("[data-field=vatid]", form).val() + '</span>'
			).replace(/(\n)+/g, '\n').replace(/\n/g, '<br/>'));
		});
	},


	toggleAddressForm : function() {

		$(".aimeos .item-order .item-address").on("click", ".address-short", function(ev) {
			$(".address-form", ev.delegateTarget).slideToggle();
		});
	},


	updateShortAddress : function() {

		$(".aimeos .item-order .item-address").on("change", "input,select", function(ev) {
			$(".address-text ." + $(this).data("field"), ev.delegateTarget).html($(this).val());
		});
	}
};



Aimeos.Order.Invoice = {

	init : function() {

		this.addItem();
		this.closeItem();
	},


	addItem : function() {

		$(".aimeos .item-order .item-invoice").on("click", ".list-header .act-add", function(ev) {
			Aimeos.addClone($(".list-item-new.prototype", ev.delegateTarget));
		});
	},


	closeItem : function() {

		$(".aimeos .item-order .item-invoice").on("click", ".act-close", function(ev) {
			$(this).closest("tr").remove();
		});
	}
};



$(function() {

	Aimeos.Order.init();
});
