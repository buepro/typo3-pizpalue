.. include:: ../Includes.txt


.. _configuration:

=============
Configuration
=============

.. _config_constantEditor:

Constant editor
===============

Many aspects from the web site can be configured through the constant editor.

.. figure:: ../Images/Configuration/ConstantEditor.jpg
   :alt: Constant editor

To ease configuration parameters were grouped. The following main categories
are available:

============================ =========================================================
Category                     Description
============================ =========================================================
PIZPALUE: ADMINISTRATION     Everything related to the site administration
PIZPALUE: CUSTOMER BASE      Main customer related parameters
PIZPALUE: CUSTOMER STYLE     More detailed customer style related parameters
PIZPALUE: CUSTOMER SOCIAL    More detailed customer social network related parameters
PIZPALUE: CUSTOMER EXTENDED  More detailed customer related parameters
PIZPALUE: CUSTOMER PLUGINS   More detailed plugin related parameters
============================ =========================================================

.. note::
   Most extensions provide their own configurations. For more detailed configurations consult the related manual.


.. _config_scaffold:

Scaffold
========

Footer columns
--------------

The behaviour from the footer columns can be adjusted through the column classes available under the category
`PIZPALUE: CUSTOMER EXTENDED` in the constant editor.


.. _config_scrollanimation:

Scroll animation
================

This feature is using one of the libraries `Twikito/scroll-effect <https://github.com/Twikito/onscroll-effect>`__ or
`Josh.js <https://github.com/mamunhpath/josh.js>`__. They allows to animate content elements while the page is being
scrolled.

Four preconfigured animations are available in the animation dropdown menu from the appearance tab in the content
element properties dialog. Those animations might be configured as well in the constant editor (category "PIZPALUE:
CUSTOMER EXTENDED - Animation").

.. hint::
   Embedding from scroll animation js-libraries is triggered by the presence from the strings `data-scroll` or
   `data-josh` in a content element attribute field.


.. _config_cookieconsent:

Cookie consent
==============

To show a cookie dialog the "Enable Cookie Consent"-parameter has to be set (constant editor category "PIZPALUE:
CUSTOMER BASE - Cookie consent"). As well a link to a privacy policy page can be set for the cookie dialog.

Further configurations regarding the cookie dialog can be found in the constant editor by selecting the category
"PIZPALUE: CUSTOMER EXTENDED" under the section "Cookie consent".

.. note::
   For Google Analytics a control block can be embedded by using the string ###GoogleAnalyticsStatus### in a content
   element.


.. _config_appIcons:

App icons
=========

In case just a simple favicon is required it can be specified in the category "PIZPALUE - CUSTOMER BASE" from the
constants editor.

To get an app icon set for all major platforms the resources might be generated at the
`"Favicon generator" <https://realfavicongenerator.net/>`__ website. The resulting resources need to be copied to the
server web directory and the header data assigned to the related field in the app icon section in the category
"PIZPALUE - CUSTOMER BASE" from the constants editor. Remove any line breaks.


.. _config_fastmenu:

Fastmenu
========

By enabling the fastmenu an icon menu is shown on the right page border.

The menu items can be associated with a page or a content. In case a content is referenced it will be shown beside
the menu.

The following configurations are available:

================================ =====================================================================================
Category                         Purpose
================================ =====================================================================================
PIZPALUE:CUSTOMER BASE           To enable and disable the menu
PIZPALUE:CUSTOMER EXTENDED       To define the icon and referenced content/page
PIZPALUE:CUSTOMER STYLE          To define colors
================================ =====================================================================================

.. note::

   The amount of menu items can be adjusted through TS. To add a new item the following code might be used.

   .. code-block:: typoscript

      pizpalue.menu.fast.items {
         newItem {
            iconClass = ppicon ppicon-log-in
            contentUid =
            pageUid =
         }
      }

.. note::

   Embed new icons by generating your own icon font.


.. _config_extensions:

Extensions
==========

For some extensions additional configurations are available:

.. toctree::
   :maxdepth: 1

   Extensions/Eventnews
   Extensions/News


.. _config_seo:

SEO
===

#. Review content (focus on user experience)
#. Review seo tags like title-tag and description meta-tag
#. Create a `sitemap <https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/XmlSitemap/Index.html>`__
#. Create a `site <https://docs.typo3.org/typo3cms/CoreApiReference/ApiOverview/SiteHandling/Index.html>`__

.. hint::

   In the site configuration specify a `robots.txt` as static route containing a sitemap. This helps search engines
   to crawl the site and further registrations at search engines might not be needed. An example `robots.txt` might
   look as following:

   ..code-block:: txt

      User-agent: *
      Disallow: /typo3/
      Disallow: /typo3_src/
      Allow: /typo3/sysext/frontend/Resources/Public/*

      Sitemap: https://www.domain.ch/sitemap.xml


Google
------

If needed follow these steps to setup and register the site at google:

#. Register domain as new property at search console
#. Verify ownership by adding google-site-verification code to the related seo constant (see figure below)
#. Register sitemap at google search console
#. Register domain in google analytics
#. Add google analytics code to the related seo constant (see figure below)

.. figure:: ../Images/Configuration/ConstantEditorSeo.jpg
   :width: 500px
   :alt: SEO related constants in "PIZPALUE CUSTOMER" category

   SEO related constants in "PIZPALUE CUSTOMER" category
