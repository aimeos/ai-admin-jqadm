<?php

return [
	'attribute' => [
		'domains' => [
			'attribute/property' => 'attribute/property',
			'media' => 'media',
			'media/property' => 'media/property',
			'price' => 'price',
			'price/property' => 'price/property',
			'text' => 'text',
		],
		'standard' => [
			'subparts' => [
				'media' => 'media',
				'text' => 'text',
				'price' => 'price',
				'property' => 'property',
			],
		],
		'media' => [
			'standard' => [
				'subparts' => [
					'property' => 'property',
				],
			],
		],
		'price' => [
			'standard' => [
				'subparts' => [
					'property' => 'property',
				],
			],
		],
	],
	'catalog' => [
		'domains' => [
			'media' => 'media',
			'media/property' => 'media/property',
			'text' => 'text',
		],
		'standard' => [
			'subparts' => [
				'media' => 'media',
				'text' => 'text',
				'price' => 'product',
			],
		],
		'media' => [
			'standard' => [
				'subparts' => [
					'property' => 'property',
				],
			],
		],
		'product' => [
			'decorators' => [
				'global' => ['Index'],
			],
		],
	],
	'common' => [
		'decorators' => [
			'default' => ['Page'],
		],
	],
	'coupon' => [
		'standard' => [
			'subparts' => [
				'code' => 'code',
			],
		],
	],
	'customer' => [
		'domains' => [
			'customer/address' => 'customer/address',
			'customer/group' => 'customer/group',
			'customer/property' => 'customer/property',
		],
		'standard' => [
			'subparts' => [
				'address' => 'address',
				'order' => 'order',
				'product' => 'product',
				'property' => 'property',
			],
		],
	],
	'dashboard' => [
		'standard' => [
			'subparts' => [
				'setting' => 'setting',
				'job' => 'job',
				'order' => 'order',
			],
		],
		'order' => [
			'standard' => [
					'subparts' => [
					'latest' => 'latest',
					'salesday' => 'salesday',
					'salesmonth' => 'salesmonth',
					'salesweekday' => 'salesweekday',
					'countday' => 'countday',
					'countpaystatus' => 'countpaystatus',
					'counthour' => 'counthour',
					'servicepayment' => 'servicepayment',
					'servicedelivery' => 'servicedelivery',
				],
			],
		],
	],
	'order' => [
		'standard' => [
			'subparts' => [
				'invoice' => 'invoice',
			],
		],
	],
	'product' => [
		'decorators' => [
			'global' => ['Cache'],
		],
		'domains' => [
			'attribute' => 'attribute',
			'media' => 'media',
			'media/property' => 'media/property',
			'price' => 'price',
			'price/property' => 'price/property',
			'product' => 'product',
			'product/property' => 'product/property',
			'stock' => ['default'],
			'text' => 'text',
		],
		'standard' => [
			'subparts' => [
				'selection' => 'selection',
				'bundle' => 'bundle',
				'media' => 'media',
				'text' => 'text',
				'price' => 'price',
				'stock' => 'stock',
				'category' => 'category',
				'characteristic' => 'characteristic',
				'option' => 'option',
				'related' => 'related',
				'supplier' => 'supplier',
				'physical' => 'physical',
				'subscription' => 'subscription',
				'download' => 'download',
				'order' => 'order',
			],
		],
		'characteristic' => [
			'standard' => [
				'subparts' => [
					'property' => 'property',
					'variant' => 'variant',
					'attribute' => 'attribute',
					'hidden' => 'hidden',
				],
			],
		],
		'media' => [
			'standard' => [
				'subparts' => [
					'property' => 'property'
				],
			],
		],
		'option' => [
			'config' => [
				'standard' => [
					'exclude' => [
						'interval' => 'interval',
					],
				],
			],
			'custom' => [
				'standard' => [
					'exclude' => [
						'price' => 'price',
					],
				],
			],
			'standard' => [
				'subparts' => [
					'config' => 'config',
					'custom' => 'custom',
				],
			],
		],
		'price' => [
			'standard' => [
				'subparts' => [
					'property' => 'property',
				],
			],
		],
		'related' => [
			'standard' => [
				'subparts' => [
					'suggest' => 'suggest',
					'bought' => 'bought',
				],
			],
		],
	],
	'product/category' => [
		'decorators' => [
			'local' => ['Cache'],
		],
	],
	'service' => [
		'domains' => [
			'media' => 'media',
			'media/property' => 'media/property',
			'price' => 'price',
			'price/property' => 'price/property',
			'text' => 'text'
		],
		'standard' => [
			'subparts' => [
				'media' => 'media',
				'text' => 'text',
				'price' => 'price',
			],
		],
		'media' => [
			'standard' => [
				'subparts' => [
					'property' => 'property',
				],
			],
		],
		'price' => [
			'standard' => [
				'subparts' => [
					'property' => 'property',
				],
			],
		],
	],
	'supplier' => [
		'domains' => [
			'media' => 'media',
			'supplier/address' => 'supplier/address',
			'text' => 'text',
		],
		'standard' => [
			'subparts' => [
				'address' => 'address',
				'media' => 'media',
				'text' => 'text',
				'product' => 'product',
			],
		],
		'media' => [
			'standard' => [
				'subparts' => [
					'property' => 'property',
				],
			],
		],
	],
];
