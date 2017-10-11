<?php

########################################################################
# Extension Manager/Repository config file for ext "pizpalue".
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Piz Palü Distribution',
	'description' => 'This distribution is based on TYPO3 version 8 and the bootstrap package from Benjamin Kott.',
	'category' => 'distribution',
	'version' => '8.1.1',
	'state' => 'stable',
	'clearcacheonload' => 1,
	'author' => 'Roman Büchler',
	'author_email' => 'rb@synac.com',
	'constraints' => array(
		'depends' => array(
			'typo3' => '8.0.0-8.99.99',
			'bootstrap_package' => '8.0.0-8.99.99',
            'bpsynplate' => '8.0.0-8.99.99'
		),
		'conflicts' => array(),
		'suggests' => array(),
	)
);