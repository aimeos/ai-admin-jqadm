/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


Vue.component('confirm-delete', {
	template: '#confirm-delete',
	props: {
		'items': {type: Object, default: () => ({})},
		'show': {type: Boolean, default: false}
	}
});
