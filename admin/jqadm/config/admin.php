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
			'locale', 'locale/site',
		],
		'resources-types' => [
			'type/attribute', 'type/media', 'type/plugin', 'type/price', 'type/product', 'type/service', 'type/text',
		],
	],
];