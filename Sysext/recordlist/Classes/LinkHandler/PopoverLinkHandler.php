<?php declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pizpalue.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pizpalue\Sysext\Recordlist\LinkHandler;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Recordlist\Controller\AbstractLinkBrowserController;
use TYPO3\CMS\Recordlist\LinkHandler\LinkHandlerInterface;
use TYPO3Fluid\Fluid\View\ViewInterface;

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
 * ATTENTION: The needed javascript (ReqyureJS module) could not be loaded from `Sysext/recordlist/Resources/Public/
 * JavaScript/Src`. It remains at `Resources/Public/JavaScript/Src/Sysext/Recordlist/`.
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
     * @var IconFactory
     */
    protected $iconFactory;

    /**
     * @var ?ViewInterface
     */
    protected $view;

    /**
     * @var PageRenderer
     */
    protected $pageRenderer;

    /**
     * Initialize the handler
     *
     * @param AbstractLinkBrowserController $linkBrowser
     * @param string $identifier
     * @param array $configuration Page TSconfig
     */
    public function initialize(AbstractLinkBrowserController $linkBrowser, $identifier, array $configuration): void
    {
        $this->linkBrowser = $linkBrowser;
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $this->pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
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
        return ($linkParts['type'] ?? '') === 'pppopover';
    }

    public function formatCurrentUrl()
    {
        return '';
    }

    /**
     * Introduced with feature "Use SVG Trees in Record Selector" (#a79bd29)
     *
     * @param ViewInterface $view
     */
    public function setView(ViewInterface $view): void
    {
        /** @phpstan-ignore-next-line */
        $view->setTemplateRootPaths(array_merge(
            $view->getTemplateRootPaths(), /** @phpstan-ignore-line */
            [GeneralUtility::getFileAbsFileName('EXT:pizpalue/Sysext/recordlist/Resources/Private/Templates/LinkBrowser')]
        ));
        /** @phpstan-ignore-next-line */
        $view->setTemplate('PpPopover');
        $this->view = $view;
    }

    private function assignVariablesToView(ViewInterface $view): void
    {
        if (isset($this->linkParts['url']['href'])) {
            $view->assign('href', $this->linkParts['url']['href']);
        } else {
            $view->assign('href', 'void');
        }
        if (isset($this->linkParts['url']['content'])) {
            $content = htmlspecialchars_decode($this->linkParts['url']['content']);
            $content = str_replace(['·', '<br />'], [' ', "\r\n"], $content);
            $view->assign('content', $content);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     * @deprecated Used for TYPO3 v10 only
     */
    private function renderStandalone(ServerRequestInterface $request): string
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->getRequest()->setControllerExtensionName('pizpalue');
        $view->setTemplateRootPaths([GeneralUtility::getFileAbsFileName(
            'EXT:pizpalue/Sysext/recordlist/Resources/Private/Templates/LinkBrowserStandalone'
        )]);
        $view->setTemplate('PpPopover');
        $this->assignVariablesToView($view);
        /** @extensionScannerIgnoreLine */
        return $view->render('PpPopover');
    }

    public function render(ServerRequestInterface $request)
    {
        $this->pageRenderer->addRequireJsConfiguration(['paths' => [
            'Buepro/Pizpalue/Sysext/Recordlist' =>
                '/typo3conf/ext/pizpalue/Sysext/recordlist/Resources/Public/JavaScript']]);
        $this->pageRenderer->loadRequireJsModule('Buepro/Pizpalue/Sysext/Recordlist/PopoverLinkHandler');
        if (!isset($this->view)) {
            /** @phpstan-ignore-next-line */
            return $this->renderStandalone($request);
        }
        $this->assignVariablesToView($this->view);
        return '';
    }
}
