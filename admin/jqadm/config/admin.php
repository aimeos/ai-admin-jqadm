<?php

return array(
	'jqadm' => array(
		'common' => array(
			'decorators' => array(
				'default' => array( 'Page' ),
			),
		),
		'product' => array(
			'decorators' => array(
				'global' => array( 'Index', 'Cache' ),
			),
		),
		'product/category' => array(
			'decorators' => array(
				'local' => array( 'Cache' ),
			),
		),
	),
);