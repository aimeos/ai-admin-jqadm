<?php

return [
	'jqadm' => [
		'common' => [
			'decorators' => [
				'default' => ['Page'],
			],
		],
		'product' => [
			'decorators' => [
				'global' => ['Index', 'Cache'],
			],
		],
		'product/category' => [
			'decorators' => [
				'local' => ['Cache'],
			],
		],
		'resources' => [
			'd' => 'dashboard', 'o' => 'order', 'u' => 'customer', 'p' => 'product',
			'c' => 'catalog', 'a' => 'attribute', 'v' => 'coupon',
			'r' => 'supplier', 's' => 'service', 'g' => 'plugin',
		],
		'resources-admin' => [
			'supplier', 'service', 'plugin', 'type',
			'locale', 'locale/site', 'locale/language', 'locale/currency',
		],
		'resources-types' => [
			'type/attribute', 'type/attribute/lists', 'type/catalog/lists', 'type/customer/lists',
			'type/media', 'type/media/lists', 'type/plugin', 'type/price', 'type/price/lists',
			'type/product', 'type/product/lists', 'type/product/property', 'type/service', 'type/service/lists',
			'type/stock', 'type/tag', 'type/text', 'type/text/lists',
		],
	],
];