/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 */

/* Check for preferred theme mode (dark/light) */
const prefersDark = window.matchMedia("(prefers-color-scheme: dark)");
const isLight = document.cookie.includes('aimeos_backend_theme=light');

//Light by default (based on View used) - checks for Dark preference (by browser, or cookie)
if (prefersDark.matches && !isLight){
	['light', 'dark'].map(cl => document.body.classList.toggle(cl));
	Aimeos.theme(document.body.classList.contains("dark") ? "dark" : "light")
}

document.querySelectorAll(".btn-theme").forEach(item => {
	item.addEventListener("click", function() {
		['light', 'dark'].map(cl => document.body.classList.toggle(cl));
		Aimeos.theme(document.body.classList.contains("dark") ? "dark" : "light")
	});
});



Aimeos = {

	apps: {},
	components: {},
	options: null,
	siteid: document.querySelector('.aimeos')?.dataset['user-siteid'] || '',

	ckeditor: {
		htmlSupport: {
			allow: [{
				name: /div|p|span/,
				classes: true
			}],
			disallow: []
		},
		language: document.querySelector('html').getAttribute('lang') || 'en',
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

			document.querySelectorAll(selector).forEach(function(item) {
				(new IntersectionObserver(callback, {})).observe(item);
			});

		} else if(document.querySelectorAll(selector).length) {
			renderFcn();
		}
	},


	query(gql, vars = {}) {
		const client = new AwesomeGraphQLClient({
			endpoint: document.querySelector('.aimeos')?.dataset.graphql,
			fetchOptions: {
				credentials: 'same-origin',
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
				}
			}
		})

		return client.request(gql, vars).catch(error => {
			console.error(error)
			throw new Error('GraphQL query failed')
		})
	},


	session(key, val = null) {
		if(window.sessionStorage) {
			if(val === null) {
				return window.sessionStorage.getItem(key)
			} else {
				window.sessionStorage.setItem(key, val)
			}
		}

		return val
	},


	theme(name) {
		const cookieName = "aimeos_backend_theme"
		const expires = "expires=" + (new Date((new Date()).getTime() + (7*84600000))).toUTCString(); // 7 days (Safari does not allow for more)
		document.cookie = cookieName + "=" + name + ";" + expires + ";path=/";
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



document.addEventListener("DOMContentLoaded", function() {

	flatpickr.localize(FlatpickrL10n[document.querySelector('.aimeos')?.dataset.locale || 'en']);

	document.querySelectorAll('.vue').forEach(function(node) {
		const key = node.dataset.key || Math.floor(Math.random() * 1000);
		Aimeos.apps[key] = Aimeos.vue({...node.dataset || {}}).mount(node);
	});

	// show toast notifications
	document.querySelectorAll('.toast').forEach(el => {
		new bootstrap.Toast(el, {delay: 3000}).show();
	});
});


/**
 * Load JSON admin resource definition immediately
 * @deprecated Use GraphQL API
 */
Aimeos.options = fetch(document.querySelector('.aimeos').dataset.url, {
	"method": "OPTIONS"
}).then(function(response) {
	if(!response.ok) {
		throw new Error(response.statusText);
	}
	return response.json();
});
