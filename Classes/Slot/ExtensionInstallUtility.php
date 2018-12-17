<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 17/12/2018
 * Time: 13:40
 */

namespace Buepro\Pizpalue\Slot;

use TYPO3\CMS\Core\Core\Environment;

class ExtensionInstallUtility
{

    /**
     * Comment the dependency to extension user_customer.
     * The object is just to install user_customer once on the system.
     *
     * @param $extensionKey
     */
    public function afterExtensionInstall($extensionKey)
    {
        if ($extensionKey !== 'pizpalue') return;

        $emconfFile = Environment::getPublicPath() . '/typo3conf/ext/pizpalue/ext_emconf.php';
        $content = file_get_contents($emconfFile);
        $commentToken = '// commented by install process';
        if (strstr($content,$commentToken) === false) {
            $content = str_replace("'user_customer'",$commentToken . " 'user_customer'",$content);
            file_put_contents($emconfFile,$content);
        }
        return;
    }

}