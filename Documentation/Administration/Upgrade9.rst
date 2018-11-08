.. include:: ../Includes.txt

.. _administration_upgrade_9:

======================
Upgrade to version 9.0
======================

Introduction
============

The goal from this distribution is to facilitate a robust foundation using as view extensions as possible. Using less 
extensions reduces the footprint and improves the upgrade experience.

With release 9 the distribution has been adapted to TYPO3 9LTS and the bootstrap_package 10. The major changes include:

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
-  Remove dependency to extensions realurl, dd_googlesitemap and bootstrap_gridelements
-  Switch CSS preprocessing from Less to Scss


Upgrading the distribution from earlier versions (e.g. the PP_8-6 branch) includes the tasks preparation, upgrading
and reviewing.


Preparation
===========

-  Backup the data (files and data base)
-  Consider using the gridelements from the distribution instead of the ones provided by the extension bootstrap_grids.
   The distribution won't support the extension bootstrap_grids in the near future any more.

Upgrading
=========

-  Uninstall the distribution pizpalue as well as the extensions realurl, dd_googlesitemap, bootstrap_grids
-  Install the bootstrap_package (version 10.x.x)
-  Install the distribution pizpalue (version 9.x.x)
-  Add static template "Pizpalue - Upgrade" to map constants

Reviewing
=========

Bootstrap
---------

Using Bootstrap 3
~~~~~~~~~~~~~~~~~

In case an existing installation requires to continue using bootstrap 3 the static template "Pizpalue - Bootstrap 3
Rendering" can be added to the static templates section.

Where just css/less related definitions need to be preserved the static template "Pizpalue - Bootstrap 3 LESS
(load less constants only)" can be added to the static templates section.

Using Bootstrap 4
~~~~~~~~~~~~~~~~~

In case the actual site doesn't use more extensive adjustments the site should be fine after defining the constants
as outlined in :ref:`"Upgrade to Bootstrap 4"<administration_upgrade_bottstrap4_reviewConstants>`.

Where further customer adjustments are in place most likely the customer template needs to be adjusted.

Social network
--------------

The social network feature has been replaced by the one provided by the bootstrap_package. To update the social network
embedding follow these steps:

#. Remove the block mark **###SocialNetwork###** in the content element.
#. Add a "Social Links" content element from the "Social Media" tab.
#. Configure the content through the constant editor, category "BOOTSTRAP PACKAGE: SOCIAL MEDIA"

Cookie consent
--------------

Fonts
-----
