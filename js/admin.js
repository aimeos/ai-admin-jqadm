/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */

/* Check for preferred theme mode (dark/light) */
const prefersDark = window.matchMedia("(prefers-color-scheme: dark)");
const isLight = document.cookie.includes('aimeos_backend_theme=light');

//Light by default (based on View used) - checks for Dark preference (by browser, or cookie)
if (prefersDark.matches && !isLight){
	document.body.classList.remove('light');
	document.body.classList.add('dark')
}

document.querySelectorAll(".btn-theme").forEach(item => {
	item.addEventListener("click", function() {
		['light', 'dark'].map(cl => document.body.classList.toggle(cl));
		const cookieName = "aimeos_backend_theme"
		const theme = document.body.classList.contains("dark") ? "dark" : "light";
		const expires = "expires=" + (new Date((new Date()).getTime() + (7*84600000))).toUTCString(); // 7 days (Safari does not allow for more)
		document.cookie = cookieName + "=" + theme + ";" + expires + ";path=/";
	});
});



Aimeos = {

	options: null,
	components: {},

	ckeditor: {
		htmlSupport: {
			allow: [{
				name: /div|p|span/,
				classes: true
			}],
			disallow: []
		},
		language: $('html').attr('lang'),
		mediaEmbed: {
			previewsInData: true,
			toolbar: ['|']
		},
		toolbar: [
			'link', '|',
			'bold', 'italic', '|',
			'undo', 'redo', '|',
			'specialCharacters', 'removeFormat', '|',
			'bulletedList', 'numberedList', '|',
			'blockQuote', '|',
			'insertTable', 'mediaEmbed', '|',
			'sourceEditing'
		]
	},

	flatpickr : {
		datetimerange: {
			defaultDate: null,
			defaultHour: 0,
			enableTime: true,
			locale: {
				rangeSeparator: ' - '
			},
			mode: "range",
			plugins: [new confirmDatePlugin({})],
			position: 'below',
			time_24hr: true,
			wrap: false
		},
		datetime: {
			// altFormat: 'M j, Y H:i K',
			defaultDate: null,
			defaultHour: 0,
			enableTime: true,
			plugins: [new confirmDatePlugin({})],
			position: 'below',
			time_24hr: true,
			wrap: false
		},
		daterange: {
			defaultDate: null,
			locale: {
				rangeSeparator: ' - '
			},
			mode: "range",
			position: 'below',
			wrap: false,
			dateFormat: "Y-m-d H:i:S",
			onChange: function (selectedDates, datestr) {
				if( typeof selectedDates[1] !== 'undefined' ) {
					selectedDates[1].setHours( 23 );
					selectedDates[1].setMinutes( 59 );
					selectedDates[1].setSeconds( 59 );
					this.setDate([ selectedDates[0], selectedDates[1] ]);
				}
			}
		},
		date: {
			// altFormat: 'M j, Y',
			defaultDate: null,
			position: 'below',
			wrap: false
		},
		time: {
			defaultHour: 0,
			enableTime: true,
			time_24hr: true,
			position: 'below',
			wrap: false
		}
	},

	addClone(node, getfcn, selectfn, after) {

		var clone = node.clone().removeClass("prototype");
		var combo = $(".combobox-prototype", clone);

		combo.combobox({getfcn: getfcn, select: selectfn});
		combo.removeClass("combobox-prototype");
		combo.addClass("combobox");

		$("[disabled='disabled']", clone).prop("disabled", false);

		if(typeof Modernizr != 'undefined') {
			if(!Modernizr.inputtypes['datetime-local']) {
				$("input[type='datetime-local']", clone).each(function(idx, elem) {
					$(elem).datepicker({
						dateFormat: 'yy-mm-dd',
						constrainInput: false
					});
				});
			}

			if(!Modernizr.inputtypes['date']) {
				$("input[type='date']", clone).each(function(idx, elem) {
					$(elem).datepicker({
						dateFormat: 'yy-mm-dd',
						constrainInput: false
					});
				});
			}
		}

		if(after) {
			clone.insertAfter(node);
		} else {
			clone.insertBefore(node);
		}

		return clone;
	},


	focusBefore(node) {

		var elem = $(":focus", node);
		var elements = $(".aimeos [tabindex=" + elem.attr("tabindex") + "]:visible");
		var idx = elements.index(elem) - $("[tabindex=" + elem.attr("tabindex") + "]:visible", node).length;

		if(idx > -1) {
			elements[idx].focus();
		}

		return node;
	},


	getOptions(request, response, element, domain, key, sort, criteria, labelFcn) {

		Aimeos.options.done(function(data) {

			let field = {}, list = {}, params = {}, param = {};
			let compare = {'||': []};

			for(entry of Array.isArray(key) ? key : [key]) {
				let term = {};
				term[entry] = request.term;
				compare['||'].push({'=~': term});
			}

			param['filter'] = criteria ? {'&&': [compare, criteria]} : compare;
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
				success: (result) => {
					var list = result.data || [];

					if(!labelFcn) {
						labelFcn = function(attr) {
							return attr[key] || null;
						}
					}

					$("option", element).remove();

					response( list.map(function(obj) {

						var opt = $("<option/>");

						opt.attr("value", obj.id);
						opt.text(labelFcn(obj.attributes));
						opt.appendTo(element);

						return {
							label: labelFcn(obj.attributes),
							value: obj.id,
							option: opt
						};
					}));
				}
			});
		});
	},


	getOptionsAttributes(request, response, element, criteria, labelFcn) {
		Aimeos.getOptions(request, response, element, 'attribute', 'attribute.label', 'attribute.label', criteria, labelFcn);
	},


	getOptionsCategories(request, response, element, criteria, labelFcn) {
		Aimeos.getOptions(request, response, element, 'catalog', 'catalog.label', 'catalog.label', criteria, labelFcn);
	},


	getOptionsCustomers(request, response, element, criteria, labelFcn) {
		Aimeos.getOptions(request, response, element, 'customer', 'customer.code', 'customer.code', criteria, labelFcn);
	},


	getOptionsCurrencies(request, response, element, criteria, labelFcn) {
		Aimeos.getOptions(request, response, element, 'locale/currency', 'locale.currency.id', '-locale.currency.status,locale.currency.id', criteria, labelFcn);
	},


	getOptionsLanguages(request, response, element, criteria, labelFcn) {
		Aimeos.getOptions(request, response, element, 'locale/language', 'locale.language.id', '-locale.language.status,locale.language.id', criteria, labelFcn);
	},


	getOptionsSites(request, response, element, criteria, labelFcn) {
		Aimeos.getOptions(request, response, element, 'locale/site', 'locale.site.label', '-locale.site.status,locale.site.label', criteria, labelFcn);
	},


	getOptionsProducts(request, response, element, criteria, labelFcn) {
		Aimeos.getOptions(request, response, element, 'product', ['product.label', 'product.code'], 'product.label', criteria, labelFcn);
	},


	lazy(selector, renderFcn) {

		if('IntersectionObserver' in window) {

			let callback = function(entries, observer) {
				for(let entry of entries) {
					if(entry.isIntersecting) {
						observer.unobserve(entry.target);
						renderFcn(entry.target);
					}
				};
			};

			$(selector).each(function() {
				(new IntersectionObserver(callback, {})).observe(this);
			});

		} else if($(selector).length) {
			renderFcn();
		}
	},


	query(gql) {
		return fetch($('.aimeos').data('graphql'), {
			method: 'POST',
			credentials: 'same-origin',
			headers: { // Laravel only
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]')?.attr('content')
			},
			body: JSON.stringify({'query': gql})
		}).then(response => {
			if(!response.ok) {
				console.error(response)
				throw new Error(response.statusText)
			}
			return response.json();
		}).then(result => {
			if(result.errors) {
				console.error(result)
				throw new Error('GraphQL query failed')
			}
			return result?.data
		})
	},


	vue(node) {
		return new Vue({
			el: node,
			data() {
				return {
					data: null
				}
			},
			beforeMount() {
				this.Aimeos = Aimeos;
				if(this.$el.dataset && this.$el.dataset.data) {
					this.data = JSON.parse(this.$el.dataset.data);
				}
			},
			methods: {
				add(data) {
					this.$refs[key].add(data);
				},
				remove(idx) {
					this.$refs[key].remove(idx);
				}
			},
			provide() {
				return {
					Aimeos: Aimeos
				};
			}
		});
	}
};



