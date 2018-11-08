.. include:: ../Includes.txt


.. _administration_upgrade_bottstrap4:

======================
Upgrade to bootstrap 4
======================

Bootstrap 4 uses Sass as CSS preprocessor where Less was used in bootstrap 3. The two preprocessors mark variables
different (Less: @, Sass: $) and have different function sets.

By introducing bootstrap 4 in the bootstrap_package some constant names changed as well.

Since the preprocessor parser provided by the bootstrap_package doesn't substitute TS-constants upgrading to bootstrap 4
requires some manual intervention.


.. _administration_upgrade_bottstrap4_reviewConstants:

Review constants
================

The following table lists all the bootstrap constants used by the distribution that might need to be reviewed:

Namespace
---------

========================================= =========================================
Bootstrap 3                               Bootstrap 4
========================================= =========================================
(plugin.bootstrap_package.settings.less)  (plugin.bootstrap_package.settings.scss)
========================================= =========================================

Category "customer base"
------------------------

Subcategory "colors"
~~~~~~~~~~~~~~~~~~~~

========================================= =========================================
Bootstrap 3                               Bootstrap 4
========================================= =========================================
*brand-primary*                           *primary*
*brand-secundary*                         *secondary*
*brand-complementary*                     *complementary*
body-bg                                   body-bg
*navbar-default-bg*                       *navbar-light-bg*
footer-bg                                 footer-bg
========================================= =========================================

Subcategory "frame"
~~~~~~~~~~~~~~~~~~~

========================================= =========================================
Bootstrap 3                               Bootstrap 4
========================================= =========================================
navbar-height                             navbar-height
========================================= =========================================

Category "customer style"
-------------------------

Subcategory "colors"
~~~~~~~~~~~~~~~~~~~~

========================================= =========================================
Bootstrap 3                               Bootstrap 4
========================================= =========================================
*navbar-default-link-color*               *navbar-light-color*
*navbar-default-link-hover-color*         *navbar-light-hover-color*
*navbar-default-link-hover-bg*            *navbar-light-hover-bg*
*navbar-default-link-active-color*        *navbar-light-active-color*
*navbar-default-link-active-bg*           *navbar-light-active-bg*
*navbar-default-link-disabled-color*      *navbar-light-disabled-color*
*navbar-default-link-disabled-bg*         *navbar-light-disabled-bg*
footer-color                              footer-color
footer-link-color                         footer-link-color
footer-link-hover-color                   footer-link-hover-color
footer-link-hover-decoration              footer-link-hover-decoration
pp-tab-v1-background                      pp-tab-v1-background
pp-tab-v1-active-background               pp-tab-v1-active-background
pp-tab-v1-border-color                    pp-tab-v1-border-color
========================================= =========================================


.. _administration_upgrade_bottstrap4_reviewContent:

Review content
==============

========================================= =========================================================
Element/component                         Replacement
========================================= =========================================================
Frame Jumbotron                           Has been dropped. Color background might be used instead
========================================= =========================================================