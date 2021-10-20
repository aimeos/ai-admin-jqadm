<?php

/**
 * @todo 2022.01 Use dots instead of empty strings to avoid problems when using TypoScript
 */

return [
	0 => 'dashboard',
	10 => [
		'_' => 'sales',
		10 => 'order',
		20 => 'subscription',
	],
	20 => [
		'_' => 'goods',
		10 => 'product',
		20 => 'catalog',
		30 => 'attribute',
		40 => 'supplier',
	],
	30 => [
		'_' => 'users',
		10 => 'customer',
		20 => 'group',
	],
	40 => [
		'_' => 'marketing',
		10 => 'coupon',
		20 => 'rule',
		30 => 'review',
	],
	50 => [
		'_' => 'configuration',
		10 => 'settings',
		20 => 'service',
		30 => 'plugin',
	],
	60 => [
		'_' => 'locale',
		10 => 'locale',
		20 => 'locale/site',
		30 => 'locale/language',
		40 => 'locale/currency',
	],
	70 => [
		'_' => 'type',
		10 => 'type/attribute',
		20 => 'type/attribute/lists',
		30 => 'type/attribute/property',
		40 => 'type/catalog/lists',
		50 => 'type/customer/lists',
		60 => 'type/customer/property',
		70 => 'type/media',
		80 => 'type/media/lists',
		90 => 'type/media/property',
		100 => 'type/plugin',
		110 => 'type/price',
		120 => 'type/price/lists',
		130 => 'type/price/property',
		140 => 'type/product',
		150 => 'type/product/lists',
		160 => 'type/product/property',
		170 => 'type/service',
		180 => 'type/service/lists',
		190 => 'type/stock',
		200 => 'type/tag',
		210 => 'type/text',
		220 => 'type/text/lists',
	],
	80 => 'log',
];
