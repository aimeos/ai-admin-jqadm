/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2025
 */


Aimeos.components['catalog-tree'] = {
    components: { DragTree },

	template: `
	<div class="catalog-tree">
		<div class="tree-menu-filter">
			<div class="filter tree-toolbar">
				<input class="form-control" v-model="input" :placeholder="placeholder" />
			</div>
		</div>
		<DragTree class="tree"
			ref="tree"
			v-model="tree"
			:beforeDragOpen="toggle"
			:defaultOpen="false"
			:disableDrag="readonly"
			:dragOpen="true"
			:eachDraggable="can"
			:rtl="rtl"
			:treeLine="true"
			:watermark="false"
			virtualization
			@change="move()"
		>
			<template #default="{ node, stat }">
				<div v-if="can(stat) && !readonly" class="dropdown">
					<button type="button" class="icon icon-treemenu dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="sr-only">{{ i18n.menu || 'Tree node menu' }}</span>
					</button>
					<ul class="dropdown-menu dropdown-menu-end">
						<li class="dropdown-item"><a class="action" href="#" @click="insert(stat, $event, -1)">{{ i18n.before || 'Add before' }}</a></li>
						<li class="dropdown-item"><a class="action" href="#" @click="insert(stat, $event, 0)">{{ i18n.insert || 'Insert into' }}</a></li>
						<li class="dropdown-item"><a class="action" href="#" @click="insert(stat, $event, 1)">{{ i18n.after || 'Add after' }}</a></li>
						<li class="dropdown-item"><a class="action" href="#" @click="ask(stat, $event)">{{ i18n.delete || 'Delete' }}</a></li>
					</ul>
				</div>
				<a class="label" :class="'node-status-' + node.status" href="#" @click="load(stat, $event)">
					<span v-if="node._more" class="icon icon-treemore"></span>
					{{ node.label || '' }}
				</a>
				<span class="icon"
					:class="{
						'icon-none': !children(stat),
						'icon-open': children(stat) && !stat.open,
						'icon-close': children(stat) && stat.open,
						'icon-loading': stat.loading
					}"
					@click="toggle(stat)">
				</span>
			</template>
		</DragTree>
		<confirm-delete
			v-bind:items="unconfirmed" v-bind:show="dialog"
			v-on:close="confirm(false)" v-on:confirm="confirm(true)">
		</confirm-delete>
	</div>
	`,

	props: {
		i18n: {type: Object, default: () => ({})},
		limit: {type: Number, default: 100},
		placeholder: {type: String, default: 'Find category'},
		readonly: {type: Boolean, default: false},
		rtl: {type: Boolean, default: false},
		siteid: {type: String, default: ''},
	},

	emits: ['load'],

    data() {
		return {
		  tree: [],
		  input: '',
		  timer: null,
		  dialog: false,
		  selected: null,
		  unconfirmed: [],
		}
	},


	mounted() {
		this.init()
		this.search = this.debounce(this.search, 300)
	},


	methods: {
		ask(stat, ev) {
			ev.preventDefault()

			this.unconfirmed = [stat.data.label]
			this.selected = stat
			this.dialog = true
		},


		confirm(value) {
			if(value) {
				this.drop(this.selected)
			}
			this.unconfirmed = []
			this.selected = null
			this.dialog = false
		},


		can(stat) {
			return Aimeos.can('change', stat.data['siteid'] || null, this.siteid)
		},


		children(stat) {
			return stat.children?.length || stat.data?.hasChildren
		},


		debounce(func, delay) {
			return function() {
				const context = this;
				const args = arguments;

				clearTimeout(this.timer);
				this.timer = setTimeout(() => func.apply(context, args), delay);
			};
		},


		drop(stat) {
			Aimeos.graphql(`mutation {
				deleteCatalog(id: "${stat.data.id}")
			}`).then(() => {
				this.$refs.tree.remove(stat)
			}).then(() => {
				if(stat.parent && !stat.parent.children?.length) {
					stat.parent.open = false
				}
			})
		},


		fetch(stat = null) {
			let filter = {'==': {'catalog.parentid': stat?.data?.id || 0}};
			stat ? stat.loading = true : null

			return Aimeos.graphql(`query {
				searchCatalogs(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, sort: ["sort:catalog:position"], offset: ${stat?.data?._offset || 0}) {
					items {
						id
						siteid
						code
						label
						status
						hasChildren
					}
					total
				}
			}`).then(result => {
				const items = result?.searchCatalogs?.items || []
				const length = stat?.data?._offset || (stat?.children ? stat.children.length : this.tree.length)

				if(length + items.length < (result?.searchCatalogs?.total || 0)) {
					items.push({id: stat?.data?.id || 0, _more: true, _offset: length + items.length})
				}

				return items
			}).finally(function() {
				stat ? stat.loading = false : null
			})
		},


		init() {
			this.fetch().then(items => {
				this.tree = items
			})
		},


		insert(stat, ev, where) {
			ev.preventDefault()

			let parent, ref
			let idx = stat.parent?.children?.findIndex(item => item.data.id == stat.data.id) || 0

			if(where) {
				ref = stat.parent?.children?.at(Math.max(idx + where, 0)) || null
				parent = stat.parent || null
			} else {
				parent = stat
				ref = null
			}

			const parentid = parent?.data?.id ? '"' + parent?.data?.id + '"' : null
			const refid = ref?.data?.id ? '"' + ref?.data?.id + '"' : null

			Aimeos.graphql(`mutation {
				insertCatalog(input: {
					label: "New category",
					code: "new-` + Math.floor(Math.random()*10000) + `",
					status: -1
				}, parentid: ${parentid}, refid: ${refid} ) {
					id
					siteid
					code
					label
					status
					hasChildren
				}
			}`).then(result => {
				this.$refs.tree.add(result?.insertCatalog, parent, where ? Math.max(idx + Math.max(where, 0), 0) : 0)
			}).then(() => {
				if(!where) {
					stat.data.hasChildren = true
					this.$refs.tree.openNodeAndParents(stat)
				}
			})
		},


		load(stat, ev) {
			if(stat.data._more) {
				this.$refs.tree.remove(stat)
				this.fetch(stat).then(items => {
					this.$refs.tree.addMulti(items, stat?.parent)
				})
			} else {
				this.$emit('load', stat, ev)
			}
		},


		move() {
			const parent = dragContext.startInfo.parent
			const target = dragContext.targetInfo.parent
			const siblings = dragContext.targetInfo.siblings
			const ref = siblings[dragContext.targetInfo.indexBeforeDrop+1] || null

			const id = dragContext.startInfo.dragNode.data.id
			const parentid = parent ? '"' + parent.data.id + '"' : 'null'
			const targetid = target ? '"' + target.data.id + '"' : 'null'
			const refid = ref ? '"' + ref.data.id + '"' : 'null'

			Aimeos.graphql(`mutation {
				moveCatalog(id: "${id}", parentid: ${parentid}, targetid: ${targetid}, refid: ${refid})
			}`)
		},


		search(input) {
			const filter = {'||': [
				{'=~': {'catalog.code': input}},
				{'=~': {'catalog.label': input}}
			]}

			return Aimeos.graphql(`query {
				searchCatalogTree(filter: ` + JSON.stringify(JSON.stringify(filter)) + `) {
					id
					siteid
					code
					label
					status
					hasChildren
					children {
						id
						siteid
						code
						label
						status
						hasChildren
						children {
							id
							siteid
							code
							label
							status
							hasChildren
							children {
								id
								siteid
								code
								label
								status
								hasChildren
								children {
									id
									siteid
									code
									label
									status
									hasChildren
									children {
										id
										siteid
										code
										label
										status
										hasChildren
										children {
											id
											siteid
											code
											label
											status
											hasChildren
											children {
												id
												siteid
												code
												label
												status
												hasChildren
											}
										}
									}
								}
							}
						}
					}
				}
			}`).then(result => {
				this.tree = result?.searchCatalogTree || []
			}).then(() => {
				this.$refs.tree.openAll()
			})
		},


		toggle(stat) {
			stat.open = !stat.open

			if(stat.open && stat.data?.hasChildren && (stat.children?.length || 0) === 0) {
				this.fetch(stat).then(items => {
					this.$refs.tree.addMulti(items, stat, 0)

					if(!items.length) {
						stat.data.hasChildren = false
					}
				})
			}
		}
	},


	watch: {
		input(val, old) {
			if(val && val !== old) {
				this.search(val)
			} else {
				this.init()
			}
		}
	}
};