Aimeos.Config = {

	init() {

		this.addConfigLine();
		this.deleteConfigLine();
		this.configComplete();

		this.addConfigMapLine();
		this.deleteConfigMapLine();
		this.hideConfigMap();
		this.showConfigMap();

		this.addConfigListLine();
		this.deleteConfigListLine();
		this.hideConfigList();
		this.showConfigList();
	},


	setup(resource, provider, target, type) {

		if(!provider) {
			return;
		}

		Aimeos.options.done(function(data) {

			if(!data.meta || !data.meta.resources || !data.meta.resources[resource]) {
				return;
			}

			var params = {}, param = {id: provider};

			if(type) {
				param["type"] = type;
			}

			if(data.meta && data.meta.prefix) {
				params[data.meta.prefix] = param;
			} else {
				params = param;
			}

			$.ajax({
				url: data.meta.resources[resource],
				dataType: "json",
				data: params
			}).done(function(result) {

				$(result.data).each(function(idx, entry) {
					var nodes = $("table.item-config input.config-key", target);
					var node = null;
					var value = '';

					nodes.each(function() {
						if($(this).val() === entry.id) {
							node = $(this);
						}
					})

					if(node) {
						var el = $("table.item-config .config-item.prototype .config-type-" + entry.attributes.type, target).clone();
						var row = node.closest(".config-item");
						var valnode = $(".config-value", row);
						value = valnode.val();

						$("> [disabled='disabled']", el).prop("disabled", false);
						$("> input", el).val(value);
						el.prop("disabled", false);
						el.val(value);
						valnode.remove();

						$(".help-text", row).text(entry.attributes.label);
						$(".config-row-value", row).append(el);
					} else {
						var row = Aimeos.addClone($("table.item-config .config-item.prototype", target));

						$(".config-row-value .config-type:not(.config-type-" + entry.attributes.type + ")", row).remove();
						$(".config-row-key .help-text", row).text(entry.attributes.label);
						$(".config-value", row).val(entry.attributes.default);
						$(".config-key", row).val(entry.id);
					}

					if(entry.attributes.type === 'select') {
						$.each(entry.attributes.default, function(idx, label) {
							var opt = $('<option/>');
							opt.text(label);
							if(value === label) {
								opt.prop('selected', true);
							}
							$(".config-value", row).append(opt);
						});
					}

					if(!entry.attributes.required) {
						$(".config-value", row).prop("required", false);
						row.removeClass("mandatory");
					} else {
						$(".config-value", row).prop("required", true);
						row.addClass("mandatory");
					}
				});
			});
		});
	},


	addConfigLine() {

		$(".aimeos .item .tab-pane").on("click", ".item-config .actions .act-add", function(ev) {

			var node = $(this).closest(".item-config");
			var clone = Aimeos.addClone($(".prototype", node));
			var types = $(".config-type", clone);

			var count = $(".group-item:not(.prototype)", $(this).closest(".tab-pane")).length;

			if(count === 0) {
				count = $(".list-item-new", ev.delegateTarget).length - 2; // minus prototype and must start with 0
			} else {
				count -= 1; // minus already added block
			}

			if(types.length > 0 ) {
				$(".config-type:not(.config-type-string)", clone).remove();
			}

			$("input", clone).each(function() {
				$(this).attr("name", $(this).attr("name").replace("idx", count));
			});

			$(".config-key", clone).autocomplete({
				source: node.data("keys") || [],
				minLength: 0,
				delay: 0
			});
		});
	},


	deleteConfigLine() {

		$(".aimeos .item .tab-pane").on("click", ".item-config .config-item .actions .act-delete", function(ev) {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	configComplete() {

		var node = $(".aimeos .item-config");
		$(".config-item .config-key", node).autocomplete({
			source: node.data("keys") || [],
			minLength: 0,
			delay: 0
		});

		$(".aimeos .item").on("click", " .config-key", function(ev) {
			$(this).autocomplete("search", "");
		});
	},


	addConfigListLine() {

		$(".aimeos .item-config").on("click", ".config-list-table .config-list-actions .act-add", function(ev) {

			var node = $(this).closest(".config-list-table");
			var clone = Aimeos.addClone($(".prototype-list", node));

			clone.removeClass("prototype-list");
			$(".act-delete", clone).focus();

			return false;
		});
	},


	addConfigMapLine() {

		$(".aimeos .item-config").on("click", ".config-map-table .config-map-actions .act-add", function(ev) {

			var node = $(this).closest(".config-map-table");
			var clone = Aimeos.addClone($(".prototype-map", node));

			clone.removeClass("prototype-map");
			$(".act-delete", clone).focus();

			return false;
		});
	},


	deleteConfigListLine() {

		$(".aimeos .item-config").on("click", ".config-list-table .config-list-actions .act-delete", function(ev) {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	deleteConfigMapLine() {

		$(".aimeos .item-config").on("click", ".config-map-table .config-map-actions .act-delete", function(ev) {
			Aimeos.focusBefore($(this).closest("tr")).remove();
		});
	},


	hideConfigList() {

		$(".aimeos .item-config").on("click", ".config-list-table .config-list-actions .act-update", function(ev) {

			var obj = [];
			var table = $(this).closest(".config-list-table");
			var lines = $(".config-list-row:not(.prototype-list)", table)

			lines.each(function() {
				obj.push($("input.config-list-value", this).val());
			});

			$(".config-value", table.parent()).val(JSON.stringify(obj));

			table.hide();
			lines.remove();

			return false;
		});
	},


	hideConfigMap() {

		$(".aimeos .item-config").on("click", ".config-map-table .config-map-actions .act-update", function(ev) {

			var obj = {};
			var table = $(this).closest(".config-map-table");
			var lines = $(".config-map-row:not(.prototype-map)", table)

			lines.each(function() {
				obj[ $("input.config-map-key", this).val() ] = $("input.config-map-value", this).val();
			});

			$(".config-value", table.parent()).val(JSON.stringify(obj));

			table.hide();
			lines.remove();

			return false;
		});
	},


	showConfigList() {

		$(".aimeos .item-config").on("focus", ".config-value", function() {

			var table = $(".config-list-table", $(this).parent());

			if(table.is(":visible")) {
				return false;
			}

			try {
				var obj = JSON.parse($(this).val())
			} catch(e) {
				var obj = [];
			}

			for(var val of obj) {
				var clone = Aimeos.addClone($(".prototype-list", table));
				$(".config-list-value", clone).val(val);
				clone.removeClass("prototype-list");
			}

			table.show();
		});
	},


	showConfigMap() {

		$(".aimeos .item-config").on("focus", ".config-value", function() {

			var table = $(".config-map-table", $(this).parent());

			if(table.is(":visible")) {
				return false;
			}

			try {
				var obj = JSON.parse($(this).val())
			} catch(e) {
				var obj = {};
			}

			for(var key in obj) {
				var clone = Aimeos.addClone($(".prototype-map", table));
				$(".config-map-value", clone).val(obj[key]);
				$(".config-map-key", clone).val(key);
				clone.removeClass("prototype-map");
			}

			table.show();
		});
	}
};



Aimeos.Form = {

	init() {

		this.checkFields();
		this.checkSubmit();
		this.editFields();
		this.noedit();
		this.setupNext();
		this.showErrors();
		this.toggleHelp();
	},


	checkFields() {

		$(".aimeos form .readonly").on("change", "input,select", function(ev) {
			$(this).addClass("is-invalid");
		});


		$(".aimeos form").on("blur", "input,select", function(ev) {

			if($(this).closest(".readonly").length > 0 || $(this).hasClass("novalidate")) {
				return;
			}

			if($(this).is(":invalid") === true) {
				$(this).removeClass("is-valid").addClass("is-invalid");
			} else {
				$(this).removeClass("is-invalid").addClass("is-valid");
			}
		});
	},


	checkSubmit() {

		$(".aimeos form").each(function() {
			this.noValidate = true;
		});

		$(".aimeos form").on("submit", function(ev) {
			var nodes = [];

			document.querySelectorAll('.main-navbar .btn-primary').forEach(function(el) {
				el.classList.remove('is-invalid');
			});

			$(".card-header", this).removeClass("is-invalid");
			$(".item-header", this).removeClass("is-invalid");
			$(".item-navbar .nav-link", this).removeClass("is-invalid");

			$("input,select", this).each(function(idx, element) {
				var elem = $(element);

				if(elem.closest(".prototype").length === 0 && elem.is(":invalid") === true) {
					if(!element.classList.contains('.form-control') && !element.classList.contains('form-select')) {
						elem = elem.closest('.form-control');
					}

					nodes.push(elem.addClass("is-invalid"));
				} else {
					elem.removeClass("is-invalid");
				}
			});

			$("td.is-invalid", this).each(function(idx, element) {
				nodes.push(element);
			});

			$.each(nodes, function() {
				$(".card-header", $(this).closest(".card")).addClass("is-invalid");
				$(".item-header", $(this).closest(".card, .box")).addClass("is-invalid");

				$(this).closest(".tab-pane").each(function() {
					$(".item-navbar .nav-item." + $(this).attr("id") + " .nav-link").addClass("is-invalid");
				});
			});

			if( nodes.length > 0 ) {
				$('html, body').animate({
					scrollTop: '0px'
				});

				document.querySelectorAll('.main-navbar .btn-primary').forEach(function(el) {
					el.classList.add('is-invalid');
				});

				return false;
			}

			if($("input,select").length > $("#problem .max_input_vars").data("value")) {
				$("#problem .max_input_vars").show();
				$("#problem").modal("show");
				return false;
			}
		});
	},


	editFields() {

		$(".aimeos .list-item").on("click", ".act-edit", function(ev) {
			$("[disabled=disabled]", ev.delegateTarget).removeAttr("disabled");
			return false;
		});
	},


	noedit() {

		$("input.noedit, select.noedit").on('keydown paste', function(ev){
			if(ev.which != 9) return false; // ignore tab
		});
	},


	setupNext() {

		$(".aimeos .item").on("click", ".next-action", function(ev) {
			$("#item-next", ev.delegateTarget).val($(this).data('next'));
			$(ev.delegateTarget).submit();
			return false;
		});
	},


	showErrors() {

		$(".aimeos .error-list .error-item").each(function() {
			$(".aimeos ." + $(this).data("key") + " .header").addClass("is-invalid");
		});
	},


	toggleHelp() {

		$(".aimeos").on("click", ".help", function(ev) {
			var list = $(this).closest("table.item-config");

			if( list.length === 0 ) {
				list = $(this).parent();
			}

			$(".help-text", list).slideToggle(300);
		});

		$(".aimeos").on("click", ".act-help", function(ev) {
			$(".help-text", ev.delegateTarget).slideToggle(300);
		});
	}
};



Aimeos.List = {

	instance : null,


	init() {

		let node = document.querySelector(".list-view");
		if(node) {
			this.instance = new Vue({el: node, mixins: [this.mixins]});
		}
	},


	mixins : {
		data() {
			return {
				all: false,
				batch: false,
				columns: false,
				dialog: false,
				items: {},
				filter: {},
				domain: null,
				search: false,
				siteid: null,
				states: {}
			}
		},
		beforeMount() {
			this.Aimeos = Aimeos;

			if(this.$el.dataset) {
				if(this.$el.dataset.items) {
					this.items = JSON.parse(this.$el.dataset.items);
				}
				if(this.$el.dataset.filter) {
					this.filter = JSON.parse(this.$el.dataset.filter);
				}
				if(this.$el.dataset.domain) {
					this.domain = this.$el.dataset.domain.replace(/\//g, '.');
				}
				if(this.$el.dataset.siteid) {
					this.siteid = this.$el.dataset.siteid;
				}
			}
		},
		computed: {
			selected() {
				let count = 0;

				for(const key in this.items) {
					if(this.items[key].checked) {
						count++;
					}
				}

				return count;
			},

			unconfirmed() {
				let list = {};

				for(const key in this.items) {
					if(this.items[key].checked) {
						list[key] = this.items[key][this.domain + '.label'] || this.items[key][this.domain + '.code'];
					}
				}

				return list;
			}
		},
		methods: {
			askDelete(id, ev) {
				if(id) {
					this.clear(false);
					this.$set(this.items[id], 'checked', true);
				}

				this.deleteUrl = ev.target.href;
				this.dialog = true;
			},

			checked(id) {
				return this.items[id] && this.items[id].checked;
			},

			confirmDelete(val) {
				if(val) {
					if(this.$refs.form && this.deleteUrl) {
						this.$refs.form.action = this.deleteUrl;
						this.$refs.form.submit();
					} else {
						console.log('[Aimeos] Missing form reference or deleteUrl');
					}
				}

				if(Object.keys(this.unconfirmed).length === 1) {
					this.clear(false);
				}

				this.dialog = false;
			},

			clear(val) {
				this.all = val;
				for(const key in this.items) {
					if([this.siteid, ''].includes(this.items[key][this.domain + '.siteid'])) {
						this.$set(this.items[key], 'checked', val);
					}
				};
			},

			readonly(id) {
				return !(this.items[id] && this.items[id][this.domain + '.siteid'] == this.siteid);
			},

			reset() {
				if(this.filter['val'])
				{
					for(let idx of Object.keys(this.filter['val'])) {
						this.$set(this.filter['val'], idx, '');
					}
				}
			},

			setState(key) {
				this.$set(this.states, key, !this.states[key]);
			},

			state(key) {
				return !(this.states[key] || false);
			},

			toggle(id) {
				this.$set(this.items[id], 'checked', !this.items[id].checked);
			},

			toggleAll() {
				this.clear(this.all = !this.all);
			},

			value(idx) {
				return this.filter['val'] && this.filter['val'][idx] || null;
			}
		}
	},


	confirmDelete() {

		$("#confirm-delete").on("click", ".btn-danger", function(e) {

			const form = $("form.list");
			form.attr('action', $(this).data('url'));
			form.submit();

			return false;
		});
	}
};



Aimeos.Nav = {

	init() {

		this.addShortcuts();
		this.hoverMenu();
		this.toggleFormItems();
		this.toggleNavItems();
		this.toggleSubmenu();
	},


	addShortcuts() {

		$(document).bind('keydown', function(ev) {
			if(ev.ctrlKey || ev.metaKey) {
				var key = String.fromCharCode(ev.which).toLowerCase();

				if(ev.altKey) {
					if(key.match(/[a-z]/)) {
						var link = $(".aimeos .sidebar-menu a[data-ctrlkey=" + key + "]").first();

						if(link.length) {
							window.location = link.attr("href");
						}
					}
				}
				switch(key) {
					case 'i':
						var node = $(".aimeos :focus").closest(".card,.box").find(".act-add:visible").first();
						if(node.length > 0) {
							node.trigger("click");
							return false;
						}

						node = $(".aimeos .act-add:visible").first();
						if(node.attr("href")) {
							window.location = node.attr('href');
						} else {
							node.trigger("click");
							return false;
						}
					case 'd':
						var node = $(".aimeos .act-copy:visible").first();
						if(node.attr("href")) {
							window.location = node.attr('href');
						} else {
							node.trigger("click");
							return false;
						}
					case 's':
						$(".aimeos form.item").first().submit();
						return false;
				}
			} else if(ev.which === 13) {
				$(".btn:focus").trigger("click");
			}
		});
	},


	hoverMenu() {

		let active = document.querySelector(".aimeos .main-sidebar .sidebar-menu > li.active");

		document.querySelectorAll(".aimeos .main-sidebar .sidebar-menu > li:not(.none)").forEach(function(item) {
			item.addEventListener("mouseenter", function(ev) {
				if(item !== active && ev.target.previousElementSibling) {
					ev.target.previousElementSibling.classList.add("before");
				}
				if(item !== active && ev.target.nextElementSibling) {
					ev.target.nextElementSibling.classList.add("after");
				}
			});
			item.addEventListener("mouseleave", function(ev) {
				if(item !== active && ev.target.previousElementSibling) {
					ev.target.previousElementSibling.classList.remove("before");
				}
				if(item !== active && ev.target.nextElementSibling) {
					ev.target.nextElementSibling.classList.remove("after");
				}
			});
		});
	},


	toggleNavItems() {

		if(window.sessionStorage && window.sessionStorage.getItem('aimeos/jqadm/item/navbar') == 1) {
			$(".aimeos .item-navbar .navbar-content .more").removeClass("more").addClass("less");
			$(".aimeos .item-navbar .navbar-content").addClass("show");
		}

		$(".aimeos .item-navbar .navbar-content").on("click", ".more", function(ev) {
			if(window.sessionStorage) {
				window.sessionStorage.setItem('aimeos/jqadm/item/navbar', 1);
			}
			$(ev.currentTarget).removeClass("more").addClass("less");
			$(ev.delegateTarget).addClass("show");
		});

		$(".aimeos .item-navbar .navbar-content").on("click", ".less", function(ev) {
			if(window.sessionStorage) {
				window.sessionStorage.setItem('aimeos/jqadm/item/navbar', 0);
			}
			$(ev.currentTarget).removeClass("less").addClass("more");
			$(ev.delegateTarget).removeClass("show");
		});
	},


	toggleFormItems() {

		if(window.sessionStorage && window.sessionStorage.getItem('aimeos/jqadm/item/form') == 1) {
			$(".aimeos .item-content .separator .more").removeClass("more").addClass("less");
			$(".aimeos .item-content .form-group.advanced").css("display", "flex");
		}

		$(".aimeos .item-content").on("click", ".separator .more", function(ev) {
			$(".form-group.advanced", ev.delegateTarget).css("display", "flex");
			$(ev.currentTarget).removeClass("more").addClass("less");
			if(window.sessionStorage) {
				window.sessionStorage.setItem('aimeos/jqadm/item/form', 1);
			}
		});

		$(".aimeos .item-content").on("click", ".separator .less", function(ev) {
			$(".form-group.advanced", ev.delegateTarget).css("display", "none");
			$(ev.currentTarget).removeClass("less").addClass("more");
			if(window.sessionStorage) {
				window.sessionStorage.setItem('aimeos/jqadm/item/form', 0);
			}
		});
	},


	toggleSubmenu() {

		document.querySelectorAll(".aimeos .main-sidebar .sidebar-menu>li:not(.none)").forEach(function(item) {
			item.addEventListener("click", function(ev) {
				ev.target.closest("li").classList.add("show");
			});
		});

		document.querySelectorAll(".aimeos .main-sidebar .sidebar-menu .menu-header").forEach(function(item) {
			item.addEventListener("click", function(ev) {
				ev.target.closest("li.treeview").classList.remove("show");
				ev.stopPropagation();
			});
		});
	}
};



Aimeos.Tabs = {

	init() {

		this.setPanelHeight();
		this.setupTabSwitch();
	},


	setPanelHeight() {

		$(".aimeos .tab-pane").on("click", ".filter-columns", function(ev) {
			// CSS class "show" will be added afterwards, thus it's reversed
			var height = ($(this).hasClass("show") ? 0 : $(".dropdown-menu", this).outerHeight());
			$(ev.delegateTarget).css("min-height", $("thead", ev.delegateTarget).outerHeight() + height);
		});
	},


	setupTabSwitch() {

		var hash = '';
		var url = document.location.toString();

		if(url.match(/#[a-z0-9]+/i)) {
			hash = url.split('#')[1];
			$('.nav-tabs a[href="#' + hash + '"]').tab('show');

			$("form").each(function() {
				if($(this).attr("action") !== undefined) {
					$(this).attr("action", $(this).attr("action").split('#')[0] + '#' + hash);
				}
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
				if($(this).attr("action") !== undefined) {
					$(this).attr("action", $(this).attr("action").split('#')[0] + hash);
				}
			});
		})
	}
};



Aimeos.Log = {

	time : null,


	init() {

		this.toggleItem();
	},


	toggleItem() {

		$(".aimeos .list-log .log-message").on("mousedown", function(ev) {
			this.time = (new Date()).getTime();
		});

		$(".aimeos .list-log .log-message").on("mouseup", function(ev) {
			var el = $(this);

			if(this.time < (new Date()).getTime() - 500) {
				return false;
			}

			if(el.hasClass("show")) {
				el.removeClass("show");
			} else {
				el.addClass("show");
			}

			return false;
		});
	}
}


Aimeos.Menu = {
	init() {
		$("body").on("click", ".app-menu .menu", function(ev) {
			$(".main-sidebar").addClass("open");
			$(".app-menu").addClass("open");
		});

		$("body").on("click", ".app-menu.open .menu", function(ev) {
			$(".main-sidebar").removeClass("open");
			$(".app-menu").removeClass("open");
		});
	}
}



$(function() {

	// show toast notifications
	document.querySelectorAll('.toast').forEach(el => {
		new bootstrap.Toast(el, {delay: 3000}).show();
	});

	Aimeos.ckeditor.language = document.documentElement && document.documentElement.getAttribute('locale') || 'en';

	flatpickr.localize(flatpickr.l10ns[$('.aimeos').attr('locale') || 'en']);
	Vue.component('flat-pickr', VueFlatpickr);
	Vue.component('v-select', VueSelect.VueSelect);
	Vue.component('multiselect', VueformMultiselect);
	Vue.component('l-map', window.Vue2Leaflet.LMap);
	Vue.component('l-marker', window.Vue2Leaflet.LMarker);
	Vue.component('l-tile-layer', window.Vue2Leaflet.LTileLayer);

	$('.vue').each(function() {
		const key = $(this).data('key') || Math.floor(Math.random() * 1000);
		Aimeos.components[key] = Aimeos.vue(this);
	});

	Aimeos.Menu.init();
	Aimeos.Config.init();
	Aimeos.Form.init();
	Aimeos.List.init();
	Aimeos.Log.init();
	Aimeos.Nav.init();
	Aimeos.Tabs.init();
});


/**
 * Load JSON admin resource definition immediately
 */
Aimeos.options = $.ajax($(".aimeos").data("url"), {
	"method": "OPTIONS",
	"dataType": "json"
});
