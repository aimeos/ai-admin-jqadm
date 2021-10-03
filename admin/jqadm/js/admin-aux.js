/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



$(function() {

	Aimeos.ProductRef.init();
	Aimeos.Address.init();
	Aimeos.Media.init();
	Aimeos.Price.init();
	Aimeos.Text.init();
});



Aimeos.Address = {

	init: function() {

		Aimeos.components['address'] = new Vue({
			el: '#item-address-group',
			data: {
				items: [],
				siteid: null,
				domain: null,
				show: false
			},
			mounted: function() {
				this.Aimeos = Aimeos;
				this.items = JSON.parse(this.$el.dataset.items || '{}');
				this.siteid = this.$el.dataset.siteid;
				this.domain = this.$el.dataset.domain;

				if(this.items[0]) {
					this.$set(this.items[0], '_show', true);
				}

				const self = this;
				Aimeos.lazy(this.$el, function() {
					self.show = true;
				});
			},
			mixins: [this.mixins]
		});
	},


	mixins: {
		methods: {
			add : function() {
				const entry = {};

				entry[this.domain + '.address.siteid'] = this.siteid;
				entry[this.domain + '.address.id'] = null;
				entry[this.domain + '.address.title'] = null;
				entry[this.domain + '.address.salutation'] = null;
				entry[this.domain + '.address.firstname'] = null;
				entry[this.domain + '.address.lastname'] = null;
				entry[this.domain + '.address.address1'] = null;
				entry[this.domain + '.address.address2'] = null;
				entry[this.domain + '.address.address3'] = null;
				entry[this.domain + '.address.postal'] = null;
				entry[this.domain + '.address.city'] = null;
				entry[this.domain + '.address.state'] = null;
				entry[this.domain + '.address.countryid'] = '';
				entry[this.domain + '.address.languageid'] = null;
				entry[this.domain + '.address.email'] = null;
				entry[this.domain + '.address.website'] = null;
				entry[this.domain + '.address.telephone'] = null;
				entry[this.domain + '.address.telefax'] = null;
				entry[this.domain + '.address.latitude'] = null;
				entry[this.domain + '.address.longitude'] = null;
				entry[this.domain + '.address.company'] = null;
				entry[this.domain + '.address.vatid'] = null;

				entry['_show'] = true;

				this.items.push(entry);
			},


			/* @deprecated 2022.01 */
			countries : function() {
				return Aimeos.getCountries;
			},


			duplicate : function(idx) {
				if(this.items[idx]) {
					this.$set(this.items, this.items.length, JSON.parse(JSON.stringify(this.items[idx])));
				}
			},


			remove : function(idx) {
				if(this.items[idx]) {
					this.items.splice(idx, 1);
				}
			},


			label : function(idx) {
				let label = '', addr = '';

				if(this.items[idx]) {
					label += (this.items[idx][this.domain + '.address.firstname'] ? this.items[idx][this.domain + '.address.firstname'] + ' ' : '');
					label += (this.items[idx][this.domain + '.address.lastname'] ? this.items[idx][this.domain + '.address.lastname'] : '');

					addr += (this.items[idx][this.domain + '.address.postal'] ? ' ' + this.items[idx][this.domain + '.address.postal'] : '');
					addr += (this.items[idx][this.domain + '.address.city'] ? ' ' + this.items[idx][this.domain + '.address.city'] : '');
				}

				if(addr && label) {
					return label + ' -' + addr;
				}

				return label + ' ' + addr;
			},


			toggle: function(what, idx) {
				if(this.items[idx]) {
					this.$set(this.items[idx], what, (!this.items[idx][what] ? true : false));
				}
			},


			point(entry) {
				return [entry[this.domain + '.address.latitude'] || 0, entry[this.domain + '.address.longitude'] || 0];
			},


			setPoint(idx, ev) {
				const map = this.$refs.map[0].mapObject;

				map.getZoom() > 2 ? null : map.setZoom(8);
				map.panTo(ev.latlng);

				this.$set(this.items[idx], this.domain + '.address.latitude', ev.latlng.lat || null);
				this.$set(this.items[idx], this.domain + '.address.longitude', ev.latlng.lng || null);
			},


			zoom(idx) {
				return this.items[idx][this.domain + '.address.latitude'] && this.items[idx][this.domain + '.address.longitude'] ? 8 : 2;
			}
		}
	}
};



