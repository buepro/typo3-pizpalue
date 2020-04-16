.. include:: ../Includes.txt

.. _faq_administration:

==============
Administration
==============

Installation
============

**Q**: How can the distribution be installed or updated with the source code from Github?

**A**: Follow these steps:

#. Create a backup (data base as well as typo3conf folder)
#. Uninstall the extensions ``user_customer`` and ``pizpalue`` in the extension manager
#. Update supported extensions (e.g. ``news``, ``tt_address``, ``timelog``) as needed. If possible use :ref:`tested versions <admin_installation_supported_extensions>`.
#. Delete the folder ``typo3conf/ext/pizpalue``
#. Download the source code from `github <https://github.com/buepro/typo3-pizpalue/archive/master.zip>`__
#. Upload the downloaded zip-file to ``typo3conf/ext``
#. Extract the files
#. Rename the new folder from ``typo3-pizpalue-master`` to ``pizpalue``
#. Install the extension ``pizpalue``
#. Install the extension ``user_customer``

