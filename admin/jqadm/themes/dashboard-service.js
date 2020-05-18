/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2018
 */


Aimeos.Dashboard.Service = {

	init : function() {

		Aimeos.lazy(".order-servicepayment", this.chartPayment.bind(this));
		Aimeos.lazy(".order-servicedelivery", this.chartDelivery.bind(this));
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
