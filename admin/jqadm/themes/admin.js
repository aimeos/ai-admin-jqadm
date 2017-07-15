/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2017
 */


Aimeos = {

	options : null,


	init : function() {

	},


	addClone : function(node, getfcn, selectfn, after) {

		var clone = node.clone().removeClass("prototype");
		var combo = $(".combobox-prototype", clone);

		combo.combobox({getfcn: getfcn, select: selectfn});
		combo.removeClass("combobox-prototype");
		combo.addClass("combobox");

		$("[disabled='disabled']", clone).prop("disabled", false);

		if(after) {
			clone.insertAfter(node);
		} else {
			clone.insertBefore(node);
		}

		return clone;
	},


	focusBefore : function(node) {

		var elem = $(":focus", node);
		var elements = $(".aimeos [tabindex=" + elem.attr("tabindex") + "]:visible");
		var idx = elements.index(elem) - $("[tabindex=" + elem.attr("tabindex") + "]:visible", node).length;

		if(idx > -1) {
			elements[idx].focus();
		}

		return node;
	},


	getOptions : function(request, response, element, domain, key, sort, criteria) {

		Aimeos.options.done(function(data) {

			var compare = {}, field = {}, list = {}, params = {}, param = {};

			compare[key] = request.term;
			list = criteria ? [{'=~': compare}, criteria] : [{'=~': compare}];
			field[domain] = key;

			param['filter'] = {'&&': list};
			param['fields'] = field;
			param['sort'] = sort;

			if( data.meta && data.meta.prefix ) {
				params[data.meta.prefix] = param;
			} else {
				params = param;
			}

			$.ajax({
				dataType: "json",
				url: data.meta.resources[domain] || null,
				data: params,
				success: function(result) {
					var list = result.data || [];

					$("option", element).remove();

					response( list.map(function(obj) {

						var opt = $("<option/>");

						opt.attr("value", obj.id);
						opt.text(obj.attributes[key]);
						opt.appendTo(element);

						return {
							label: obj.attributes[key] || null,
							value: obj.id,
							option: opt
						};
					}));
				}
			});
		});
	},


	getOptionsAttributes : function(request, response, element, criteria) {
		Aimeos.getOptions(request, response, element, 'attribute', 'attribute.label', 'attribute.label', criteria);
	},


	getOptionsCategories : function(request, response, element, criteria) {
		Aimeos.getOptions(request, response, element, 'catalog', 'catalog.label', 'catalog.label', criteria);
	},


	getOptionsCurrencies : function(request, response, element, criteria) {
		Aimeos.getOptions(request, response, element, 'locale/currency', 'locale.currency.id', '-locale.currency.status,locale.currency.id', criteria);
	},


	getOptionsLanguages : function(request, response, element, criteria) {
		Aimeos.getOptions(request, response, element, 'locale/language', 'locale.language.id', '-locale.language.status,locale.language.id', criteria);
	},


	getOptionsSites : function(request, response, element, criteria) {
		Aimeos.getOptions(request, response, element, 'locale/site', 'locale.site.label', '-locale.site.status,locale.site.label', criteria);
	},


	getOptionsProducts : function(request, response, element, criteria) {
		Aimeos.getOptions(request, response, element, 'product', 'product.label', 'product.label', criteria);
	}
};



