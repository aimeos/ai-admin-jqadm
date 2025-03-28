/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2025
 */


Aimeos.components['tree'] = {
	components: { DragTree },

	template: `
	<div class="tree">
		<div class="tree-filter">
			<input class="form-control" v-model="input" :placeholder="placeholder" />
		</div>
		<DragTree
			ref="tree"
			v-model="tree"
			:beforeDragOpen="toggle"
			:defaultOpen="false"
			:disableDrag="!movable || readonly"
			:dragOpen="true"
			:eachDraggable="canMove"
			:rtl="rtl"
			:treeLine="true"
			:watermark="false"
			virtualization
			@change="move()"
		>
			<template #default="{ node, stat }">
				<div v-if="can('change', node.siteid)" class="dropdown">
					<button type="button" class="icon icon-treemenu dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="sr-only">{{ i18n.menu || 'Tree node menu' }}</span>
					</button>
					<ul class="dropdown-menu dropdown-menu-end">
						<li v-if="can('insert', node.siteid)" class="dropdown-item"><a class="action" href="#" @click="insert(stat, $event, -1)">{{ i18n.before || 'Add before' }}</a></li>
						<li v-if="can('insert', node.siteid)" class="dropdown-item"><a class="action" href="#" @click="insert(stat, $event, 0)">{{ i18n.insert || 'Insert into' }}</a></li>
						<li v-if="can('insert', node.siteid)" class="dropdown-item"><a class="action" href="#" @click="insert(stat, $event, 1)">{{ i18n.after || 'Add after' }}</a></li>
						<li v-if="can('delete', node.siteid)" class="dropdown-item"><a class="action" href="#" @click="ask(stat, $event)">{{ i18n.delete || 'Delete' }}</a></li>
					</ul>
				</div>
				<a class="label" :class="'node-status-' + node.status" href="#" :draggable="movable ? true : false" @click="load(stat, $event)">
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
		domain: {type: String, required: true},
		siteid: {type: String, required: true},
		i18n: {type: Object, default: () => ({})},
		limit: {type: Number, default: 100},
		movable: {type: Boolean, default: true},
		placeholder: {type: String, default: 'Find node'},
		readonly: {type: Boolean, default: false},
		rtl: {type: Boolean, default: false},
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


	computed: {
		prefix() {
			return this.domain.replace(/\//, '.')
		},


		name() {
			return this.domain.split('/').map(([first = '', ...rest]) => [first.toUpperCase(), ...rest].join('')).join('')
		}
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


		can(action, siteid) {
			return !this.readonly && Aimeos.can(action, siteid || null, this.siteid)
		},


		canMove(stat) {
			return this.can('move', stat.data.siteid)
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
				delete` + this.name + `(id: "${stat.data.id}")
			}`).then(() => {
				this.$refs.tree.remove(stat)
			}).then(() => {
				if(stat.parent && !stat.parent.children?.length) {
					stat.parent.open = false
				}
			})
		},


		fetch(stat = null) {
			let filter = {'==': {[this.prefix + '.parentid']: stat?.data?.id || null}};
			stat ? stat.loading = true : null

			return Aimeos.graphql(`query {
				search` + this.name + `s(filter: ` + JSON.stringify(JSON.stringify(filter)) + `, sort: ["sort:` + this.prefix + `:position"], offset: ${stat?.data?._offset || 0}) {
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
				const name = 'search' + this.name + 's'
				const items = result[name]?.items || []
				const length = stat?.data?._offset || (stat?.children ? stat.children.length : this.tree.length)

				if(length + items.length < (result[name]?.total || 0)) {
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
			let idx = (stat.parent?.children || this.$refs.tree.stats).findIndex(item => item.data.id == stat.data.id) || 0

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
				insert` + this.name + `(input: {
					label: "` + (this.i18n.new || 'New node') + `",
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
				const name = 'insert' + this.name
				this.$refs.tree.add(result[name], parent, where ? Math.max(idx + Math.max(where, 0), 0) : 0)
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
				move` + this.name + `(id: "${id}", parentid: ${parentid}, targetid: ${targetid}, refid: ${refid})
			}`)
		},


		search(input) {
			const filter = {'||': [
				{'=~': {[this.prefix + '.code']: input}},
				{'=~': {[this.prefix + '.label']: input}}
			]}

			return Aimeos.graphql(`query {
				search` + this.name + `Tree(filter: ` + JSON.stringify(JSON.stringify(filter)) + `) {
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
				const name = 'search' + this.name + 'Tree'
				this.tree = result[name] || []
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