Aimeos.Media = {

	init: function() {

		Aimeos.components['media'] = new Vue({
			el: '#item-media-group',
			data: {
				items: [],
				siteid: null,
				domain: null
			},
			mounted: function() {
				this.Aimeos = Aimeos;
				this.items = JSON.parse(this.$el.dataset.items || '{}');
				this.siteid = this.$el.dataset.siteid;
				this.domain = this.$el.dataset.domain;

				if(this.items[0]) {
					this.$set(this.items[0], '_show', true);
				}
			},
			mixins: [this.mixins]
		});
	},

	mixins: {
		methods: {
			active: function(idx) {
				return this.items[idx] && this.items[idx]['media.status'] > 0;
			},


			add: function() {
				const entry = {};

				entry[this.domain + '.lists.id'] = null;
				entry[this.domain + '.lists.type'] = 'default';
				entry[this.domain + '.lists.siteid'] = this.siteid;
				entry[this.domain + '.lists.datestart'] = null;
				entry[this.domain + '.lists.dateend'] = null;

				entry['media.id'] = null;
				entry['media.label'] = null;
				entry['media.type'] = 'default';
				entry['media.siteid'] = this.siteid;
				entry['media.languageid'] = null;
				entry['media.mimetype'] = null;
				entry['media.preview'] = null;
				entry['media.url'] = null;
				entry['media.status'] = 1;

				entry['property'] = [];
				entry['config'] = [];
				entry['_show'] = true;
				entry['_nosort'] = true;

				this.items.push(entry);
			},


			create: function(ev) {
				const self = this;
				const len = ev.target.files.length;

				for(let i = 0; i < len; i++) {
					this.add();
				}

				Vue.nextTick(function() {
					for(let i = 0; i < len; i++) {
						const dt = new DataTransfer();
						const idx = self.items.length - len + i;

						dt.items.add(ev.target.files[i]);
						self.$refs.file[idx].files = dt.files;
						self.files(idx, dt.files);
					}
				});
			},


			files: function(idx, files) {

				if(!files.length) {
					return;
				}

				const self = this;
				let cnt = sum = 0;

				for(let i=0; i<files.length; i++) {
					self.$set(self.items[idx], 'media.mimetype', files[i].type);

					if(files[i].type.startsWith('image/')) {
						self.$set(self.items[idx], 'media.preview', URL.createObjectURL(files[i]));
					} else if(files[i].type.startsWith('video/')) {
						const video = document.createElement('video');

						video.addEventListener('canplay', function(e) {
							video.currentTime = video.duration / 2;
							e.target.removeEventListener(e.type, arguments.callee);
						});

						video.addEventListener('seeked', function() {
							const canvas = document.createElement('canvas');
							const context = canvas.getContext('2d');

							canvas.width = video.videoWidth;
							canvas.height = video.videoHeight;

							context.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
							self.$set(self.items[idx], 'media.preview', canvas.toDataURL());

							canvas.toBlob(function(blob) {
								const container = new DataTransfer();
								const preview = self.$refs.preview[idx];

								container.items.add(new File([blob], files[i].name, {
									type: 'image/png', lastModified: new Date().getTime()
								}));
								preview.files = container.files;
							});
						});

						video.src = URL.createObjectURL(files[i]);
						video.load();
					}

					sum += files[i].size;
					cnt++;
				}

				if($("#problem .file_uploads").data("value") != 1) {
					$("#problem .file_uploads").show();
					$("#problem").modal("show");
				}

				if(sum > $("#problem .post_max_size").data("value")) {
					$("#problem .upload_max_filesize").show();
					$("#problem").modal("show");
				}

				if(cnt > $("#problem .max_file_uploads").data("value")) {
					$("#problem .max_file_uploads").show();
					$("#problem").modal("show");
				}

				for(let i=0; i<files.length; i++) {
					if(files[i].size > $("#problem .upload_max_filesize").data("value")) {
						$("#problem .upload_max_filesize").show();
						$("#problem").modal("show");
					}
				}

				this.$set(this.items[idx], 'media.label', files[0].name);
			},


			label: function(idx) {
				let label = '';

				if(this.items[idx]) {
					label += (this.items[idx]['media.languageid'] ? this.items[idx]['media.languageid'] + ': ' : '');
					label += (this.items[idx]['media.label'] ? this.items[idx]['media.label'] : '');
					label += (this.items[idx]['media.type'] ? ' (' + this.items[idx]['media.type'] + ')' : '');
				}

				return label;
			},


			remove: function(idx) {
				if(this.items[idx]) {
					this.items.splice(idx, 1);
				}
			},


			toggle: function(what, idx) {
				if(this.items[idx]) {
					this.$set(this.items[idx], what, (!this.items[idx][what] ? true : false));
				}
			},


			url: function(prefix, url) {
				const str = url ? url.substr(0, 4) : '';
				return (str === 'http' || str === 'data' || str === 'blob' ? url : prefix + url);
			}
		}
	}
};



