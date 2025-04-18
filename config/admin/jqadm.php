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
			'customer/property' => 'customer/property',
			'group' => 'group',
			'product' => 'product',
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
			'notify' => 'notify',
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
			'transaction' => 'transaction',
		],
	],
	'product' => [
		'domains' => [
			'attribute' => 'attribute',
			'catalog' => 'catalog',
			'media' => 'media',
			'media/property' => 'media/property',
			'price' => 'price',
			'price/property' => 'price/property',
			'product' => 'product',
			'product/property' => 'product/property',
			'stock' => 'stock',
			'supplier' => 'supplier',
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
			'related' => 'related',
			'supplier' => 'supplier',
			'physical' => 'physical',
			'subscription' => 'subscription',
			'download' => 'download',
			'order' => 'order',
		],
		'category' => [
			'types' => ['default', 'promotion'],
		],
		'characteristic' => [
			'attribute' => [
				'types' => ['default', 'variant', 'config', 'custom', 'hidden']
			],
			'subparts' => [
				'property' => 'property',
				'attribute' => 'attribute',
			],
		],
		'media' => [
			'subparts' => [
				'property' => 'property'
			],
		],
		'price' => [
			'subparts' => [
				'property' => 'property',
			],
		],
		'physical' => [
			'types' => [
				'package-length' => 'package-length',
				'package-height' => 'package-height',
				'package-width' => 'package-width',
				'package-weight' => 'package-weight',
			]
		],
		'related' => [
			'types' => ['bought-together', 'suggestion'],
		],
		'supplier' => [
			'types' => ['default', 'promotion'],
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
	'settings' => [
		'api' => [
			'subparts' => [
				'deepl' => 'deepl',
				'openai' => 'openai',
				'removebg' => 'removebg',
			],
		],
		'subparts' => [
			'theme' => 'theme',
			'api' => 'api',
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
	'type' => [
		'domains' => [
			'attribute' => 'attribute',
			'attribute/lists' => 'attribute/lists',
			'attribute/property' => 'attribute/property',
			'catalog/lists' => 'catalog/lists',
			'customer/lists' => 'customer/lists',
			'customer/property' => 'customer/property',
			'media' => 'media',
			'media/lists' => 'media/lists',
			'media/property' => 'media/property',
			'plugin' => 'plugin',
			'price' => 'price',
			'price/lists' => 'price/lists',
			'price/property' => 'price/property',
			'product' => 'product',
			'product/lists' => 'product/lists',
			'product/property' => 'product/property',
			'rule' => 'rule',
			'service' => 'service',
			'service/lists' => 'service/lists',
			'stock' => 'stock',
			'tag' => 'tag',
			'text' => 'text',
			'text/lists' => 'text/lists',
		]
	]
];
