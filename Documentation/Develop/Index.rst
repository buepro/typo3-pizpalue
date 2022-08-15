.. include:: /Includes.rst.txt


.. _develop:

=======
Develop
=======

Work with site
==============

Installation
------------

#. Clone the repository

   .. code-block:: bash

      git clone https://github.com/buepro/typo3-pizpalue.git

#. Create site

   .. code-block:: bash

      composer ddev:install

Development
-----------

Mount extension directories
~~~~~~~~~~~~~~~~~~~~~~~~~~~

To work at the same time on other packages mount local directories to the
container by adjusting and adding the following script to
`.ddev/docker-compose.mount.yaml`:

.. code-block:: yaml

   services:
     web:
       volumes:
         # Mount local directory to the web containers path `/mnt/public/package`
         - "~/Projects/public:/mnt/public/package"


Work with the container
~~~~~~~~~~~~~~~~~~~~~~~

Use the ddev container during development. Like this the system environment
is being respected. E.g.:

.. code-block:: bash

   ddev composer update

Uninstallation
--------------

To remove the development site use:

.. code-block:: bash

   composer ddev:uninstall

Create tests
============

Create test db with PhpMyAdmin
------------------------------

.. rst-class:: bignums

#. Clean up the db

#. Select tables `pages, sys_template, tt_content`

#. Set format specific options as following
   *  Enclose columns with `"`
   *  Escape columns with `"`
   *  Replace NULL by `\NULL`


Add needed extensions
---------------------

It might be needed to add the following extensions:

.. code-block:: php

   protected $coreExtensionsToLoad = [
      'impexp',
      'seo',
   ];

.. code-block:: php

   protected $testExtensionsToLoad = [
      'typo3conf/ext/pvh',
      'typo3conf/ext/container',
      'typo3conf/ext/container_elements',
      'typo3conf/ext/bootstrap_package',
      'typo3conf/ext/pizpalue',
   ];

Notes
=====
-  Bootstrap package sync status:
   - Branch BP-12.0: 22.04.2022 / commit 16734ba6
   - Branch BP-12.1: 26.11.2021 / commit 028a786b
