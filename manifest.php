<?php

return array(
	'name' => 'ai-admin-jqadm',
	'depends' => array(
		'aimeos-core',
	),
	'include' => array(
		'admin/jqadm/src',
	),
	'i18n' => array(
		'admin' => 'admin/i18n',
	),
	'custom' => array(
		'admin/jqadm/templates' => array(
			'admin/jqadm/templates',
		),
	),
);
