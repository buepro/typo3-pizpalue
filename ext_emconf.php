<?php

########################################################################
# Extension Manager/Repository config file for ext "pizpalue".
########################################################################

$EM_CONF[$_EXTKEY] = [
    'title'            => 'Piz Palü Distribution',
    'description'      => 'This distribution is based on TYPO3 version 8 and the bootstrap package from Benjamin Kott.',
    'category'         => 'distribution',
    'version'          => '8.2.7',
    'state'            => 'stable',
    'clearcacheonload' => 1,
    'author'           => 'Roman Büchler',
    'author_email'     => 'rb@buechler.pro',
    'constraints'      => [
        'depends'   => [
            'typo3'                 => '8.0.0-8.99.99',
            'bootstrap_package'     => '8.0.2',
            'static_info_tables'    => '6.4.3-6.5.1',
            'vhs'                   => '4.2.0-4.3.3',
            'realurl'               => '2.2.1-2.3.2',
            'dd_googlesitemap'      => '2.1.3-2.1.4',
            'gridelements'          => '8.0.0',
            'bootstrap_grids'       => '1.4.0',
            'sr_language_menu'      => '6.4.2-6.4.3',
            'news'                  => '6.0.0-6.3.0',
            'brt_videourlreplace'   => '1.2.1-1.2.4',
            'slickcarousel'         => '2.0.0',
            'ws_flexslider'         => '1.5.3-1.5.5',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ]
];
