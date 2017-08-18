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
			/*'t' => 'type'*/
		],
		'resources-admin' => [
			'supplier', 'service', 'plugin',
			'locale', 'locale/site',
		],
		'resources-types' => [
			'attribute/type', 'attribute/listtype'
		],
	],
];