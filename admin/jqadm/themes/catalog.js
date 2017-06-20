/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */


Aimeos.Catalog = {

	tree: null,
	request : null,


	init : function() {

		this.setupTree();
		this.setupSearch();
		this.setupExpandAll();
		this.setupCollapseAll();
	},


	setupCollapseAll : function() {

		var self = this;
		$(".aimeos .catalog-tree .tree-toolbar .collapse-all").on("click", function() {
			self.tree.collapseAll();
		});
	},


	setupExpandAll : function() {

		var self = this;
		$(".aimeos .catalog-tree .tree-toolbar .expand-all").on("click", function() {
			self.tree.expandAll();
		});
	},


	setupSearch : function() {

		var self = this;
		$(".aimeos .catalog-tree .tree-toolbar").on("input", ".search-input", function() {

			name = $(this).val();

			self.tree.nodes[0].open = false;
			self.tree.nodes.forEach(function (node) {
				var regex = new RegExp(name, 'i');

				if (regex.test(node.name)) {
					self.showParents(node);
					node.open = true;
					node.hidden = false;
				} else {
					node.hidden = true;
					node.open = false;
				}
			});

			self.tree.prepareDataForVisibleNodes();
			self.tree.update();
    	});
	},


	setupTree : function() {

		this.tree = new SvgTree;

		this.tree.initialize('.aimeos .catalog-tree .tree-content', {
			'expandUpToLevel': 1,
			'showIcons': false
		}, function(callback) {
			Aimeos.options.done(function(data) {

				$.ajax(data['meta']['resources']['catalog'] || null, {
					"data": {include: "catalog"},
					"dataType": "json"
				}).done(function(result) {

					if(result.errors && result.errors[0] || Array.isArray(result.data) === false) {
						throw result.errors[0];
					}

					nodes = result.data.map(function(entry) {
						return {
							identifier: entry.id,
							name: entry.attributes['catalog.label'],
							depth: 0, //entry.attributes['catalog.level'],
							hasChildren: entry.attributes['catalog.hasChildren']
						};
					});

					callback(nodes);
				});
			});
		});
	},


    showParents : function(node) {
        if (node.parents.length === 0) {
            return true;
        }

        var parent = this.tree.nodes[node.parents[0]];
        parent.hidden = false;
        parent.open = true;
        this.showParents(parent);
    }
};



$(function() {

	Aimeos.Catalog.init();
});
