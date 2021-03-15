<?php

declare(strict_types=1);

/*
 * This file is part of the package buepro/pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Cms\Recordlist\LinkHandler;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Recordlist\Controller\AbstractLinkBrowserController;
use TYPO3\CMS\Recordlist\LinkHandler\LinkHandlerInterface;

/**
 * Defines the popover register from the link browser.
 *
 * Due to link builder limitations just the popover content will be handled here.
 * The content is assigned to the href attribute in the form:
 *
 * `t3://pppopover?href=[href]&content=[content]`
 *
 * `href` is used to create the actual content from the href tag. In case it contains `void` the uri becomes
 * `javascript:void(0);` otherwise its content is obtained by stdWrap.typolink.
 *
 * Hint: Is used in BE when opening the link browser.
 *
 */
class PopoverLinkHandler implements LinkHandlerInterface
{
    /**
     * Available additional link attributes
     *
     * @var string[]
     */
    protected $linkAttributes = ['class', 'title'];

    /**
     * Parts of the current link
     *
     * @var array
     */
    protected $linkParts = [];

    /**
     * @var AbstractLinkBrowserController
     */
    protected $linkBrowser;

    /**
     * @var \TYPO3\CMS\Fluid\View\StandaloneView
     */
    protected $view;

    /**
     * Initialize the handler
     *
     * @param AbstractLinkBrowserController $linkBrowser
     * @param string $identifier
     * @param array $configuration Page TSconfig
     */
    public function initialize(AbstractLinkBrowserController $linkBrowser, $identifier, array $configuration)
    {
        $this->linkBrowser = $linkBrowser;
        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        $this->view->getRequest()->setControllerExtensionName('recordlist');
        $this->view->setTemplateRootPaths([
            GeneralUtility::getFileAbsFileName('EXT:recordlist/Resources/Private/Templates/LinkBrowser'),
            GeneralUtility::getFileAbsFileName('EXT:pizpalue/Resources/Private/Templates/Cms/LinkBrowser')
        ]);
        $this->view->setPartialRootPaths([GeneralUtility::getFileAbsFileName('EXT:recordlist/Resources/Private/Partials/LinkBrowser')]);
        $this->view->setLayoutRootPaths([GeneralUtility::getFileAbsFileName('EXT:recordlist/Resources/Private/Layouts/LinkBrowser')]);
    }

    /**
     * @param string[] $fieldDefinitions Array of link attribute field definitions
     * @return string[]
     */
    public function modifyLinkAttributes(array $fieldDefinitions)
    {
        return $fieldDefinitions;
    }

    public function getLinkAttributes()
    {
        return $this->linkAttributes;
    }

    public function getBodyTagAttributes()
    {
        return [];
    }

    public function isUpdateSupported()
    {
        return false;
    }

    public function canHandleLink(array $linkParts)
    {
        $this->linkParts = $linkParts;
        return $linkParts['type'] === 'pppopover';
    }

    public function formatCurrentUrl()
    {
        return '';
    }

    public function render(ServerRequestInterface $request)
    {
        GeneralUtility::makeInstance(PageRenderer::class)->loadRequireJsModule('TYPO3/CMS/Pizpalue/Src/PopoverLinkHandler');
        if (isset($this->linkParts['url']['href'])) {
            $this->view->assign('href', $this->linkParts['url']['href']);
        } else {
            $this->view->assign('href', 'void');
        }
        if (isset($this->linkParts['url']['content'])) {
            $content = htmlspecialchars_decode($this->linkParts['url']['content']);
            $content = str_replace(['·', '<br />'], [' ', "\r\n"], $content);
            $this->view->assign('content', $content);
        }
        /** @extensionScannerIgnoreLine */
        return $this->view->render('PpPopover');
    }
}
