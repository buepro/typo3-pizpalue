<?php

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title'            => 'Pizpalue template',
    'description'      => 'Extension to create websites using bootstrap. It builds upon the bootstrap_package from Benjamin Kott and increases functionality by supporting the following extensions:  container_elements, news, eventnews, tt_address',
    'category'         => 'template',
    'version'          => '12.6.1',
    'state'            => 'stable',
    'clearCacheOnLoad' => 1,
    'author'           => 'Roman Büchler',
    'author_email'     => 'rb@buechler.pro',
    'constraints'      => [
        'depends'   => [
            'php'                   => '7.3.0-8.0.99',
            'typo3'                 => '10.4.20-11.5.99',
            'bootstrap_package'     => '12.0.5-12.0.99',
            'pvh'                   => '1.0.0-1.99.99'
        ],
        'conflicts' => [],
        'suggests'  => [
            'container_elements'    => '',
            'easyconf'              => '',
            'eventnews'             => '5.0.0',
            'flux_elements'         => '',
            'news'                  => '',
            'pp_gridelements'       => '',
            'tt_address'            => '',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Buepro\\Pizpalue\\' => 'Classes',
            'Buepro\\Pizpalue\\Easyconf\\' => 'Extensions/easyconf/Classes',
            'Buepro\\Pizpalue\\Form\\' => 'Extensions/form/Classes',
        ],
    ],
];
