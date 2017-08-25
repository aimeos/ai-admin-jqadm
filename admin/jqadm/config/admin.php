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
			'site' => [
				'groups' => ['admin'],
			],
			'dashboard' => [
				'groups' => ['admin', 'editor', 'viewer'],
			],
			'order' => [
				'groups' => ['admin', 'editor', 'viewer'],
			],
			'customer' => [
				'groups' => ['admin', 'editor', 'viewer'],
			],
			'product' => [
				'groups' => ['admin', 'editor'],
			],
			'catalog' => [
				'groups' => ['admin', 'editor'],
			],
			'attribute' => [
				'groups' => ['admin', 'editor'],
			],
			'coupon' => [
				'groups' => ['admin', 'editor'],
			],
			'supplier' => [
				'groups' => ['admin', 'editor'],
			],
			'service' => [
				'groups' => ['admin'],
			],
			'plugin' => [
				'groups' => ['admin'],
			],
			'type' => [
				'groups' => ['admin'],
			],
			'locale' => [
				'groups' => ['admin'],
				'site' => [
					'groups' => ['admin'],
				],
				'language' => [
					'groups' => ['admin'],
				],
				'currency' => [
					'groups' => ['admin'],
				],
			],
			'type' => [
				'groups' => ['admin'],
				'attribute' => [
					'groups' => ['admin'],
					'lists' => [
						'groups' => ['admin'],
					],
				],
				'catalog' => [
					'lists' => [
						'groups' => ['admin'],
					],
				],
				'customer' => [
					'lists' => [
						'groups' => ['admin'],
					],
				],
				'media' => [
					'groups' => ['admin'],
					'lists' => [
						'groups' => ['admin'],
					],
				],
				'plugin' => [
					'groups' => ['admin'],
				],
				'price' => [
					'groups' => ['admin'],
					'lists' => [
						'groups' => ['admin'],
					],
				],
				'product' => [
					'groups' => ['admin'],
					'lists' => [
						'groups' => ['admin'],
					],
					'property' => [
						'groups' => ['admin'],
					],
				],
				'service' => [
					'groups' => ['admin'],
					'lists' => [
						'groups' => ['admin'],
					],
				],
				'stock' => [
					'groups' => ['admin'],
				],
				'tag' => [
					'groups' => ['admin'],
				],
				'text' => [
					'groups' => ['admin'],
					'lists' => [
						'groups' => ['admin'],
					],
				],
			],
			'expert' => [
				'groups' => ['admin'],
			],
			'language' => [
				'groups' => ['admin', 'editor', 'viewer'],
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