Aimeos.Price = {

	init: function() {

		Aimeos.components['price'] = new Vue({
			el: '#item-price-group',
			data: {
				items: [],
				siteid: null,
				domain: null
			},
			mounted: function() {
				this.Aimeos = Aimeos;
				this.items = JSON.parse(this.$el.dataset.items || '{}');
				this.siteid = this.$el.dataset.siteid;
				this.domain = this.$el.dataset.domain;

				if(this.items[0]) {
					this.$set(this.items[0], '_show', true);
				}
			},
			mixins: [this.mixins]
		});
	},

	mixins: {
		methods: {
			active: function(idx) {
				return this.items[idx] && this.items[idx]['price.status'] > 0;
			},


			add: function(data) {
				const entry = {};

				entry[this.domain + '.lists.id'] = null;
				entry[this.domain + '.lists.type'] = 'default';
				entry[this.domain + '.lists.siteid'] = this.siteid;
				entry[this.domain + '.lists.datestart'] = null;
				entry[this.domain + '.lists.dateend'] = null;

				entry['price.id'] = null;
				entry['price.label'] = null;
				entry['price.type'] = 'default';
				entry['price.siteid'] = this.siteid;
				entry['price.taxrates'] = {'': ''};
				entry['price.currencyid'] = null;
				entry['price.rebate'] = '0.00';
				entry['price.costs'] = '0.00';
				entry['price.value'] = null;
				entry['price.quantity'] = 1;
				entry['price.status'] = 1;

				entry['property'] = [];
				entry['config'] = [];
				entry['_show'] = true;
				entry['_nosort'] = true;

				this.items.push(Object.assign(entry, data));
			},


			label: function(idx) {
				let label = '';

				if(this.items[idx]) {
					label += (this.items[idx]['price.quantity'] ? this.items[idx]['price.quantity'] + ' ~ ' : '');
					label += (this.items[idx]['price.value'] ? this.items[idx]['price.value'] : '');
					label += (this.items[idx]['price.costs'] ? ' + ' + this.items[idx]['price.costs'] : '');
					label += (this.items[idx]['price.currencyid'] ? ' ' + this.items[idx]['price.currencyid'] : '');
					label += (this.items[idx]['price.type'] ? ' (' + this.items[idx]['price.type'] + ')' : '');
				}

				return label;
			},


			remove: function(idx) {
				this.items.splice(idx, 1);
			},


			toggle: function(what, idx) {
				if(this.items[idx]) {
					this.$set(this.items[idx], what, (!this.items[idx][what] ? true : false));
				}
			}
		}
	}
};



