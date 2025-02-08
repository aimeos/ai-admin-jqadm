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
		'src',
	],
	'i18n' => [
		'admin' => 'i18n',
		'admin/code' => 'i18n/code',
	],
	'custom' => [
		'admin/jqadm' => [
			'manifest.jsb2',
		],
	],
	'template' => [
		'admin/jqadm/templates' => [
			'templates/admin/jqadm',
		],
	],
];
