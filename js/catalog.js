/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2025
 */


Aimeos.Catalog = {

	init() {
		Aimeos.Catalog.Basic.init()
	}
}



Aimeos.Catalog.Basic = {

	instance: null,


	init() {
		delete this.instance
		const node = document.querySelector(".aimeos .catalog .item-basic .box");

		if(node) {
			this.instance = Aimeos.app({mixins: [this.mixins]}, {...node.dataset || {}}).mount(node);
		}
	},


	mixins : {
		props: {
			data: {type: String, required: true},
			siteid: {type: String, required: true}
		},


		data() {
			return {
				item: {},
				show: false
			}
		},


		beforeMount() {
			this.Aimeos = Aimeos
			this.item = JSON.parse(this.data)
			this.show = Aimeos.session('aimeos/jqadm/item/form') == 1
		},


		methods: {
			can(action) {
				return Aimeos.can(action, this.item['catalog.siteid'] || null, this.siteid)
			},


			toggle() {
				this.show = Aimeos.session('aimeos/jqadm/item/form', +!this.show)
			}
		}
	}
}



Aimeos.Catalog.Tree = {

	instance: null,


	init() {
		delete this.instance
		const node = document.querySelector(".aimeos .catalog .tree-nodes")

		if(node) {
			this.instance = Aimeos.app({mixins: [this.mixins]}, {...node.dataset || {}}).mount(node)
		}
	},


	mixins : {
		props: {
			url: {type: String, required: true},
		},


		data() {
			return {
				current: null,
				rtl: false,
			}
		},


		beforeMount() {
			this.rtl = document.documentElement.getAttribute('dir') === 'rtl'
		},


		methods: {
			load(stat, ev) {
				ev.preventDefault()
				this.current = stat

				fetch(this.url.replace(/_id_/, stat.data.id)).then(response => {
					if(!response.ok) {
						throw response
					}
					return response.text()
				}).then(data => {
					this.update(data).init()
				}).catch(e => {
					console.error(e)
				})
			},


			init() {
				Aimeos.Form.help()

				for(const name of Object.getOwnPropertyNames(Aimeos.apps)) {
					delete Aimeos.apps[name]
				}

				for(const name of Object.getOwnPropertyNames(Aimeos)) {
					if(typeof Aimeos[name]?.init === 'function') {
						Aimeos[name].init()
					}
				}

				document.querySelectorAll('.vue').forEach(function(node) {
					const key = node.dataset.key || Math.floor(Math.random() * 1000);
					Aimeos.apps[key] = Aimeos.vue({...node.dataset || {}}).mount(node);
				})

				document.querySelectorAll('.toast').forEach(el => {
					new bootstrap.Toast(el, {delay: 3000}).show();
				});

				document.querySelector('.aimeos .catalog .tree-content .act-cancel')?.addEventListener('click', ev => {
					this.load(this.current, ev)
				})

				document.querySelector('.aimeos .catalog .tree-content form')?.addEventListener('submit', ev => {
					ev.preventDefault();
					this.save(ev.target)
				})
			},


			save(target) {
				fetch(target.action, {
					method: 'POST',
					body: new FormData(target)
				}).then(response => {
					if(!response.ok) {
						throw response
					}
					return response.text()
				}).then(data => {
					this.update(data).init()

					const item = Aimeos.Catalog.Basic.instance.$data.item;

					this.current.data.code = item['catalog.code']
					this.current.data.label = item['catalog.label']
					this.current.data.status = item['catalog.status']
				})
			},


			update(data) {
				const doc = new DOMParser().parseFromString(data, "text/html")
				const search = document.querySelector('.aimeos .catalog .tree-content')
				const replace = doc.querySelector('.aimeos .catalog .tree-content')

				if(search && replace) {
					search.replaceWith(replace)
				}

				const toastlist = document.querySelector('.aimeos .toast-list')
				const toastnew = doc.querySelector('.aimeos .toast-list')

				if(toastlist && toastnew) {
					toastlist.replaceWith(toastnew)
				}

				return this
			}
		}
	},
};



document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Catalog.init();
	Aimeos.Catalog.Tree.init()
});