Aimeos.ProductRef = {

	init: function() {

		const self = this;
		const node = document.querySelector('.item-product .productref-list');

		if(node) {
			Aimeos.components['productref'] = new Vue({
				'el': node,
				'mixins': [Aimeos.ProductRef.mixins]
			});
		}

		Aimeos.lazy('.item-product .productref-list', function() {
			Aimeos.components['productref'] && Aimeos.components['productref'].reset();
		});
	},


	mixins: {
		'data': function() {
			return {
				'parentid': null,
				'siteid': '',
				'resource': '',
				'items': [],
				'fields': [],
				'filter': {},
				'offset': 0,
				'limit': 25,
				'total': 0,
				'order': '',
				'types': {},
				'options': [],
				'colselect': false,
				'checked': false,
				'loading': true
			}
		},


		beforeMount: function() {
			this.Aimeos = Aimeos;
			try {
				if(!this.$el.dataset) {
					throw 'Missing "data" attributes';
				}
				if(!this.$el.dataset.types) {
					throw 'Missing "data-types" attribute';
				}
				if(!this.$el.dataset.siteid) {
					throw 'Missing "data-siteid" attribute';
				}
				if(!this.$el.dataset.parentid) {
					throw 'Missing "data-parentid" attribute';
				}
				if(!this.$el.dataset.resource) {
					throw 'Missing "data-resource" attribute';
				}

				this.siteid = this.$el.dataset.siteid;
				this.parentid = this.$el.dataset.parentid;
				this.resource = this.$el.dataset.resource;
				this.types = JSON.parse(this.$el.dataset.types);
				this.order = this.prefix + 'position';

				const fieldkey = 'aimeos/jqadm/' + this.resource.replace('/', '') + '/fields';
				this.fields = this.columns(this.$el.dataset.fields || [], fieldkey);
			} catch(e) {
				console.log( '[Aimeos] Init referenced product list failed: ' + e);
			}
		},


		computed: {
			prefix : function() {
				return this.resource.replace('/', '.') + '.';
			}
		},


		methods: {
			add: function() {
				const obj = {};

				obj[this.prefix + 'id'] = null;
				obj[this.prefix + 'siteid'] = this.siteid;
				obj[this.prefix + 'position'] = 0;
				obj[this.prefix + 'status'] = 1;
				obj[this.prefix + 'type'] = 'default';
				obj[this.prefix + 'config'] = {};
				obj[this.prefix + 'datestart'] = null;
				obj[this.prefix + 'dateend'] = null;
				obj[this.prefix + 'refid'] = null;
				obj['edit'] = true;

				this.items.unshift(obj);
				return this;
			},


			columns: function(json, key) {
				let list = [];
				try {
					if(window.sessionStorage) {
						list = JSON.parse(window.sessionStorage.getItem(key)) || [];
					}
					if(!list.length) {
						list = JSON.parse(json);
					}
				} catch(e) {
					console.log('[Aimeos] Failed to get list of columns: ' + e);
				}
				return list;
			},


			css: function(key) {
				return this.resource.replace('/', '-') + '-' + key;
			},


			delete: function(resource, id, callback) {

				const self = this;
				self.waiting(true);

				Aimeos.options.done(function(response) {

					if(response.meta && response.meta.resources && response.meta.resources[resource] ) {

						const config = {};

						if(response.meta.prefix && response.meta.prefix) {
							config['params'][response.meta.prefix] = {'id': id};
						} else {
							config['params'] = {'id': id};
						}

						axios.delete(response.meta.resources[resource], config).then(function(response) {
							callback ? callback(response.data) : null;
						}).then(function() {
							self.waiting(false);
						});
					}
				});

				return this;
			},


			edit: function(idx) {
				if(this.siteid === this.items[idx][this.prefix + 'siteid']) {
					this.$set(this.items[idx], 'edit', true);
				}
				return this;
			},


			find: function(ev, key, op) {
				const value = ev.target ? ev.target.value : ev;
				if(value) {
					const expr = {};
					expr[op || '=='] = {};
					expr[op || '=='][this.prefix + key] = value;
					this.$set(this.filter, this.prefix + key, expr);
				} else {
					this.$delete(this.filter, this.prefix + key);
				}
				return this.fetch();
			},


			fetch: function() {
				const self = this;
				const args = {
					'filter': {'&&': []},
					'fields': {},
					'page': {'offset': self.offset, 'limit': self.limit},
					'sort': self.order
				};

				for(let key in self.filter) {
					args['filter']['&&'].push(self.filter[key]);
				}

				if(this.fields.includes(this.prefix + 'refid')) {
					args.fields['product'] = ['product.id', 'product.code', 'product.label', 'product.status'];
				}
				args.fields[this.resource] = [self.prefix + 'id', self.prefix + 'siteid', ...self.fields];

				this.get(self.resource, args, function(data) {
					self.total = data.total || 0;
					self.items = data.items || [];
				});

				return this;
			},


			get: function(resource, args, callback) {

				const self = this;
				self.waiting(true);

				Aimeos.options.done(function(response) {

					if(response.meta && response.meta.resources && response.meta.resources[resource] ) {

						if(args.fields) {
							const include = [];
							for(let key in args.fields) {
								args.fields[key] = args.fields[key].join(',');
								include.push(key);
							}
							args['include'] = include.join(',');
						}

						const config = {
							'paramsSerializer': function(params) {
								return jQuery.param(params); // workaround, Axios and QS fail on [==]
							},
							'params': {}
						};

						if(response.meta.prefix && response.meta.prefix) {
							config['params'][response.meta.prefix] = args;
						} else {
							config['params'] = args;
						}

						axios.get(response.meta.resources[resource], config).then(function(response) {
							const list = [];
							const included = {};

							(response.data.included || []).forEach(function(entry) {
								if(!included[entry.type]) {
									included[entry.type] = {};
								}
								included[entry.type][entry.id] = entry;
							});

							(response.data.data || []).forEach(function(entry) {
								for(let type in (entry.relationships || {})) {
									const relitem = entry.relationships[type]['data'] && entry.relationships[type]['data'][0] || null;
									if(relitem && relitem['id'] && included[type][relitem['id']]) {
										Object.assign(entry['attributes'], included[type][relitem['id']]['attributes'] || {});
									}
								}
								list.push(entry.attributes || {});
							});

							callback({
								total: response.data.meta ? response.data.meta.total || 0 : 0,
								items: list
							});

						}).then(function() {
							self.waiting(false);
						});
					}
				});

				return this;
			},


			label: function(idx) {
				let str = '';

				if(this.items[idx]) {
					if(this.items[idx][this.prefix + 'refid']) {
						str += this.items[idx][this.prefix + 'refid'];
					}

					if(this.items[idx]['product.label']) {
						str += ' - ' + this.items[idx]['product.label'];
					}

					if(this.items[idx]['product.code']) {
						str += ' (' + this.items[idx]['product.code'] + ')';
					}
				}

				return str;
			},


			remove: function(idx) {
				const self = this;
				this.checked = false;

				if(idx !== undefined) {
					this.delete(this.resource, this.items[idx][this.prefix + 'id'], () => self.waiting(false));
					return this.items.splice(idx, 1);
				}

				this.items = this.items.filter(function(item) {
					if(item.checked) {
						self.delete(self.resource, item[self.prefix + 'id']);
					}
					return !item.checked;
				});

				return this.waiting(false);
			},


			reset: function() {
				const domain = {};
				const parentid = {};

				domain[this.prefix + 'domain'] = 'product';
				parentid[this.prefix + 'parentid'] = this.parentid;

				Object.assign(this.$data, {filter: {'base': {'&&': [{'==': parentid}, {'==': domain}]}}});
				return this.fetch();
			},


			sort: function(key) {
				this.order = this.order === this.prefix + key ? '-' + this.prefix + key : this.prefix + key;
				return this.fetch();
			},


			sortclass: function(key) {
				return this.order === this.prefix + key ? 'sort-desc' : (this.order === '-' + this.prefix + key ? 'sort-asc' : '');
			},


			status: function(map, val) {
				return map[val] || val;
			},

			stringify: function(value) {
				return typeof value === 'object' || typeof value === 'array' ? JSON.stringify(value) : value;
			},


			suggest: function(input, loadfcn) {
				const self = this;
				const args = {
					'filter': {'||': [
						{'==': {'product.id': input}},
						{'=~': {'product.code': input}},
						{'=~': {'product.label': input}}
					]},
					'fields': {'product': ['product.id', 'product.code', 'product.label']},
					'page': {'offset': 0, 'limit': 25},
					'sort': 'product.label'
				};

				try {
					loadfcn ? loadfcn(true) : null;

					this.get('product', args, function(data) {
						self.options = [];
						(data.items || []).forEach(function(entry) {
							self.options.push({
								'id': entry['product.id'],
								'label': entry['product.id'] + ' - ' + entry['product.label'] + ' (' + entry['product.code'] + ')'
							});
						});
					});
				} finally {
					loadfcn ? loadfcn(false) : null;
				}
			},


			toggle: function(fields) {
				this.fields = fields;

				if(window.sessionStorage) {
					window.sessionStorage.setItem(
						'aimeos/jqadm/' + this.resource.replace('/', '') + '/fields',
						JSON.stringify(this.fields)
					);
				}

				return this.fetch();
			},


			value: function(key) {
				const op = Object.keys(this.filter[this.prefix + key] || {}).pop();
				return this.filter[this.prefix + key] && this.filter[this.prefix + key][op][this.prefix + key] || '';
			},


			waiting: function(val) {
				this.loading = val;
				return this;
			}
		},


		watch: {
			checked: function() {
				for(let item of this.items) {
					this.$set(item, 'checked', this.checked);
				}
			},


			limit: function() {
				this.fetch();
			},


			offset: function() {
				this.fetch();
			}
		}
	}
};


