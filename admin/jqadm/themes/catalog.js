/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


/**
 * Attention:
 *
 * Updating tree.jquery.js requires removing or overwriting these lines from
 * NodeElement.prototype.select() and NodeElement.prototype.deselect():
 *
 * var $span = this.getSpan();
 * $span.attr("tabindex", 0);
 * $span.focus();
 */



/**
 * Load categories and create catalog tree
 */
Aimeos.options.done(function(result) {

	if(!result || !result.meta || !result.meta.resources || !result.meta.resources.catalog || $(".aimeos .item-catalog").length === 0) {
		return;
	}

	if(result.meta.prefix) {
		Aimeos.Catalog.prefix = result.meta.prefix;
	}

	var params = {};
	var rootId = $(".aimeos .item-catalog").data("rootid");

	if(rootId) {
		if(result.meta.prefix) {
			params[result.meta.prefix] = {id: rootId, include: "catalog"};
		} else {
			params = {id: rootId, include: "catalog"};
		}
	}

	$.ajax(result.meta.resources.catalog, {
		"data": params,
		"dataType": "json"
	}).done(function(result) {

		if(!result || !result.data || !result.meta) {
			throw {"msg": "No valid data in response", "result": result};
		}

		if(result.meta.csrf) {
			Aimeos.Catalog.csrf = result.meta.csrf;
		}

		var root = Aimeos.Catalog.createTree(Aimeos.Catalog.transformNodes(result));

		root.bind("tree.click", Aimeos.Catalog.onClick);
		root.bind("tree.move", Aimeos.Catalog.onMove);

		var id = $(".aimeos .item-catalog #item-id").val() || $(".aimeos .item-catalog #item-parentid").val();
		var node = root.tree("getNodeById", id);

		root.tree("selectNode", node);
		root.tree("openNode", node);
	});
});



