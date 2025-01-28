<?php

return [
	'site' => [
		/** admin/jqadm/resource/site/groups
		 * List of user groups that are allowed to change to different sites
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'super'],
	],
	'dashboard' => [
		/** admin/jqadm/resource/dashboard/groups
		 * List of user groups that are allowed to access the dashboard
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'editor', 'super'],

		/** admin/jqadm/resource/dashboard/key
		 * Shortcut key to switch to the dashboard by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2017.10
		 */
		'key' => 'D',
	],
	'sales' => [
		/** admin/jqadm/resource/sales/groups
		 * List of user groups that are allowed to access the sales submenu
		 *
		 * @param array List of user group names
		 * @since 2021.04
		 */
		'groups' => ['admin', 'editor', 'super'],
	],
	'order' => [
		/** admin/jqadm/resource/order/groups
		 * List of user groups that are allowed to access the order panel
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'editor', 'super'],

		/** admin/jqadm/resource/order/key
		 * Shortcut key to switch to the order panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2017.10
		 */
		'key' => 'O',
	],
	'subscription' => [
		/** admin/jqadm/resource/subscription/groups
		 * List of user groups that are allowed to access the subscription panel
		 *
		 * @param array List of user group names
		 * @since 2018.04
		 */
		'groups' => ['admin', 'editor', 'super'],

		/** admin/jqadm/resource/subscription/key
		 * Shortcut key to switch to the subscription panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2018.04
		 */
		'key' => 'X',
	],
	'basket' => [
		/** admin/jqadm/resource/basket/groups
		 * List of user groups that are allowed to access the basket panel
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'editor', 'super'],

		/** admin/jqadm/resource/basket/key
		 * Shortcut key to switch to the basket panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2023.10
		 */
		'key' => 'B',
	],
	'users' => [
		/** admin/jqadm/resource/users/groups
		 * List of user groups that are allowed to access the users submenu
		 *
		 * @param array List of user group names
		 * @since 2021.04
		 */
		'groups' => ['admin', 'editor', 'super'],
	],
	'customer' => [
		/** admin/jqadm/resource/customer/groups
		 * List of user groups that are allowed to access the customer panel
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'editor', 'super'],

		/** admin/jqadm/resource/customer/key
		 * Shortcut key to switch to the user panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2017.10
		 */
		'key' => 'U',
	],
	'group' => [
		/** admin/jqadm/resource/group/groups
		 * List of user groups that are allowed to access the group panel
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'super'],

		/** admin/jqadm/resource/group/key
		 * Shortcut key to switch to the group panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2018.07
		 */
		'key' => 'G',
	],
	'goods' => [
		/** admin/jqadm/resource/goods/groups
		 * List of user groups that are allowed to access the goods submenu
		 *
		 * @param array List of user group names
		 * @since 2021.04
		 */
		'groups' => ['admin', 'editor', 'super'],
	],
	'product' => [
		/** admin/jqadm/resource/product/groups
		 * List of user groups that are allowed to access the product panel
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'editor', 'super'],

		/** admin/jqadm/resource/product/key
		 * Shortcut key to switch to the product panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2017.10
		 */
		'key' => 'P',
	],
	'catalog' => [
		/** admin/jqadm/resource/catalog/groups
		 * List of user groups that are allowed to access the catalog panel
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'editor', 'super'],

		/** admin/jqadm/resource/catalog/key
		 * Shortcut key to switch to the catalog panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2017.10
		 */
		'key' => 'C',
	],
	'attribute' => [
		/** admin/jqadm/resource/attribute/groups
		 * List of user groups that are allowed to access the attribute panel
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'editor', 'super'],

		/** admin/jqadm/resource/attribute/key
		 * Shortcut key to switch to the attribute panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2017.10
		 */
		'key' => 'A',
	],
	'supplier' => [
		/** admin/jqadm/resource/supplier/groups
		 * List of user groups that are allowed to access the supplier panel
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'editor', 'super'],

		/** admin/jqadm/resource/supplier/key
		 * Shortcut key to switch to the supplier panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2017.10
		 */
		'key' => 'I',
	],
	'marketing' => [
		/** admin/jqadm/resource/marketing/groups
		 * List of user groups that are allowed to access the marketing submenu
		 *
		 * @param array List of user group names
		 * @since 2021.04
		 */
		'groups' => ['admin', 'editor', 'super'],
	],
	'coupon' => [
		/** admin/jqadm/resource/coupon/groups
		 * List of user groups that are allowed to access the voucher panel
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'editor', 'super'],

		/** admin/jqadm/resource/coupon/key
		 * Shortcut key to switch to the voucher panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2017.10
		 */
		'key' => 'V',
	],
	'review' => [
		/** admin/jqadm/resource/review/groups
		 * List of user groups that are allowed to access the review panel
		 *
		 * @param array List of user group names
		 * @since 2020.10
		 */
		'groups' => ['admin', 'editor', 'super'],

		/** admin/jqadm/resource/review/key
		 * Shortcut key to switch to the review panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2020.10
		 */
		'key' => 'R',
	],
	'setup' => [
		/** admin/jqadm/resource/setup/groups
		 * List of user groups that are allowed to access the setup menu
		 *
		 * @param array List of user group names
		 * @since 2021.04
		 */
		'groups' => ['admin', 'super'],
	],
	'settings' => [
		/** admin/jqadm/resource/settings/groups
		 * List of user groups that are allowed to access the settings submenu
		 *
		 * @param array List of user group names
		 * @since 2021.07
		 */
		'groups' => ['admin', 'super'],

		/** admin/jqadm/resource/settings/key
		 * Shortcut key to switch to the rule panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2021.04
		 */
		'key' => 'T',
	],
	'rule' => [
		/** admin/jqadm/resource/rule/groups
		 * List of user groups that are allowed to access the rule panel
		 *
		 * @param array List of user group names
		 * @since 2021.04
		 */
		'groups' => ['admin', 'editor', 'super'],

		/** admin/jqadm/resource/rule/key
		 * Shortcut key to switch to the rule panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2021.04
		 */
		'key' => 'E',
	],
	'service' => [
		/** admin/jqadm/resource/service/groups
		 * List of user groups that are allowed to access the service panel
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'super'],

		/** admin/jqadm/resource/service/key
		 * Shortcut key to switch to the service panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2017.10
		 */
		'key' => 'S',
	],
	'plugin' => [
		/** admin/jqadm/resource/plugin/groups
		 * List of user groups that are allowed to access the plugin panel
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'super'],

		/** admin/jqadm/resource/plugin/key
		 * Shortcut key to switch to the plugin panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2017.10
		 */
		'key' => 'N',
	],
	'locale' => [
		/** admin/jqadm/resource/locale/groups
		 * List of user groups that are allowed to access the locale panel
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'super'],

		/** admin/jqadm/resource/locale/key
		 * Shortcut key to switch to the locale panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2017.10
		 */
		'key' => '',

		'site' => [
			/** admin/jqadm/resource/locale/site/groups
			 * List of user groups that are allowed to access the locale site panel
			 *
			 * @param array List of user group names
			 * @since 2017.10
			 */
			'groups' => ['super'],

			/** admin/jqadm/resource/locale/site/key
			 * Shortcut key to switch to the locale site panel by using the keyboard
			 *
			 * @param string Single character in upper case
			 * @since 2017.10
			 */
			'key' => '',

		],
		'language' => [
			/** admin/jqadm/resource/locale/language/groups
			 * List of user groups that are allowed to access the locale language panel
			 *
			 * @param array List of user group names
			 * @since 2017.10
			 */
			'groups' => ['super'],

			/** admin/jqadm/resource/locale/language/key
			 * Shortcut key to switch to the locale language panel by using the keyboard
			 *
			 * @param string Single character in upper case
			 * @since 2017.10
			 */
			'key' => '',
		],
		'currency' => [
			/** admin/jqadm/resource/locale/currency/groups
			 * List of user groups that are allowed to access the locale currency panel
			 *
			 * @param array List of user group names
			 * @since 2017.10
			 */
			'groups' => ['super'],

			/** admin/jqadm/resource/locale/currency/key
			 * Shortcut key to switch to the locale currency panel by using the keyboard
			 *
			 * @param string Single character in upper case
			 * @since 2017.10
			 */
			'key' => '',
		],
	],
	'type' => [
		/** admin/jqadm/resource/type/groups
		 * List of user groups that are allowed to access the type panels
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'super'],
	],
	'log' => [
		/** admin/jqadm/resource/log/groups
		 * List of user groups that are allowed to view the log messages
		 *
		 * @param array List of user group names
		 * @since 2018.04
		 */
		'groups' => ['admin', 'super'],

		/** admin/jqadm/resource/log/key
		 * Shortcut key to switch to the log panel by using the keyboard
		 *
		 * @param string Single character in upper case
		 * @since 2018.04
		 */
		'key' => 'L',
	],
	'language' => [
		/** admin/jqadm/resource/language/groups
		 * List of user groups that are allowed to switch languages
		 *
		 * @param array List of user group names
		 * @since 2017.10
		 */
		'groups' => ['admin', 'editor', 'super'],
	],
];
