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
		'subparts' => [
			'media' => 'media',
			'text' => 'text',
			'price' => 'price',
			'property' => 'property',
		],
		'media' => [
			'subparts' => [
				'property' => 'property',
			],
		],
		'price' => [
			'subparts' => [
				'property' => 'property',
			],
		],
	],
	'catalog' => [
		'domains' => [
			'media' => 'media',
			'media/property' => 'media/property',
			'text' => 'text',
		],
		'subparts' => [
			'media' => 'media',
			'text' => 'text',
			'price' => 'product',
		],
		'media' => [
			'subparts' => [
				'property' => 'property',
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
		'subparts' => [
			'code' => 'code',
		],
	],
	'customer' => [
		'domains' => [
			'customer/address' => 'customer/address',
			'customer/group' => 'customer/group',
			'customer/property' => 'customer/property',
		],
		'subparts' => [
			'address' => 'address',
			'order' => 'order',
			'product' => 'product',
			'property' => 'property',
		],
	],
	'dashboard' => [
		'subparts' => [
			'setting' => 'setting',
			'job' => 'job',
			'order' => 'order',
		],
		'order' => [
			'subparts' => [
				'quick' => 'quick',
				'latest' => 'latest',
				'salesday' => 'salesday',
				'salesmonth' => 'salesmonth',
				'salesweekday' => 'salesweekday',
				'countday' => 'countday',
				'countpaystatus' => 'countpaystatus',
				'counthour' => 'counthour',
				'countcountry' => 'countcountry',
				'servicepayment' => 'servicepayment',
				'servicedelivery' => 'servicedelivery',
			],
			'quick' => [
				'subparts' => [
					'counttotal' => 'counttotal',
					'countcompleted' => 'countcompleted',
					'countunfinished' => 'countunfinished',
					'countcustomer' => 'countcustomer',
				]
			]
		],
	],
	'order' => [
		'actions' => [
			'order-export' => 'order-export'
		],
		'subparts' => [
			'invoice' => 'invoice',
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
		'characteristic' => [
			'subparts' => [
				'property' => 'property',
				'variant' => 'variant',
				'attribute' => 'attribute',
				'hidden' => 'hidden',
			],
		],
		'media' => [
			'subparts' => [
				'property' => 'property'
			],
		],
		'option' => [
			'config' => [
				'exclude' => [
					'interval' => 'interval',
				],
			],
			'custom' => [
				'exclude' => [
					'price' => 'price',
				],
			],
			'subparts' => [
				'config' => 'config',
				'custom' => 'custom',
			],
		],
		'price' => [
			'subparts' => [
				'property' => 'property',
			],
		],
		'related' => [
			'subparts' => [
				'suggest' => 'suggest',
				'bought' => 'bought',
			],
		],
		'physical' => [
			'types' => [
				'package-length' => 'package-length',
				'package-height' => 'package-height',
				'package-width' => 'package-width',
				'package-weight' => 'package-weight',
			]
		]
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
		'subparts' => [
			'media' => 'media',
			'text' => 'text',
			'price' => 'price',
		],
		'media' => [
			'subparts' => [
				'property' => 'property',
			],
		],
		'price' => [
			'subparts' => [
				'property' => 'property',
			],
		],
	],
	'supplier' => [
		'domains' => [
			'media' => 'media',
			'supplier/address' => 'supplier/address',
			'text' => 'text',
		],
		'subparts' => [
			'address' => 'address',
			'media' => 'media',
			'text' => 'text',
			'product' => 'product',
		],
		'media' => [
			'subparts' => [
				'property' => 'property',
			],
		],
	],
];
