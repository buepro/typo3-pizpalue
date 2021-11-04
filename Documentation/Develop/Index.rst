.. include:: ../Includes.txt


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

Use the ddev container during development. Like this the system environment
is being respected. E.g.:

.. code-block:: bash

   ddev composer update

Uninstallation
--------------

To remove the development site use:

.. code-block:: bash

   composer ddev:delete

Notes
=====
-  Bootstrap package sync status: 23.9.2021 / commit ad453360