Aimeos.Config = {

	init : function() {

		this.addConfigLine();
		this.deleteConfigLine();
		this.configComplete();
		this.resetSearch();
	},


	addConfigLine : function() {

		$(".aimeos .item .tab-pane").on("click", ".item-config .act-add", function(ev) {

			var clone = Aimeos.addClone($(".prototype", $(this).closest(".item-config")));
			var count = $(".list-item-new", ev.delegateTarget).length - 2; // minus prototype and must start with 0

			$("input", clone).each(function() {
				$(this).attr("name", $(this).attr("name").replace("idx", count));
			});

			$(".config-key", clone).autocomplete({
				source: ['css-class'],
				minLength: 0,
				delay: 0
			});
		});
	},


	deleteConfigLine : function() {

		$(".aimeos .item .tab-pane").on("click", ".item-config .act-delete", function(ev) {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	configComplete : function() {

		$(".aimeos .item .config-item .config-key").autocomplete({
			source: ['css-class'],
			minLength: 0,
			delay: 0
		});

		$(".aimeos .item").on("click", " .config-key", function(ev) {
			$(this).autocomplete("search", "");
		});
	},


	resetSearch : function() {

		$(".aimeos .list-search").on("click", ".act-reset", function(ev) {
			$("select", ev.delegateTarget).val("");
			$("input", ev.delegateTarget).val("");
			return false;
		});
	}
};



Aimeos.Filter = {

	init : function() {

		this.selectDDInput();
		this.setupFilterOperators();
		this.toggleSearch();
	},


	selectDDInput : function() {

		$(".aimeos .dropdown-menu label").on("click", function(ev) {
			ev.stopPropagation();
			return true;
		});
	},


	selectFilterOperator : function(select, type) {

		var operators = {
			'string': ['=~', '~=', '==', '!='],
			'integer': ['==', '!=', '>', '<', '>=', '<='],
			'datetime': ['>', '<', '>=', '<=', '==', '!='],
			'date': ['>', '<', '>=', '<=', '==', '!='],
			'float': ['>', '<', '>=', '<=', '==', '!='],
			'boolean': ['==', '!='],
		};
		var ops = operators[type];
		var list = [];

		$("option", select).each(function(idx, el) {
			var elem = $(el).removeProp("selected").hide();
			list[elem.val()] = elem;
		});

		if(ops) {
			for(op in ops.reverse()) {
				if(list[ops[op]]) {
					list[ops[op]].remove().show();
					select.prepend(list[ops[op]]);
				}
			};
		}

		$("option", select).first().prop("selected", "selected");
	},


	setupFilterOperators : function() {

		var select = $(".aimeos .main-navbar form .filter-operator");
		var type = $(".aimeos .main-navbar form .filter-key option").first().data("type");

		Aimeos.Filter.selectFilterOperator(select, type);


		$(".aimeos .main-navbar form").on("change", ".filter-key", function(ev) {

			var select = $(".filter-operator", ev.delegateTarget);
			var type = $(":selected", this).data("type");

			Aimeos.Filter.selectFilterOperator(select, type);
		});
	},


	toggleSearch : function() {

		$(".aimeos .main-navbar form").on("click", ".more", function(ev) {
			$(".filter-columns,.filter-key,.filter-operator", ev.delegateTarget).toggle(300, function() {
				$(ev.currentTarget).removeClass("more").addClass("less");
			});
		});

		$(".aimeos .main-navbar form").on("click", ".less", function(ev) {
			$(".filter-columns,.filter-key,.filter-operator", ev.delegateTarget).toggle(300, function() {
				$(ev.currentTarget).removeClass("less").addClass("more");
			});
		});
	}
};



Aimeos.Form = {

	init : function() {

		this.checkFields();
		this.checkSubmit();
		this.createDatePicker();
		this.setupNext();
		this.showErrors();
		this.toggleHelp();
	},


	checkFields : function() {

		$(".aimeos .item-content .readonly").on("change", "input,select", function(ev) {
			$(this).parent().addClass("has-danger");
		});


		$(".aimeos .item-content").on("blur", "input,select", function(ev) {

			if($(this).closest(".readonly").length > 0) {
				return;
			}

			if($(this).is(":invalid") === true) {
				$(this).parent().removeClass("has-success").addClass("has-danger");
			} else {
				$(this).parent().removeClass("has-danger").addClass("has-success");
			}
		});
	},


	checkSubmit : function() {

		$(".aimeos form").each(function() {
			this.noValidate = true;
		});

		$(".aimeos form").on("submit", function(ev) {
			var nodes = [];

			$(".card-header", this).removeClass("has-danger");
			$(".item-navbar .nav-link", this).removeClass("has-danger");

			$(".item-content input,select", this).each(function(idx, element) {
				var elem = $(element);

				if(elem.parents(".prototype").length === 0 && elem.is(":invalid") === true) {
					elem.parent().addClass("has-danger");
					nodes.push(element);
				} else {
					elem.parent().removeClass("has-danger");
				}
			});

			$.each(nodes, function() {
				$(".card-header", $(this).closest(".card")).addClass("has-danger");

				$(this).parents(".tab-pane").each(function() {
					$(".item-navbar .nav-item." + $(this).attr("id") + " .nav-link").addClass("has-danger");
				});
			});

			if( nodes.length > 0 ) {
				$('html, body').animate({
					scrollTop: '0px'
				});

				return false;
			}
		});
	},


	createDatePicker : function() {

		$(".aimeos .date").each(function(idx, elem) {

			$(elem).datepicker({
				dateFormat: $(elem).data("format"),
				constrainInput: false
			});
		});
	},


	setupNext : function() {

		$(".aimeos .item").on("click", ".next-action", function(ev) {
			$("#item-next", ev.delegateTarget).val($(this).data('next'));
			$(ev.delegateTarget).submit();
			return false;
		});
	},


	showErrors : function() {

		$(".aimeos .error-list .error-item").each(function() {
			$(".aimeos ." + $(this).data("key") + " .header").addClass("has-danger");
		});
	},


	toggleHelp : function() {

		$(".aimeos").on("click", ".help", function(ev) {
			$(".help-text", $(this).parent()).slideToggle(300);
		});
	}
};



Aimeos.Nav = {

	init : function() {

		this.addShortcuts();
		this.toggleMenu();
	},


	addShortcuts : function() {

		$(window).bind('keydown', function(ev) {
			if(ev.ctrlKey || ev.metaKey) {
				if(ev.altKey) {
					var key = String.fromCharCode(ev.which).toLowerCase();
					if(key.match(/[a-z]/)) {
						ev.preventDefault();
						window.location = $(".aimeos .sidebar-menu a[data-ctrlkey=" + key + "]").first().attr("href");
						return false;
					}
				}
				switch(String.fromCharCode(ev.which).toLowerCase()) {
					case 'a':
						ev.preventDefault();
						var node = $(".aimeos :focus").closest(".card,.content-block").find(".act-add:visible").first();
						if(node.length > 0) {
							node.trigger("click");
							return false;
						}

						node = $(".aimeos .act-add:visible").first();
						if(node.attr("href")) {
							window.location = node.attr('href');
						} else {
							node.trigger("click");
						}
						return false;
					case 'd':
						ev.preventDefault();
						var node = $(".aimeos .act-copy:visible").first();
						if(node.attr("href")) {
							window.location = node.attr('href');
						} else {
							node.trigger("click");
						}
						return false;
					case 's':
						ev.preventDefault();
						$(".aimeos form.item").first().submit();
						return false;
				}
			} else if(ev.which === 13) {
				$(".btn:focus").trigger("click");
			}
		});
	},


	toggleMenu : function() {

		$(".aimeos .main-sidebar").on("click", ".separator .more", function(ev) {
			$(".advanced", ev.delegateTarget).slideDown(300, function() {
				$(ev.currentTarget).removeClass("more").addClass("less");
			});
		});

		$(".aimeos .main-sidebar").on("click", ".separator .less", function(ev) {
			$(".advanced", ev.delegateTarget).slideUp(300, function() {
				$(ev.currentTarget).removeClass("less").addClass("more");
			});
		});
	}
};



Aimeos.Tabs = {

	init : function() {

		this.setPanelHeight();
		this.setupTabSwitch();
	},


	setPanelHeight : function() {

		$(".aimeos .tab-pane").on("click", ".filter-columns", function(ev) {
			// CSS class "show" will be added afterwards, thus it's reversed
			var height = ($(this).hasClass("show") ? 0 : $(".dropdown-menu", this).outerHeight());
			$(ev.delegateTarget).css("min-height", $("thead", ev.delegateTarget).outerHeight() + height);
		});
	},


	setupTabSwitch : function() {

		var hash = '';
		var url = document.location.toString();

		if(url.match('#')) {
			hash = url.split('#')[1];
			$('.nav-tabs a[href="#' + hash + '"]').tab('show');

			$("form").each(function() {
				$(this).attr("action", $(this).attr("action").split('#')[0] + '#' + hash);
			});
		}

		$('.nav-tabs a').on('shown.bs.tab', function (e) {
			hash = e.target.hash;

			if(history.pushState) {
				history.pushState(null, null, hash);
			} else {
				window.location.hash = hash;
				window.scrollTo(0, 0);
			}

			$("form").each(function() {
				$(this).attr("action", $(this).attr("action").split('#')[0] + hash);
			});
		})
	}
};




/**
 * Load JSON admin resource definition immediately
 */
Aimeos.options = $.ajax($(".aimeos").data("url"), {
	"method": "OPTIONS",
	"dataType": "json"
});


$(function() {

	Aimeos.init();
	Aimeos.Config.init();
	Aimeos.Filter.init();
	Aimeos.Form.init();
	Aimeos.Nav.init();
	Aimeos.Tabs.init();
});
