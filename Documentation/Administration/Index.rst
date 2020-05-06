.. include:: ../Includes.txt


.. _administration:

==============
Administration
==============

.. _admin_installation:

Installation
============

Refer to TYPO3 documentation for further details on
`installing extensions <https://docs.typo3.org/m/typo3/guide-installation/master/en-us/ExtensionInstallation/Index.html>`__.

.. _admin_installation_supported_extensions:

Supported extensions
--------------------

================================ ================
Extension                        Version tested
================================ ================
pp_gridelements                  1.0.0
flux_elements                    1.0.2
slickcarousel                    3.0.3
ws_flexslider                    1.5.12
indexed_search                   9.5.14
news                             7.3.1
tt_address                       5.0.0
timelog                          1.5.3
================================ ================

.. _admin_update:

Update
======

After updating this extension in the extension manager the data base structure should be analysed in the maintenance
module.

.. _admin_upgrade:

Upgrade
=======

The following upgrade descriptions are available:

.. toctree::
   :maxdepth: 2

   Upgrade9
   Upgrade11
   UpgradeBootstrap4


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

To temporarily show an under construction page an url redirection might be created and the code debugging might be
enabled by setting the site mode in the "PIZPALUE: AGENCY" category from the constant editor.
