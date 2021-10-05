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
	'countries' => [
		'AF', 'AX', 'AL', 'DZ', 'AS', 'AD', 'AO', 'AI', 'AQ', 'AG', 'AR', 'AM', 'AW', 'AU', 'AT', 'AZ', 'BS', 'BH',
		'BD', 'BB', 'BY', 'BE', 'BZ', 'BJ', 'BM', 'BT', 'BO', 'BQ', 'BA', 'BW', 'BV', 'BR', 'IO', 'UM', 'VG', 'VI',
		'BN', 'BG', 'BF', 'BI', 'KH', 'CM', 'CA', 'CV', 'KY', 'CF', 'TD', 'CL', 'CN', 'CX', 'CC', 'CO', 'KM', 'CG',
		'CD', 'CK', 'CR', 'HR', 'CU', 'CW', 'CY', 'CZ', 'DK', 'DJ', 'DM', 'DO', 'EC', 'EG', 'SV', 'GQ', 'ER', 'EE',
		'ET', 'FK', 'FO', 'FJ', 'FI', 'FR', 'GF', 'PF', 'TF', 'GA', 'GM', 'GE', 'DE', 'GH', 'GI', 'GR', 'GL', 'GD',
		'GP', 'GU', 'GT', 'GG', 'GN', 'GW', 'GY', 'HT', 'HM', 'VA', 'HN', 'HK', 'HU', 'IS', 'IN', 'ID', 'CI', 'IR',
		'IQ', 'IE', 'IM', 'IL', 'IT', 'JM', 'JP', 'JE', 'JO', 'KZ', 'KE', 'KI', 'KW', 'KG', 'LA', 'LV', 'LB', 'LS',
		'LR', 'LY', 'LI', 'LT', 'LU', 'MO', 'MK', 'MG', 'MW', 'MY', 'MV', 'ML', 'MT', 'MH', 'MQ', 'MR', 'MU', 'YT',
		'MX', 'FM', 'MD', 'MC', 'MN', 'ME', 'MS', 'MA', 'MZ', 'MM', 'NA', 'NR', 'NP', 'NL', 'NC', 'NZ', 'NI', 'NE',
		'NG', 'NU', 'NF', 'KP', 'MP', 'NO', 'OM', 'PK', 'PW', 'PS', 'PA', 'PG', 'PY', 'PE', 'PH', 'PN', 'PL', 'PT',
		'PR', 'QA', 'XK', 'RE', 'RO', 'RU', 'RW', 'BL', 'SH', 'KN', 'LC', 'MF', 'PM', 'VC', 'WS', 'SM', 'ST', 'SA',
		'SN', 'RS', 'SC', 'SL', 'SG', 'SX', 'SK', 'SI', 'SB', 'SO', 'ZA', 'GS', 'KR', 'SS', 'ES', 'LK', 'SD', 'SR',
		'SJ', 'SZ', 'SE', 'CH', 'SY', 'TW', 'TJ', 'TZ', 'TH', 'TL', 'TG', 'TK', 'TO', 'TT', 'TN', 'TR', 'TM', 'TC',
		'TV', 'UG', 'UA', 'AE', 'GB', 'US', 'UY', 'UZ', 'VU', 'VE', 'VN', 'WF', 'EH', 'YE', 'ZM', 'ZW'
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
		'actions' => [
			'order-export' => 'order-export'
		],
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
