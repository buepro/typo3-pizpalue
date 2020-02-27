<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title'            => 'Piz Palü Distribution',
    'description'      => 'This extension builds upon the bootstrap_package from Benjamin Kott. It installs gridelements for better content structuring and some galleries. Indexed_search, news, tt_address and timelog are supported and just need to be installed. The distribution tailors Swiss market featuring German as default language and additional translations to French, English and Finnish.',
    'category'         => 'distribution',
    'version'          => '11.1.0',
    'state'            => 'stable',
    'clearCacheOnLoad' => 1,
    'author'           => 'Roman Büchler',
    'author_email'     => 'rb@buechler.pro',
    'constraints'      => [
        'depends'   => [
            'typo3'                 => '9.5.7-9.5.99',
            'bootstrap_package'     => '11.0.2-11.99.99',
            'vhs'                   => '6.0.0-6.99.99',
            'gridelements'          => '9.5.0-9.99.99',
            'slickcarousel'         => '3.0.3-3.99.99',
            'ws_flexslider'         => '1.5.12-1.99.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
    'autoload' => [
        'psr-4' => [
            'Buepro\\Pizpalue\\' => 'Classes'
        ],
    ],
];