Aimeos.Catalog = {

	csrf : null,
	element : null,
	prefix : null,


	init : function() {

		this.askDelete();
		this.confirmDelete();

		this.setupAdd();
		this.setupSearch();
		this.setupExpandAll();
		this.setupCollapseAll();
	},


	createTree : function(root) {

		var tree = $(".aimeos .item-catalog .tree-content").tree({
			"data": [root],
			"dragAndDrop": true,
			"closedIcon": " ",
			"openedIcon": " ",
			"saveState": true,
			"slide": false,
			"dataFilter": function(result) {
				var list = [];

				for(var i in result.included) {
					if(result.included[i].type !== 'catalog') {
						continue;
					}
					list.push({
						id: result.included[i].id,
						name: result.included[i].attributes['catalog.label'],
						load_on_demand: result.included[i].attributes['catalog.hasChildren'],
						children: []
					});
				}

				return list;
			},
			"dataUrl": function(node) {

				var params = {};

				if(Aimeos.Catalog.prefix) {
					params[Aimeos.Catalog.prefix] = {'include': 'catalog'};
				} else {
					params = {'include': 'catalog'};
				}

				var result = {
					'url': $(".aimeos .item-tree").data("jsonurl"),
					'data': params,
					'method': 'GET'
				}

				if(node) {
					var name = $(".aimeos .item-tree").data("idname");
					result['data'][name] = node.id;
				}

				return result;
			},
			"onCanMoveTo": function(node, target, position) {
				if(target === tree.tree('getTree').children[0] && position !== 'inside') {
					return false;
				}
				return true;
			},
			"onCreateLi": function(node, li, isselected) {
				$(".jqtree-toggler", li).attr("tabindex", 1);
				$(".jqtree-title", li).attr("tabindex", 1);
			}
		});

		return tree;
	},


	onClick : function(event) {
		window.location = $(".aimeos .item-catalog").data("geturl").replace("_ID_", event.node.id);
	},


	onMove : function(event) {
		event.preventDefault();

		Aimeos.options.done(function(result) {

			if(!result || !result.meta || !result.meta.resources || !result.meta.resources.catalog) {
				throw {"msg": "No valid data in response", "result": result};
			}

			var params = {};
			var url = result.meta.resources.catalog;
			var targetid = event.move_info.target_node.id;

			if(result.meta.prefix) {
				params[result.meta.prefix] = {id: event.move_info.moved_node.id};
			} else {
				params = {id: event.move_info.moved_node.id};
			}

			if(Aimeos.Catalog.csrf) {
				params[Aimeos.Catalog.csrf.name] = Aimeos.Catalog.csrf.value;
			}

			var entry = {
				attributes: {},
				id: event.move_info.moved_node.id,
				parentid: event.move_info.previous_parent.id
			};

			if(event.move_info.position === 'inside') {
				entry.targetid = targetid;
			} else {
				entry.targetid = event.move_info.target_node.parent.id;
			}

			if(event.move_info.position === 'after') {
				var children = event.move_info.target_node.parent.children;

				for(var i = 0; i < children.length; i++) {
					if(children[i].id === targetid && i+1 < children.length) {
						entry.refid = children[i+1].id;
						break;
					}
				}
			} else if(event.move_info.position === 'before') {
				entry.refid = targetid;
			}

			$.ajax(url + (url.indexOf('?') !== -1 ? '&' : '?') + jQuery.param(params), {
				"dataType": "json",
				"method": "PATCH",
				"data": JSON.stringify({"data": entry})
			}).done(function(result) {
				event.move_info.do_move();

				if(result.meta.csrf) {
					Aimeos.Catalog.csrf = result.meta.csrf;
				}
			});
		});
	},


	transformNodes : function(result) {

		root = {
			id: result.data.id,
			name: result.data.attributes['catalog.label'] || '-none-',
			children: []
		};

		if(result.included && result.included.length > 0) {

			var getChildren = function(list, parentId) {
				var result = [];

				for(var i in list) {
					if(list[i].attributes['catalog.parentid'] == parentId) {
						result.push({
							id: list[i].id,
							name: list[i].attributes['catalog.label'],
							load_on_demand: list[i].attributes['catalog.hasChildren'],
							children: getChildren(list, list[i].id)
						});
					}
				}

				return result;
			};

			root.children = getChildren(result.included, result.data.id);
		}

		return root;
	},


	askDelete : function() {
		var self = this;

		$(".aimeos .item-catalog").on("click", ".tree-toolbar .act-delete", function(ev) {

			$("#confirm-delete").modal("show", $(this));
			self.element = $(".tree-content", ev.delegateTarget).tree("getSelectedNode");
			return false;
		});
	},


	confirmDelete : function() {
		var self = this;

		$("#confirm-delete").on("click", ".btn-danger", function(e) {
			if(self.element) {
				self.deleteNode(self.element, self.element.parent || null);
			}
		});
	},


	deleteNode : function(node, parent) {

		Aimeos.options.done(function(result) {

			if(!result || !result.meta || !result.meta.resources || !result.meta.resources.catalog) {
				throw {"msg": "No valid data in response", "result": result};
			}

			var params = {};
			var url = result.meta.resources.catalog;

			if(result.meta.prefix) {
				params[result.meta.prefix] = {id: node.id};
			} else {
				params = {id: node.id};
			}

			if(Aimeos.Catalog.csrf) {
				params[Aimeos.Catalog.csrf.name] = Aimeos.Catalog.csrf.value;
			}

			$.ajax(url + (url.indexOf('?') !== -1 ? '&' : '?') + jQuery.param(params), {
				"dataType": "json",
				"method": "DELETE"
			}).done(function(result) {

				if(result.meta.csrf) {
					Aimeos.Catalog.csrf = result.meta.csrf;
				}

				if(!result.errors) {
					window.location = $(".aimeos .item-catalog").data("createurl").replace("_ID_", (parent && parent.id ? parent.id : ''));
				}
			});
		});
	},


	setupAdd : function() {

		$(".aimeos .item-catalog").on("click", ".tree-toolbar .act-add", function(ev) {

			var root = $(".tree-content", ev.delegateTarget);
			var node = root.tree("getSelectedNode");

			if(!node) {
				node = root.tree("getNodeByHtmlElement", $(".jqtree-tree > .jqtree-folder", root));
			}

			window.location = $(ev.delegateTarget).data("createurl").replace("_ID_", (node ? node.id : ''));
		});
	},


	setupCollapseAll : function() {

		$(".aimeos .item-catalog .catalog-tree").on("click", ".tree-toolbar .collapse-all", function(ev) {
			$(".tree-content .jqtree-folder .jqtree-toggler", ev.delegateTarget).addClass("jqtree-closed");
			$(".tree-content .jqtree-folder", ev.delegateTarget).addClass("jqtree-closed");
			$('.tree-content ul.jqtree_common[role="group"]', ev.delegateTarget).css("display", "none");
		});
	},


	setupExpandAll : function() {

		$(".aimeos .item-catalog .catalog-tree").on("click", ".tree-toolbar .expand-all", function(ev) {
			$(".tree-content .jqtree-folder .jqtree-toggler.jqtree-closed", ev.delegateTarget).removeClass("jqtree-closed");
			$(".tree-content .jqtree-folder.jqtree-closed", ev.delegateTarget).removeClass("jqtree-closed");
			$('.tree-content ul.jqtree_common[role="group"]', ev.delegateTarget).css("display", "block");
		});
	},


	setupSearch : function() {

		$(".aimeos .catalog-tree .tree-toolbar").on("input", ".search-input", function() {
			var name = $(this).val();

			$('.aimeos .catalog-tree .tree-content .jqtree_common[role="treeitem"]').each(function(idx, node) {
				var regex = new RegExp(name, 'i');
				var node = $(node);

				if(regex.test(node.html())) {
					node.parents("li.jqtree_common").show();
					node.show();
				} else {
					node.hide();
				}
			});
		});
	}
};



