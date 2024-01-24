/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


Aimeos.components['confirm-delete'] = {
	template: '#confirm-delete',
	emits: ['close', 'confirm'],
	props: {
		'items': {type: Object, default: () => ({})},
		'show': {type: Boolean, default: false}
	}
};
