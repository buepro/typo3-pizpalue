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
    'version'          => '16.1.0',
    'state'            => 'stable',
    'clearCacheOnLoad' => 1,
    'author'           => 'Roman Büchler',
    'author_email'     => 'rb@buechler.pro',
    'constraints'      => [
        'depends'   => [
            'typo3'                 => '11.5.0-12.99.99',
            'bootstrap_package'     => '14.0.7-14.99.99',
            'pvh'                   => '2.0.0-2.99.99'
        ],
        'conflicts' => [
            'container_elements'      => '0.0.0-5.0.0'
        ],
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
            'Buepro\\Pizpalue\\Sysext\\Core\\' => 'Sysext/core/Classes',
            'Buepro\\Pizpalue\\Sysext\\Frontend\\' => 'Sysext/frontend/Classes',
            'Buepro\\Pizpalue\\Sysext\\Backend\\' => 'Sysext/backend/Classes',
        ],
    ],
];
