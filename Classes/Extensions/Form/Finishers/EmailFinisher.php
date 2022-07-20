<?php
declare(strict_types = 1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Extensions\Form\Finishers;

/**
 * @deprecated
 */
class EmailFinisher extends \Buepro\Pizpalue\Form\Finishers\EmailFinisher
{
    /**
     * @deprecated since v12, will be removed in v14
     */
    public function __construct()
    {
        trigger_error(
            __CLASS__ . 'will be removed in pizpalue v14, use `\Buepro\Pizpalue\Form\Finishers\EmailFinisher` instead.',
            E_USER_DEPRECATED
        );
        parent::__construct();
    }
}