Aimeos.Text = {

	init: function() {

		Aimeos.components['text'] = new Vue({
			el: '#item-text-group',
			data: {
				items: [],
				siteid: null,
				domain: null
			},
			mounted: function() {
				this.Aimeos = Aimeos;
				this.CKEditor = ClassicEditor;
				this.items = JSON.parse(this.$el.dataset.items || '{}');
				this.siteid = this.$el.dataset.siteid;
				this.domain = this.$el.dataset.domain;

				if(this.items[0]) {
					this.$set(this.items[0], '_show', true);
				}
			},
			mixins: [this.mixins]
		});
	},

	mixins: {
		methods: {
			active: function(idx) {
				return this.items[idx] && this.items[idx]['text.status'] > 0;
			},


			add: function(data) {
				const entry = {};

				entry[this.domain + '.lists.id'] = null;
				entry[this.domain + '.lists.type'] = 'default';
				entry[this.domain + '.lists.siteid'] = this.siteid;
				entry[this.domain + '.lists.datestart'] = null;
				entry[this.domain + '.lists.dateend'] = null;

				entry['text.id'] = null;
				entry['text.type'] = null;
				entry['text.languageid'] = '';
				entry['text.siteid'] = this.siteid;
				entry['text.content'] = '';
				entry['text.label'] = '';
				entry['text.status'] = 1;

				entry['property'] = [];
				entry['config'] = [];
				entry['_show'] = true;
				entry['_nosort'] = true;

				this.items.push(Object.assign(entry, data));
			},


			label: function(idx) {
				let label = '';

				if(this.items[idx]) {
					label += (this.items[idx]['text.languageid'] ? this.items[idx]['text.languageid'].toUpperCase() : '');
					label += (this.items[idx]['text.type'] ? ' (' + this.items[idx]['text.type'] + ')' : '');

					if(this.items[idx]['text.label']) {
						label += ': ' + this.items[idx]['text.label'].substr(0, 40);
					} else if(this.items[idx]['text.content']) {
						const tmp = document.createElement("span");
						tmp.innerHTML = this.items[idx]['text.content'];
						label += ': ' + (tmp.innerText || tmp.textContent || "").substr(0, 40);
					}
				}

				return label;
			},


			remove: function(idx) {
				if(this.items[idx]) {
					this.items.splice(idx, 1);
				}
			},


			toggle: function(what, idx) {
				if(this.items[idx]) {
					this.$set(this.items[idx], what, (!this.items[idx][what] ? true : false));
				}
			},


			translate : function(idx, langid) {

				if(!this.items[idx]) {
					return;
				}

				if(!this.$el.dataset.translate) {
					alert('No translation service configured');
					return;
				}

				config = JSON.parse(this.$el.dataset.translate);

				if(!config['url']) {
					alert('No translation URL for DeepL configured');
					return;
				}

				if(!config['key']) {
					alert('No translation credentials for DeepL configured');
					return;
				}

				const self = this;
				const data = {
					'auth_key': config['key'],
					'text' : this.items[idx]['text.content'],
					'target_lang' : langid.toUpperCase()
				};

				if(this.items[idx]['text.languageid']) {
					data['source_lang'] = this.items[idx]['text.languageid'].toUpperCase();
				}


				$.getJSON(config['url'] + '/translate', data).done(function(data) {
					self.add({
						'text.content': data['translations'] && data['translations'][0] && data['translations'][0]['text'] || '',
						'text.languageid': langid.toLowerCase()
					});
				}).fail(function(jqxhr, status, error) {
					let msg = '';

					switch(jqxhr.status) {
						case 200: break;
						case 400: msg = 'Bad request: ' + error; break;
						case 403: msg = 'Invalid DeepL API token'; break;
						case 413: msg = 'The text size exceeds the limit'; break;
						case 429: msg = 'Too many requests. Please wait and resend your request.'; break;
						case 456: msg = 'Quota exceeded. The character limit has been reached.'; break;
						case 503: msg = 'Resource currently unavailable. Try again later.'; break;
						default: msg = 'Unexpected response code: ' + jqxhr.status;
					}

					alert(msg);
				});
			}
		}
	}
};
