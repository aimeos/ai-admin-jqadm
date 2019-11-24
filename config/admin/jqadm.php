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
		'domains' => [
			'attribute' => 'attribute',
			'media' => 'media',
			'media/property' => 'media/property',
			'price' => 'price',
			'price/property' => 'price/property',
			'product' => 'product',
			'product/property' => 'product/property',
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
				'physical' => 'physical',
				'download' => 'download',
				'subscription' => 'subscription',
				'special' => 'special'
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

		'special' => [
			'standard' => [
				'subparts' => [
					'price' => 'price',
				],
			],
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
