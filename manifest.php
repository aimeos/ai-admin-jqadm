<?php

return [
	'name' => 'ai-admin-jqadm',
	'depends' => [
		'aimeos-core',
	],
	'config' => [
		'config',
	],
	'include' => [
		'admin/jqadm/src',
		'lib/custom/src',
	],
	'i18n' => [
		'admin' => 'admin/i18n',
		'admin/ext' => 'admin/i18n/ext',
	],
	'custom' => [
		'admin/jqadm' => [
			'admin/jqadm/manifest.jsb2',
		],
	],
	'template' => [
		'admin/jqadm/templates' => [
			'admin/jqadm/templates',
		],
	],
];
