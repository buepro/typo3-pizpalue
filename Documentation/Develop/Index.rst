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

Use local packages
~~~~~~~~~~~~~~~~~~

To work at the same time on other packages (e.g. `user_pizpalue`) carry out the
following steps:

#. Mount local directories to the container by adjusting and adding the following
   script to `.ddev/docker-compose.mount.yaml`:

   .. code-block:: yaml

      services:
        web:
          volumes:
            # Mount local directory to the web containers path `/mnt/public/package`
            - "~/Projects/public:/mnt/public/package"

#. Add the package repository to the composer configuration:

   .. code-block:: json

      {
         "repositories": {
            "user-pizpalue": {
               "type": "path",
               "url": "/mnt/public/package/typo3-user_pizpalue"
            }
         }
      }

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

Documentation
=============

Create upgrade description
--------------------------

#. `git log v13.2.0..7ab170cc --pretty="* %s (%cd, %h)%n+++%n%n%b%n"
   --date=format:%d.%m.%Y --abbrev-commit --grep "\!\!\!" > Upgrade.rst`

#. Format headings

Notes
=====
-  Bootstrap package sync status:

   - Branch BP_14_0: v14.0.7, 14.08.2023 / commit 45333312
