/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 */



Aimeos.Log = {

	time : null,


	init() {

		document.querySelector(".aimeos .list-log .log-message").addEventListener("mousedown", function() {
			this.time = (new Date()).getTime();
		});

		document.querySelector(".aimeos .list-log .log-message").addEventListener("mouseup", function(ev) {
			if(this.time < (new Date()).getTime() - 500) {
				return false;
			}

			ev.currentTarget.classList.toggle("show");
			return false;
		});
	}
};



document.addEventListener("DOMContentLoaded", function() {
    Aimeos.Log.init();
});