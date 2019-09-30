.. include:: ../Includes.txt

.. _faq_administration:

==============
Administration
==============

Installation
============

**Q**: The following error message shows up in the front end: Oops, an error occurred!
Error handler could not fetch error page "/pages", reason: Couldn't get URL: /pages

**A**: The error might be related to the site configuration. You might try the following:

#. Change to the "Sites" module
#. Delete the entry point
#. Save
#. Clear cache
#. Redefine the entry point
#. In the register "Error handling" delete all, save and create new entry

---

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


Upgrade
=======

**Q**: Can I upgrade an existing site to use the latest TYPO3 version with "Pizpalue"? The objective is to maintain the
page tree and the content elements.

**A**: The distribution imports a default page tree when being installed the first time. In case this isn't desired
the file :file:`typo3conf/ext/pizpalue/Initialisation/data.xml` as well as the folder
:file:`typo3conf/ext/pizpalue/Initialisation/data.xml.files` need to be deleted prior installing the distribution
for the first time. To upgrade TYPO3 and install the distribution without importing the page tree the following steps
might be carried out:

#. Uninstall all extensions
#. Upgrade TYPO3
#. Download the zipped version from Pizpalue from Github
#. Delete the file :file:`../Initialisation/data.xml` as well as the folder :file:`../Initialisation/data.xml.files`
#. Upload the extension directory to the server
#. Install the extension pizpalue in the extension manager
#. Include pizpalue "Include static (from extensions)" on root template
#. Include pizpalue "Page TS config" on root page (page properties - Resources)
