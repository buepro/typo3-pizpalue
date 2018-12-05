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
    'version'          => '9.0.0-dev',
    'state'            => 'stable',
    'clearcacheonload' => 1,
    'author'           => 'Roman Büchler',
    'author_email'     => 'rb@buechler.pro',
    'constraints'      => [
        'depends'   => [
            'typo3'                 => '8.7.0-9.99.99',
            'bootstrap_package'     => '10.0.2-10.0.99',
            'vhs'                   => '5.1.0-5.1.99',
            'gridelements'          => '9.0.0-dev',
            'news'                  => '7.0.8-7.0.99',
            'slickcarousel'         => '3.0.1-3.0.99',
            'ws_flexslider'         => '1.5.6-1.5.99',
            //'brt_videourlreplace'   => '1.2.1-1.2.99',
            //'user_customer'         => '9.0.0-9.99.99'
        ],
        'conflicts' => [],
        'suggests'  => [],
    ]
];
