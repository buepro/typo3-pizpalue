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
    'version'          => '11.0.0-dev',
    'state'            => 'stable',
    'clearcacheonload' => 1,
    'author'           => 'Roman Büchler',
    'author_email'     => 'rb@buechler.pro',
    'constraints'      => [
        'depends'   => [
            'typo3'                 => '9.5.7-10.1.99',
            'indexed_search'        => '9.5.6-9.5.99',
            'bootstrap_package'     => '10.0.7-11.1.99',
            'vhs'                   => '5.2.0-5.2.99',
            'gridelements'          => '9.2.2-9.2.99',
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
