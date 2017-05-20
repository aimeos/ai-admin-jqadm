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
			'd' => 'dashboard', /*'o' => 'order', 'u' => 'customer',*/ 'p' => 'product', /*'c' => 'catalog', 'v' => 'voucher'*/
			/*'r' => 'supplier', 's' => 'service', 'g' => 'plugin', 'l' => 'locale', 't' => 'type'*/
		],
		'advanced' => ['supplier', 'service', 'plugin', 'locale', 'type'],
	],
];