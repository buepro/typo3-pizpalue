<?php

/*
 * This file is part of the package buepro/user_customer.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title'            => 'Customer template',
    'description'      => 'Customer template used with distribution pizpalue',
    'category'         => 'templates',
    'author'           => 'Roman Büchler',
    'author_email'     => 'rb@buechler.pro',
    'state'            => 'stable',
    'internal'         => '',
    'uploadfolder'     => '0',
    'createDirs'       => '',
    'clearCacheOnLoad' => 0,
    'version'          => '11.1.2',
    'constraints'      => [
        'depends'   => [
            'typo3'         => '9.5.0-10.99.99'
        ],
        'conflicts' => [
        ],
        'suggests'  => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Buepro\\UserCustomer\\' => 'Classes'
        ],
    ],
];
