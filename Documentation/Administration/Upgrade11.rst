.. include:: ../Includes.txt

.. _admin_upgrade_11:

=======================
Upgrade to version 11.0
=======================


Introduction
============

The version jump from ``9.5`` to ``11.0`` isn't primary due to new features or breaking changes that were introduced.
It is more related to the close coupling to the extension ``bootstrap_package``. With this version the compatibility
with version ``11`` is given.

Breaking changes
================

Following breaking changes being introduced since version 9.5 will be outlined.

Image variants selector
-----------------------

Introduction
~~~~~~~~~~~~

Normally images are used in content elements spanning the content area which defaults to a maximum width of 1100px.
For one image various sizes are created for the different screen sizes. Their sizes are defined in a variants set in
``typoscript``. In case images are used in a different context (e.g. when spanning the entire page width) different
variants are required.

So far ``pizpalue`` provided a second variants that was automatically selected when no frame was assigned to a content
element (assuming it then spans the entire page width). With this release the automatic selection has been dropped.
The variants of interest can now be selected in the content element images tab.

Corrective action
~~~~~~~~~~~~~~~~~

Review the content for content elements with images that used no frame and check whether the image resolution on big
screens is fine. If not select the ``Full page width`` variants for it.


SCSS variables
--------------

Introduction
~~~~~~~~~~~~

The extension ``user_customer`` uses SCSS variables from ``pizpalue`` that were found under

.. code-block:: css

   @import "../../../../pizpalue/Resources/Public/Scss/Theme/variables";

are now available under

.. code-block:: css

   @import "../../../../pizpalue/Resources/Public/Scss/Theme/BootstrapPackage/variables";

Corrective action
~~~~~~~~~~~~~~~~~

Check if the above mentioned variables are used in your installation and change the path accordingly.

In case the extension ``user_customer`` is in use check the file ``user_customer/Resources/Public/Scss/custom.scss`` for
any occurrence of

.. code-block:: css

   @import "../../../../pizpalue/Resources/Public/Scss/Theme/variables";

and exchange it with

.. code-block:: css

   @import "../../../../pizpalue/Resources/Public/Scss/Theme/BootstrapPackage/variables";

Hints
=====

Social networks
---------------

``VK`` abd ``rss`` are now available for configuration. These channels might be disabled in
``Configuration/TypoScript/constants.typoscript``:

.. code-block:: typoscript

   page.theme {
     socialmedia {
       channels {
         vk.url =
         rss.url =
       }
     }
   }
