/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */



Aimeos.Customer = {

	init : function() {
		$(".item-customer .item-countryid.combobox").combobox({
			getfcn: Aimeos.getCountries,
			select: Aimeos.Customer.select
		});
	},


	select: function(ev, ui) {

		var node = $(ev.delegateTarget);
		node.closest("tr").find("input.item-label").val(node.val());
	}
};



$(function() {

	Aimeos.Customer.init();
});
