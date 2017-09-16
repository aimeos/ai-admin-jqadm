<?php

return [
	'jqadm' => [
		'access' => [
			'site' => [
				'groups' => ['admin', 'super'],
			],
			'dashboard' => [
				'groups' => ['admin', 'editor', 'super'],
			],
			'order' => [
				'groups' => ['admin', 'editor', 'super'],
			],
			'customer' => [
				'groups' => ['admin', 'editor', 'super'],
			],
			'product' => [
				'groups' => ['admin', 'editor', 'super'],
			],
			'catalog' => [
				'groups' => ['admin', 'editor', 'super'],
			],
			'attribute' => [
				'groups' => ['admin', 'editor', 'super'],
			],
			'coupon' => [
				'groups' => ['admin', 'editor', 'super'],
			],
			'supplier' => [
				'groups' => ['admin', 'editor', 'super'],
			],
			'service' => [
				'groups' => ['admin', 'super'],
			],
			'plugin' => [
				'groups' => ['admin', 'super'],
			],
			'locale' => [
				'groups' => ['admin', 'super'],
				'site' => [
					'groups' => ['admin', 'super'],
				],
				'language' => [
					'groups' => ['admin', 'super'],
				],
				'currency' => [
					'groups' => ['admin', 'super'],
				],
			],
			'type' => [
				'groups' => ['admin', 'super'],
				'attribute' => [
					'groups' => ['admin', 'super'],
					'lists' => [
						'groups' => ['admin', 'super'],
					],
				],
				'catalog' => [
					'lists' => [
						'groups' => ['admin', 'super'],
					],
				],
				'customer' => [
					'lists' => [
						'groups' => ['admin', 'super'],
					],
				],
				'media' => [
					'groups' => ['admin', 'super'],
					'lists' => [
						'groups' => ['admin', 'super'],
					],
				],
				'plugin' => [
					'groups' => ['admin', 'super'],
				],
				'price' => [
					'groups' => ['admin', 'super'],
					'lists' => [
						'groups' => ['admin', 'super'],
					],
				],
				'product' => [
					'groups' => ['admin', 'super'],
					'lists' => [
						'groups' => ['admin', 'super'],
					],
					'property' => [
						'groups' => ['admin', 'super'],
					],
				],
				'service' => [
					'groups' => ['admin', 'super'],
					'lists' => [
						'groups' => ['admin', 'super'],
					],
				],
				'stock' => [
					'groups' => ['admin', 'super'],
				],
				'tag' => [
					'groups' => ['admin', 'super'],
				],
				'text' => [
					'groups' => ['admin', 'super'],
					'lists' => [
						'groups' => ['admin', 'super'],
					],
				],
			],
			'expert' => [
				'groups' => ['admin', 'super'],
			],
			'language' => [
				'groups' => ['admin', 'editor', 'super'],
			],
		],
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
		'resources-types' => [
			'type/attribute', 'type/attribute/lists', 'type/catalog/lists', 'type/customer/lists',
			'type/media', 'type/media/lists', 'type/plugin', 'type/price', 'type/price/lists',
			'type/product', 'type/product/lists', 'type/product/property', 'type/service', 'type/service/lists',
			'type/stock', 'type/tag', 'type/text', 'type/text/lists',
		],
	],
];