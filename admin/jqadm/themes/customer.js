/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 */



Aimeos.Customer = {

	init : function() {

		Aimeos.Customer.Item.init();
	}
};



Aimeos.Customer.Item = {

	init : function() {

		this.toggleCompany();
	},


	toggleCompany : function() {

	}
};


$(function() {

	Aimeos.Customer.init();
});
