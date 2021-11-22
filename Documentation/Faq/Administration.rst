.. include:: ../Includes.txt

.. _faq_administration:

==============
Administration
==============

Installation
============

**Q**: How can the extension be installed or updated with the source code from Github?

**A**: Follow these steps:

#. Create a backup (data base as well as typo3conf folder)
#. Uninstall the extensions `user_customer` and `pizpalue` in the extension manager
#. Update supported extensions (e.g. `news`, `tt_address`, `timelog`) as needed. If possible use :ref:`tested versions <admin_installation_supported_extensions>`.
#. Delete the folder `typo3conf/ext/pizpalue`
#. Download the source code from `github <https://github.com/buepro/typo3-pizpalue/archive/master.zip>`__
#. Upload the downloaded zip-file to `typo3conf/ext`
#. Extract the files
#. Rename the new folder from `typo3-pizpalue-master` to `pizpalue`
#. Install the extension `pizpalue`
#. Install the extension `user_customer`

---

**Q**: I have to use `gridelements` and `container` together. After updating `container` to version 1.3.0 the container
child elements aren't shown any more. How can this be fixed?

**A**: It might be related to the initial installation sequence from the structure extensions. You might try to order
the package listing in `typo3conf/PackageStates.php`:

.. code-block:: php

   'pvh' => [
      'packagePath' => 'typo3conf/ext/pvh/',
   ],
   'gridelements' => [
      'packagePath' => 'typo3conf/ext/gridelements/',
   ],
   'pp_gridelements' => [
      'packagePath' => 'typo3conf/ext/pp_gridelements/',
   ],
   'flux' => [
      'packagePath' => 'typo3conf/ext/flux/',
   ],
   'flux_elements' => [
      'packagePath' => 'typo3conf/ext/flux_elements/',
   ],
   'container' => [
      'packagePath' => 'typo3conf/ext/container/',
   ],
   'container_elements' => [
      'packagePath' => 'typo3conf/ext/container_elements/',
   ],

Translation
===========

**Q**: How can I translate strings to my language (e.g. for the cookie dialog)?

**A**: To contribute translating an extension consult the chapter
`Localization with Crowdin <https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/Internationalization/TranslationServer/Crowdin.html>`__
in the `TYPO3 Explained <https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/Index.html>`__ documentation.
The string to be translated most likely belongs to either the bootstrap_package or the pizpalue extension.
Alternatively labels can be overridden with TS. For more details see
`_LOCAL_LANG.[lang-key].[label-key] <https://docs.typo3.org/m/typo3/reference-typoscript/master/en-us/TopLevelObjects/Plugin.html#local-lang-lang-key-label-key>`__
from the
`plugin chapter <https://docs.typo3.org/m/typo3/reference-typoscript/master/en-us/TopLevelObjects/Plugin.html>`__
in the `TypoScript template reference <https://docs.typo3.org/m/typo3/reference-typoscript/master/en-us/Index.html>`__
manual.


Design
======

**Q**: The browser shows an error related to the favicon. What is wrong?

**A**: Most likely the fav- and appicon files aren't available or the icon header data isn' correct.
To create and add the icons follow these steps:

#. Create the icons and the header code at `realfavicongenerator.net <https://realfavicongenerator.net/>`__.
#. Store the assets to the website root directory.
#. Merge the header code provided by `realfavicongenerator.net <https://realfavicongenerator.net/>`__ to one line.
#. In the constant editor paste the one line header code to the `Header data` field, section `App icon` from the category `PIZPALUE: CUSTOMER BASE`.
