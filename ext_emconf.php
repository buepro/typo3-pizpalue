<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title'            => 'Piz Palü Distribution',
    'description'      => 'This distribution is based on the bootstrap package from Benjamin Kott. To provide further flexibility in arranging content the extensions gridelements, news, slickcarousel as well as ws_flexslider are incorporated. The distribution tailors Swiss market featuring German as default language and additional translations to French and English.',
    'category'         => 'distribution',
    'version'          => '9.0.0-dev',
    'state'            => 'stable',
    'clearcacheonload' => 1,
    'author'           => 'Roman Büchler',
    'author_email'     => 'rb@buechler.pro',
    'constraints'      => [
        'depends'   => [
            'typo3'                 => '8.7.0-9.99.99',
            'bootstrap_package'     => '10.0.5-10.0.99',
            'vhs'                   => '5.1.0-5.1.99',
            'gridelements'          => '8.3.0-9.0.99',
            'news'                  => '7.0.8-7.0.99',
            'slickcarousel'         => '2.0.0-3.0.99',
            'ws_flexslider'         => '1.5.6-1.5.99',
            'indexed_search'        => '8.7.17-9.99.99',
            //'brt_videourlreplace'   => '1.2.1-1.2.99',
            'user_customer'         => '9.0.0-9.99.99',
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
