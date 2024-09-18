/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
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

	siteid: null,
	options: null,
	components: {},
	apps: {},

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
			allowInput: true,
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
			wrap: true
		},
		datetime: {
			// altFormat: 'M j, Y H:i K',
			allowInput: true,
			defaultDate: null,
			defaultHour: 0,
			enableTime: true,
			plugins: [new confirmDatePlugin({})],
			position: 'below',
			time_24hr: true,
			wrap: true
		},
		daterange: {
			allowInput: true,
			defaultDate: null,
			locale: {
				rangeSeparator: ' - '
			},
			mode: "range",
			position: 'below',
			wrap: true,
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
			allowInput: true,
			defaultDate: null,
			position: 'below',
			wrap: true
		},
		time: {
			allowInput: true,
			defaultHour: 0,
			enableTime: true,
			time_24hr: true,
			position: 'below',
			wrap: true
		}
	},


	app(config = {}, props = {}) {
		const app = createApp(config, props);

		app.use(CKEditor);
		app.component('flat-pickr', Flatpickr);
		app.component('multiselect', Multiselect);
		app.component('draggable', Draggable);
		app.component('l-map', LMap);
		app.component('l-marker', LMarker);
		app.component('l-tile-layer', LTileLayer);

		for(const key in Aimeos.components) {
			app.component(key, Aimeos.components[key]);
		}

		return app;
	},


	can(action, siteid, siteID) {
		if(action === 'match') {
			return siteid == siteID;
		}

		return (new String(siteid)).startsWith(siteID);
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


	query(gql, vars = {}) {
		const client = new AwesomeGraphQLClient({
			endpoint: $('.aimeos').data('graphql'),
			fetchOptions: {
				credentials: 'same-origin',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]')?.attr('content')
				}
			}
		})

		return client.request(gql, vars).catch(error => {
			console.error(error)
			throw new Error('GraphQL query failed')
		})
	},


	vue(props = {}) {
		return this.app({
			props: {
				data: {type: String, default: '[]'},
				siteid: {type: String, default: ''},
				domain: {type: String, default: ''},
			},
			data() {
				return {
					dataset: []
				}
			},
			computed: {
				prefix() {
					return this.domain ? this.domain.replace(/\//g, '.') + '.' : '';
				}
			},
			beforeMount() {
				this.Aimeos = Aimeos
				this.dataset = JSON.parse(this.data)
			},
			methods: {
				add: function(data) {
					data[this.prefix + 'siteid'] = this.siteid
					this.dataset.push(data)
				},

				can(action) {
					return Aimeos.can(action, this.dataset[this.prefix + 'siteid'] || '', this.siteid)
				},

				remove: function(idx) {
					this.dataset.splice(idx, 1);
				},

				set(data) {
					this.dataset = data
				},
			}
		}, props);
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

			$("input,select,textarea", this).each(function(idx, element) {
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
		const node = document.querySelector(".list-view");

		if(node) {
			this.instance = Aimeos.app({
				mixins: [this.mixins]
			}, {...node.dataset || {}}).mount(node);
		}
	},


	mixins : {
		props: {
			items: {type: String, default: '{}'},
			filter: {type: String, default: '{}'},
			domain: {type: String, default: ''},
			siteid: {type: String, default: ''},
		},
		data() {
			return {
				all: false,
				batch: false,
				columns: false,
				dialog: false,
				entries: {},
				filters: {},
				search: false,
				states: {}
			}
		},
		beforeMount() {
			this.Aimeos = Aimeos;
			this.entries = JSON.parse(this.items);
			this.filters = JSON.parse(this.filter);
			this.filters['val'] = this.filters['val'] || {}
		},
		computed: {
			prefix() {
				return this.domain ? this.domain.replace(/\//g, '.') + '.' : ''
			},

			selected() {
				let count = 0;

				for(const key in this.entries) {
					if(this.entries[key].checked) {
						count++;
					}
				}

				return count;
			},

			unconfirmed() {
				let list = {};
				for(const key in this.entries) {
					if(this.entries[key].checked) {
						list[key] = this.entries[key][this.prefix + 'label'] || this.entries[key][this.prefix + 'code'] || this.entries[key][this.prefix + 'id'];
					}
				}

				return list;
			}
		},
		methods: {
			askDelete(id, ev) {
				if(id) {
					this.clear(false);
					this.entries[id]['checked'] = true;
				}

				this.deleteUrl = ev.target.href;
				this.dialog = true;
			},

			checked(id) {
				return this.entries[id] && this.entries[id].checked;
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
				for(const key in this.entries) {
					if([this.siteid, ''].includes(this.entries[key][this.prefix + 'siteid'])) {
						this.entries[key]['checked'] = val;
					}
				};
			},

			readonly(id) {
				return !(this.entries[id] && this.entries[id][this.prefix + 'siteid'] == this.siteid);
			},

			reset() {
				this.filters['val'] = {}
			},

			setState(key) {
				this.states[key] = !this.states[key];
			},

			state(key) {
				return !(this.states[key] || false);
			},

			toggle(id) {
				this.entries[id]['checked'] = !this.entries[id].checked;
			},

			toggleAll() {
				this.clear(this.all = !this.all);
			},

			update(idx, val) {
				this.filters['val'][idx] = val;
			},

			upload() {
				this.$refs.import.click()
			},

			value(idx) {
				return this.filters['val'] && this.filters['val'][idx] ? this.filters['val'][idx] : null;
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
		this.setupTabSwitch();
	},


	setupTabSwitch() {
		const self = this
		const hash = document.location.hash

		$('.nav-tabs a[href="' + hash + '"]').tab('show')
		self.toForm(hash)

		$('.nav-tabs a').on('shown.bs.tab', function (e) {
			self.toState(e.target.hash)
			self.toForm(e.target.hash)
		})
	},


	toForm(hash) {
		if(hash) {
			$("form").each(function() {
				if($(this).attr("action") !== undefined) {
					$(this).attr("action", $(this).attr("action").split('#')[0] + hash)
				}
			})
		}
	},


	toState(hash) {
		if(hash) {
			if(history.pushState) {
				history.pushState(null, null, hash)
			}
		}
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
};


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
};


document.addEventListener("DOMContentLoaded", function() {
	// show toast notifications
	document.querySelectorAll('.toast').forEach(el => {
		new bootstrap.Toast(el, {delay: 3000}).show();
	});

	Aimeos.siteid = $('.aimeos').data('user-siteid') || '';
	Aimeos.ckeditor.language = document.documentElement && document.documentElement.getAttribute('locale') || 'en';

	flatpickr.localize(FlatpickrL10n[$('.aimeos').attr('locale') || 'en']);

	$('.vue').each(function(idx, node) {
		const key = $(this).data('key') || Math.floor(Math.random() * 1000);
		Aimeos.apps[key] = Aimeos.vue({...node.dataset || {}}).mount(node);
	});

	Aimeos.Menu.init();
	Aimeos.Form.init();
	Aimeos.List.init();
	Aimeos.Log.init();
	Aimeos.Nav.init();
	Aimeos.Tabs.init();
});


/**
 * Load JSON admin resource definition immediately
 * @deprecated Use GraphQL API
 */
Aimeos.options = fetch($(".aimeos").data("url"), {
	"method": "OPTIONS"
}).then(function(response) {
	if(!response.ok) {
		throw new Error(response.statusText);
	}
	return response.json();
});
