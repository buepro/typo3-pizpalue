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


.. _admin_supported_extensions:

Supported extensions
--------------------

The following extensions are supported and just need to be installed.

================================ ================
Extension                        Version tested
================================ ================
indexed_search                   9.5.8
news                             7.3.1
tt_address                       4.3.0
timelog                          1.2.1
================================ ================

.. note::
   In case an other extension version is used it is recommended to create a backup before installing it.


.. _admin_update:

Update
======

After updating this distribution in the extension manager the data base structure should be analysed in the maintenance
module.


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


.. _admin_extensions:

Extensions
==========

For some extensions additional packages are available:

.. toctree::
   :maxdepth: 1

   Extensions/Form
   Extensions/News
   Extensions/TtAddress
   Extensions/Felogin


.. _admin_development:

Development
===========

During development or maintenance phase two actions might be of interest:

#. Show under construction page
#. Enable code debugging

To temporarily show an under construction page an url redirection might be created to the page "In Arbeit" and the code
debugging might be enabled by setting the site mode in the "PIZPALUE: AGENCY" category from the constant editor.
