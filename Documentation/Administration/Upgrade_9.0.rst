.. include:: ../Includes.txt

.. _admin_upgrade_9:

======================
Upgrade to version 9.0
======================

Introduction
============

The goal from this distribution is to facilitate a robust foundation using as view extensions as possible. Using less 
extensions reduces the footprint and improves the upgrade experience.

With release 9 the distribution has been adapted to bootstrap_package 10 supporting TYPO3 9LTS. The major changes
include:

`TYPO3: <https://typo3.org/article/typo3-v9-lts-youre-the-one-that-i-want/>`__

-  Speaking URLs Out of the Box 
-  Search Engine Optimization (SEO) 
-  Site Management
-  Major Backend Changes
-  System Maintenance Area
-  Conditional Variants for Form Elements
-  General Data Protection Regulation (GDPR) 

`bootstrap_package: <https://github.com/benjaminkott/bootstrap_package>`__

-  Bootstrap 4 using scss
-  Cookie consent
-  Content elements
-  Background images for content elements

As a result the distribution needed to be refactored and adapted significantly:

-  Apply latest naming conventions
-  Switch CSS preprocessing from Less to Scss
-  Adapt to content rendering
-  Remove dependency to extensions realurl, dd_googlesitemap, url_forwarding, bootstrap_grids, sr_language_menu,
   brt_videourlreplace, static_info_tables

Upgrading the distribution from earlier versions (e.g. the PP_8-6 branch) includes the tasks preparation, upgrading
and reviewing.


Preparation
===========

-  Consider using the gridelements from the distribution instead of the ones provided by the extension bootstrap_grids.
   The distribution won't support the extension bootstrap_grids in the near future any more.
-  Backup the data (files and data base)


Upgrading
=========

.. _upgrade9_basic_upgrade_procedure:

Basic upgrade procedure (using Bootstrap 3.x (LESS))
----------------------------------------------------

-  If the extension user_customer is present uninstall it and remove the dependencies to extension pizpalue and
   bootstrap_package in the file typo3conf/ext/user_customer/ext_emconf.php (to avoid recursive calls during installing
   the extension pizpalue)
-  Uninstall the extension pizpalue
-  Update extension bootstrap_package (10.x.x), vhs, news, ws_flexslider
-  Update extensions gridelements, slickcarousel **according TYPO3 version**
-  Install the distribution pizpalue (version 9.x.x)
-  Add static templates "Bootstrap Package: Full Package", "Bootstrap Package: Bootstrap 3.x (LESS)"
-  Add static templates "Pizpalue - Main", "Pizpalue - Upgrade9", "Pizpalue - Bootstrap 3.x (LESS)",
   "Pizpalue - Gridelements CEs", "Pizpalue - news", "Pizpalue - slickcarousel", "Pizpalue - Gridelements rendering"
-  In case extension user_customer was used add static template "Customer"
-  On the root page (Properties - Resources) include TSConfig "Pizpalue - Content elements",
   "Pizpalue - Extension gridelements", "Pizpalue - Extension news"
-  Delete unused extensions

.. note::
   The correct order for the static templates is:
      #. Bottstrap Package related templates
      #. Extension related templates
      #. Pizpalue related templates starting with "Pizpalue - Main"
      #. Customization related templates (e.g. from user_customer)
      #. Pizpalue - Gridelements rendering


Upgrade bootstrap (using Bootstrap 4.x (SCSS))
----------------------------------------------

Upgrading bootstrap isn't required. To use bootstrap 4 follow these steps:

-  Carry out the :ref:`basic upgrade procedure<upgrade9_basic_upgrade_procedure>`
-  Follow the description as outlined in :ref:`"Upgrade to Bootstrap 4"<admin_upgrade_bottstrap4>`.


Reviewing
=========

Page layouts / Content elements
-------------------------------

The bootstrap_package introduced a new way of content rendering allowing content element containers to span the entire
page width. Assigning colors and images to the background from the content element container leads to the page being
grouped in horizontal sections.

The new way of content rendering had an impact on the page layouts as well as the content elements hence they need
to be reviewed.


Social network
--------------

The social network feature has been replaced by the one provided by the bootstrap_package. To update the social network
embedding follow these steps:

#. Remove the block mark **###SocialNetwork###** in the content element.
#. Add a "Social Links" content element from the "Social Media" tab.
#. Configure the content through the constant editor, category "PIZPALUE: CUSTOMER SOCIAL"


Various
-------

-  Review the constants "PIZPALUE" in the constants editor.

