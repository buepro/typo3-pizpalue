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
    'version'          => '11.1.2-dev',
    'state'            => 'stable',
    'clearCacheOnLoad' => 1,
    'author'           => 'Roman Büchler',
    'author_email'     => 'rb@buechler.pro',
    'constraints'      => [
        'depends'   => [
            'typo3'                 => '9.5.7-10.1.99',
            'bootstrap_package'     => '11.0.0-11.1.99',
            'vhs'                   => '5.2.0-5.2.99',
            'gridelements'          => '9.3.0-9.3.99',
            'slickcarousel'         => '3.0.2-3.0.99',
            'ws_flexslider'         => '1.5.10-1.5.99',
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
