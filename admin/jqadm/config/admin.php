<?php

return [
	'jqadm' => [
		'attribute' => [
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
		],
		'catalog' => [
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
				'global' => ['Index', 'Cache'],
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
		'product/category' => [
			'decorators' => [
				'local' => ['Cache'],
			],
		],
		'service' => [
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
		],
		'supplier' => [
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
	],
];
