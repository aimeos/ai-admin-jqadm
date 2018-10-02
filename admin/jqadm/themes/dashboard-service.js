/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */


Aimeos.Dashboard.Service = {

	init : function() {

		if( $(".order-servicepayment").length ) {
			this.chartPayment();
		}

		if( $(".order-servicedelivery").length ) {
			this.chartDelivery();
		}
	},



	chartDelivery : function() {

		var criteria = {"==": {"order.base.service.type": "delivery"}};
		Aimeos.Dashboard.drawDonut("#order-servicedelivery-data", "order", "order.base.service.code", criteria, "-order.ctime", 1000);
	},



	chartPayment : function() {

		var criteria = {"==": {"order.base.service.type": "payment"}};
		Aimeos.Dashboard.drawDonut("#order-servicepayment-data", "order", "order.base.service.code", criteria, "-order.ctime", 1000);
	}
};



$(function() {
	Aimeos.Dashboard.Service.init();
});
