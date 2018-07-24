<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title'            => 'Piz Palü Distribution',
    'description'      => 'This distribution is based on TYPO3 version 8 and the bootstrap package from Benjamin Kott. It is tailored for the Swiss market featuring German as default language and additional translations to French and English.',
    'category'         => 'distribution',
    'version'          => '8.3.4',
    'state'            => 'stable',
    'clearcacheonload' => 1,
    'author'           => 'Roman Büchler',
    'author_email'     => 'rb@buechler.pro',
    'constraints'      => [
        'depends'   => [
            'typo3'                 => '8.0.0-8.99.99',
            'indexed_search'        => '8.7.10-8.7.99',
            'bootstrap_package'     => '8.0.2-8.0.99',
            'static_info_tables'    => '6.4.3-6.5.99',
            'vhs'                   => '4.2.0-5.0.99',
            'dd_googlesitemap'      => '2.1.3-2.1.99',
            'gridelements'          => '8.0.0',
            'bootstrap_grids'       => '1.4.0',
            'sr_language_menu'      => '6.4.2-6.4.99',
            'news'                  => '6.0.0-6.3.99',
            'brt_videourlreplace'   => '1.2.1-1.2.99',
            'slickcarousel'         => '2.0.0-2.0.99',
            'ws_flexslider'         => '1.5.3-1.5.99',
            'url_forwarding'        => '1.3.1-1.3.99'
        ],
        'conflicts' => [],
        'suggests'  => [],
    ]
];
