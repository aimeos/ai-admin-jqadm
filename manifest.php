<?php

return array(
	'name' => 'ai-admin-jqadm',
	'depends' => array(
		'aimeos-core',
	),
	'config' => array(
		'admin/jqadm/config',
	),
	'include' => array(
		'admin/jqadm/src',
		'lib/custom/src',
	),
	'i18n' => array(
		'admin' => 'admin/i18n',
		'admin/ext' => 'admin/i18n/ext',
	),
	'custom' => array(
		'admin/jqadm' => array(
			'admin/jqadm/manifest.jsb2',
		),
		'admin/jqadm/templates' => array(
			'admin/jqadm/templates',
		),
	),
);
