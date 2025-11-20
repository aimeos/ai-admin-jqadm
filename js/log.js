/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 */



Aimeos.Log = {

	init() {
		document.querySelector(".aimeos .list-log .list-items")?.addEventListener("dblclick", function(ev) {
			ev.target?.parentElement?.classList?.toggle("show");
		});
	}
};



document.addEventListener("DOMContentLoaded", function() {
    Aimeos.Log.init();
});
