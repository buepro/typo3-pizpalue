.. include:: /Includes.rst.txt

.. image:: https://poser.pugx.org/buepro/typo3-pizpalue/v/stable.svg
   :alt: Latest Stable Version
   :target: https://extensions.typo3.org/extension/pizpalue/

.. image:: https://img.shields.io/badge/TYPO3-11-orange.svg
   :alt: TYPO3 11
   :target: https://get.typo3.org/version/11

.. image:: https://img.shields.io/badge/TYPO3-10-orange.svg
   :alt: TYPO3 10
   :target: https://get.typo3.org/version/10

.. image:: https://poser.pugx.org/buepro/typo3-pizpalue/d/total.svg
   :alt: Total Downloads
   :target: https://packagist.org/packages/buepro/typo3-pizpalue

.. image:: https://poser.pugx.org/buepro/typo3-pizpalue/d/monthly
   :alt: Monthly Downloads
   :target: https://packagist.org/packages/buepro/typo3-pizpalue

.. image:: https://github.com/buepro/typo3-pizpalue/workflows/CI/badge.svg
   :alt: Continuous Integration Status
   :target: https://github.com/buepro/typo3-pizpalue/actions?query=workflow%3ACI

.. _introduction:

============
Introduction
============

Preface
=======

This extension started as a distribution and is now a template serving as a base
foundation for new websites. The former distribution part became to be a new
distribution called `pizpalue_distribution` and can be downloaded from
`the TYPO3 extension repository <https://extensions.typo3.org/extension/pizpalue_distribution>`__.

What does it do?
================

The extension builds upon the extension `bootstrap_package from Benjamin Kott <https://extensions.typo3.org/extension/bootstrap_package>`__
and serves as a base template for the creation of websites or
`distributions <https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ExtensionArchitecture/CreateNewDistribution/Index.html>`__
using the `bootstrap framework <https://getbootstrap.com/>`__. As an example the
distribution `pizpalue_distribution <https://extensions.typo3.org/extension/pizpalue_distribution>`__
uses this extension. A demo site can be visited `here <http://pizpalue.buechler.pro/das-plus/>`__.

The extension extends and configures TYPO3 and selected extensions to provide
better structured and attracting content while maintaining or improving speed
and seo performance.

It provides the following main features:

Easy website setup
------------------

The extension `easyconf <https://extensions.typo3.org/extension/easyconf>`__
has been configured to provide an easy way for non technical website owners
to setup their website. The following is taken care of automatically:

-  Domain related settings used in the content and site configuration
-  SEO related settings in the site configuration for "page not found",
   `robots.txt` and `sitemap.xml`
-  News related configurations in site configuration
-  Logo and app icon configurations
-  Google font configurations

.. figure:: ../Images/Introduction/Easyconf.jpg
   :alt: Website configuration form

   Website configuration form

Enhancing page
--------------

-  Background image to cover the entire page
-  CSS field to easily fine tune individual page styles

Arranging content
-----------------

-  Page layouts allowing to add content in side columns with a menu
-  Support for structure elements provided by
   `container_elements <https://extensions.typo3.org/extension/container_elements>`__.
   Example structure elements are: columns, grid, accordion, tabs, card, tile unit
   and randomizer

Enhancing content
-----------------

-  Support for automatic image scaling in structure elements (see
   :ref:`templating <integration_templating>`)
-  Content element fields to optimize image scaling dependent on the screen size
-  Content element fields to assign custom classes, styles and attributes
-  Content element fields to create a :ref:`scroll menu <user-scrollmenu>`

Animating content
-----------------

-  Animation effects provided `animate.css <https://github.com/animate-css/animate.css>`__
-  Scroll animation effects provided by `Twikito/onscroll-effect <https://github.com/Twikito/onscroll-effect>`__,
   or `Josh.js <https://github.com/mamunhpath/josh.js>`__
-  A feature to reveal the footer

New content elements
--------------------

-  A content element to create modal dialogs
-  A content element to create a list of categorized content elements
-  A content element to create structured content (JSON-LD data, see :ref:`user-contentElements-schema`)
-  A layout for media content elements to emphasize media

Supporting extensions
---------------------

The following extensions are supported:

-  `container_elements <https://extensions.typo3.org/extension/container_elements>`__
-  `easyconf <https://extensions.typo3.org/extension/easyconf>`__
-  `news <https://extensions.typo3.org/extension/news>`__
-  `eventnews <https://extensions.typo3.org/extension/eventnews>`__
-  `tt_address <https://extensions.typo3.org/extension/tt_address>`__

Various
-------

-  A menu to be fixed on the side (fastmenu)

Discover more regarding the features on the `demo site <http://pizpalue.buechler.pro/das-plus/>`__.

Screenshots
===========

The following screen shots were taken from a website created with the distribution
`pizpalue_distribution <https://extensions.typo3.org/extension/pizpalue_distribution>`__.

Structure elements
------------------

`See example <https://pizpalue.buechler.pro/das-plus/strukturemente>`__

.. figure:: ../Images/Introduction/StructureElements.jpg
   :width: 700px
   :alt: Page embedding contents covering entire page width

   Page embedding contents covering entire page width

Scroll animation
----------------

`See example <https://pizpalue.buechler.pro/das-plus/inhaltselemente/animation>`__

.. figure:: ../Images/Introduction/ScrollAnimationPage.jpg
   :width: 700px
   :alt: Page embedding scroll animated content

   Page embedding scroll animated content

Modal dialog
------------

See as well `demo site <https://pizpalue.buechler.pro/das-plus/inhaltselemente/modaler-dialog>`__.

.. figure:: ../Images/Introduction/ModalDialog.jpg
   :width: 700px
   :alt: Modal dialog

News
----

`See example <https://pizpalue.buechler.pro/das-plus/news/>`__

.. figure:: ../Images/Introduction/NewsPage.jpg
   :width: 700px
   :alt: Page embedding news

   Page embedding news

Credits
=======

This extension can be provided thanks to outstanding work from TYPO3 community members. A big thanks goes to:

-  Benjamin Kott, extension `bootstrap_package <https://extensions.typo3.org/extension/bootstrap_package>`__
-  B13 GmbH, extension `container <https://extensions.typo3.org/extension/container>`__
-  Georg Ringer, extensions `news <https://extensions.typo3.org/extension/news>`__,
   `eventnews <https://extensions.typo3.org/extension/eventnews>`__
-  tt_address Development Team, extension `tt_address <https://extensions.typo3.org/extension/tt_address>`__
