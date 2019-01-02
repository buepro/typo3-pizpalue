.. include:: ../Includes.txt


.. _administration:

==============
Administration
==============


.. _admin_installation:

Installation
============

The distribution can be installed from within the distribution list, by uploading the extension and through composer.
Refer to TYPO3 documentation for further details on handling extensions.

.. note::
   Due to a bug it is recommended to install the extension news prior installing the distribution.


.. _admin_update:

Update
======

If a newer version from this distribution is installed its update script should be executed. It can be started through
the update button available in the extension manager.

.. figure:: ../Images/Administration/Update.jpg
   :alt: Distribution update button

   Distribution update button


.. _admin_upgrade:

Upgrade
=======

The following upgrade descriptions are available:

.. toctree::
   :maxdepth: 2

   Upgrade9
   UpgradeBootstrap4


.. _admin_customization:

Customization
=============

The suggested way to customize the distribution for customer projects is to create an extension (e.g. user_customer)
and define the customer theme and functions in it (`see TYPO3 documentation
<https://docs.typo3.org/typo3cms/ExtbaseFluidBook/4-FirstExtension/Index.html>`__).

An example extension for that purpose is delivered and activated with the distribution. You might use it as your
starting point.

.. figure:: ../Images/Administration/Customize.jpg
   :alt: Customize the distribution for customer projects

   Customize the distribution for customer projects

The extension might be deactivated by removing its static template or uninstalling it.

.. note::
   The sample extension "user_customer" is being installed by adding an extension dependency to the ext_emconf.php file.
   By finalizing the installation this dependency is removed (commented) to enable the extension "user_customer" being
   uninstalled with the distribution remaining installed.


.. _admin_development:

Development
===========

During development or maintenance phase two actions might be of interest:

#. Show under construction page
#. Enable code debugging

To temporarily show an under construction page an url redirection might be created to the page "In Arbeit" and the code
debugging might be enabled by activating the debug mode in the "PIZPALUE: AGENCY" category from the constant editor.


.. _admin_news_rss_feed:

News RSS Feed
=============

To provide an RSS feed the following steps could be followed:

#. Create an extension template on a page where the feed should be available
#. Include static template (from extension) "Pizpalue - news RSS feed (pizpalue)"
#. Configure the behaviour using the constant editor (category "PIZPALUE: NEWS RSS")
#. Add "?type=9818&no_cache=1" to the page link to get the feed link

.. note::
   To embed external feeds the extension rss_display might be used. At the time of writing the extension didn't
   provide a view helper to get the url from enclosed images. The branch enclosure-view-helper from fork
   `chesio/rss_display <https://github.com/chesio/rss_display/tree/enclosure-view-helper>`__ provides one.


