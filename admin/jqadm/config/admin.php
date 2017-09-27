<?php

return [
	'jqadm' => [
		'navbar' => [
			'dashboard',
			'order',
			'customer',
			'product',
			'catalog',
			'attribute',
			'coupon',
			'supplier',
			'service',
			'plugin',
			'locale' => [
				'locale',
				'locale/site',
				'locale/language',
				'locale/currency',
			],
			'type' => [
				'type/attribute',
				'type/attribute/lists',
				'type/catalog/lists',
				'type/customer/lists',
				'type/media',
				'type/media/lists',
				'type/plugin',
				'type/price',
				'type/price/lists',
				'type/product',
				'type/product/lists',
				'type/product/property',
				'type/service',
				'type/service/lists',
				'type/stock',
				'type/tag',
				'type/text',
				'type/text/lists',
			],
		],
		'resource' => [
			'site' => [
				'groups' => ['admin', 'super'],
			],
			'dashboard' => [
				'groups' => ['admin', 'editor', 'super'],
				'key' => 'D',
			],
			'order' => [
				'groups' => ['admin', 'editor', 'super'],
				'key' => 'O',
			],
			'customer' => [
				'groups' => ['admin', 'editor', 'super'],
				'key' => 'U',
			],
			'product' => [
				'groups' => ['admin', 'editor', 'super'],
				'key' => 'P',
			],
			'catalog' => [
				'groups' => ['admin', 'editor', 'super'],
				'key' => 'C',
			],
			'attribute' => [
				'groups' => ['admin', 'editor', 'super'],
				'key' => 'A',
			],
			'coupon' => [
				'groups' => ['admin', 'editor', 'super'],
				'key' => 'V',
			],
			'supplier' => [
				'groups' => ['admin', 'editor', 'super'],
				'key' => 'I',
			],
			'service' => [
				'groups' => ['admin', 'super'],
				'key' => 'S',
			],
			'plugin' => [
				'groups' => ['admin', 'super'],
				'key' => 'G',
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
	],
];