Aimeos.Catalog.Product = {

	init : function() {

		this.addItem();
		this.closeItem();
		this.removeItem();
	},


	addItem : function() {

		$(".item-catalog .item-product").on("click", ".list-header .act-add", function(ev) {
			Aimeos.addClone(
				$(".list-item-new.prototype", ev.delegateTarget),
				Aimeos.getOptionsProducts,
				Aimeos.Catalog.Product.select);
		});
	},


	closeItem : function() {

		$(".item-catalog .item-product").on("click", ".act-close", function(ev) {
			$(this).closest("tr").remove();
		});
	},


	removeItem : function() {

		$(".item-catalog .item-product .list-item").on("click", ".act-delete", function(ev) {

			var elem = $(this);
			var row = $(ev.delegateTarget);

			Aimeos.options.done(function(result) {

				if(!result || !result.meta) {
					throw {"msg": "No valid data in response", "result": result};
				}

				var params = {}, param = {};
				var url = elem.attr("href");

				if(result.meta.prefix) {
					params[result.meta.prefix] = param;
				} else {
					params = param;
				}

				if(Aimeos.Catalog.csrf) {
					params[Aimeos.Catalog.csrf.name] = Aimeos.Catalog.csrf.value;
				}

				$.ajax({
					"url": url + (url.indexOf('?') !== -1 ? '&' : '?') + jQuery.param(params),
					"dataType": "json",
					"method": "DELETE"
				}).done(function(result) {

					if(result.meta.csrf) {
						Aimeos.Catalog.csrf = result.meta.csrf;
					}

					Aimeos.focusBefore(row).remove();
				});
			});

			return false;
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("tr").find("input.item-label").val(node.val());
	}
};



$(function() {

	Aimeos.Catalog.init();
	Aimeos.Catalog.Product.init();
});
