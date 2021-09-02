<?php

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title'            => 'Pizpalue template',
    'description'      => 'Extension to create websites using bootstrap. It builds upon the bootstrap_package from Benjamin Kott and increases functionality by supporting the following extensions:  container_elements, pp_gridelements, flux_elements, timelog, ws_flexslider, slickcarousel, indexed_search, news, eventnews, tt_address.',
    'category'         => 'template',
    'version'          => '12.0.0-dev',
    'state'            => 'stable',
    'clearCacheOnLoad' => 1,
    'author'           => 'Roman Büchler',
    'author_email'     => 'rb@buechler.pro',
    'constraints'      => [
        'depends'   => [
            'typo3'                 => '10.4.20-11.4.99',
            'bootstrap_package'     => '12.0.1-12.99.99',
            'vhs'                   => '6.0.3-6.99.99',
        ],
        'conflicts' => [],
        'suggests'  => [
            'news'                  => '',
            'pp_gridelements'       => '',
            'flux_elements'         => '',
            'container_elements'    => '',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Buepro\\Pizpalue\\' => 'Classes'
        ],
    ],
];
