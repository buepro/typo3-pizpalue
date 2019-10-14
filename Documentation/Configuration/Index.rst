.. include:: ../Includes.txt


.. _configuration:

=============
Configuration
=============


.. _config_installtool:

Install tool
============

Consider to review the below mentioned entries in the installtool.

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] = 'd.m.y';
   $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'] = 'H:i';
   $GLOBALS['TYPO3_CONF_VARS']['SYS']['phpTimeZone'] = 'Europe/Zurich';
   $GLOBALS['TYPO3_CONF_VARS']['SYS']['systemLocale'] = 'de_CH.utf8';
   $GLOBALS['TYPO3_CONF_VARS']['BE']['lockSSL'] = true;

Per default TYPO3 writes log entries as well in production context (location `typo3temp/var/log/`). To disable it
the following code might be used in `typo3conf/AdditionalConfiguration.php`:

.. code-block:: php

   if (in_array(\TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext(), ['Production','Production/Staging'])) {
       // Removes the default writer configurations
       $GLOBALS['TYPO3_CONF_VARS']['LOG']['writerConfiguration'] = [];
       // Removes the writer configuration for depreciation log
       $GLOBALS['TYPO3_CONF_VARS']['LOG']['TYPO3']['CMS']['deprecations']['writerConfiguration'][\TYPO3\CMS\Core\Log\LogLevel::NOTICE] = [];
   }

.. tip::
   The above mentioned configurations might be part from the file `typo3conf/AdditionalConfiguration.php`.
   A sample file is provided in the directory `typo3conf/ext/pizpalue/Resources/Private/FolderStructureTemplateFiles/`.


.. _config_siteConfiguration:

Site configuration
==================

With TYPO3 V9 site configuration has been introduced.

The distribution ships with a default site configuration file located in
`typo3conf/ext/pizpalue/Resources/Private/FolderStructureTemplateFiles/sites`. This default configuration won't be
installed automatically.

.. note::
   You might need to adjust the settings for the "Error Handling" as well as for the extension "news" by editing
   the configuration file (typo3conf/sites/pizpalue/config.yaml). Have a look at the following:

   .. code-block:: yaml

      errorContentSource: 't3://page?uid=87' #change this: the number after "uid=" reflects the uid from the 404-page

   .. code-block:: yaml

      routeEnhancers:
         NewsPluginDetail:
            type: Extbase
            limitToPages:
               - 95 #change this: the number reflects the uid from the news detail page

   .. code-block:: yaml

      routeEnhancers:
         #...
         NewsPluginList:
            type: Extbase
            limitToPages:
               - 96 #change this: the number reflects the uid from the news list page


.. _config_constantEditor:

Constant editor
===============

Many aspects from the web site can be configured through the constant editor.

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


.. figure:: ../Images/Configuration/ConstantEditor.jpg
   :alt: Constant editor


.. note::
   Most extensions provide their own configurations. For more detailed configurations consult the related manual.


.. _config_seo:

SEO
===

Prerequisites
-------------

#. Review content (focus on user experience)
#. Review seo tags like title-tag and description meta-tag


TYPO3
-----

You might follow these steps to setup seo features:

#. Create a `site <https://docs.typo3.org/typo3cms/CoreApiReference/ApiOverview/SiteHandling/Index.html>`__ (a sample
   configuration file can be found in the directory
   typo3conf/ext/pizpalue/Resources/Private/FolderStructureTemplateFiles/sites)


.. _config_google:

Google
------

To register the website at Google follow these steps:

#. Register domain as new property at search console
#. Verify ownership by adding google-site-verification code to the related seo constant (see figure below)
#. Register sitemap at search console
#. Register domain in google analytics
#. Add google analytics code to the related seo constant (see figure below)

.. figure:: ../Images/Configuration/ConstantEditorSeo.jpg
   :width: 500px
   :alt: SEO related constants in "PIZPALUE CUSTOMER" category

   SEO related constants in "PIZPALUE CUSTOMER" category


.. _config_scrollanimation:

Scroll animation
================

This feature is using the `AOS-library <http://michalsnik.github.io/aos/>`__ and is enabled by default. It allows to
animate content elements while the page is being scrolled.

Use the constant editor to disable the feature (category "PIZPALUE: CUSTOMER BASE - Features") or configure it
further (category "PIZPALUE: CUSTOMER EXTENDED - Animation").

Four preconfigured animations are available in the animation dropdown menu from the appearance tab in the content
element properties dialog. Those animations might be configured as well in the constant editor (category "PIZPALUE:
CUSTOMER EXTENDED - Animation").


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
