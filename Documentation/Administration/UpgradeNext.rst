.. include:: ../Includes.txt

.. _admin_upgrade_11.4:

==========================================
Upgrade to version NEXT (not released yet)
==========================================

PageTS configurations (???)
=================================

In order to ease usage all pageTS config is now added
automatically. This applies as well to configurations
related to TCEFORM, content elements and supported
extensions.

To manually add the configurations the parameter
`enableDefaultPageTSconfig` can be disabled and the
individual configurations can be added through the page
properties (Field `Page TSconfig`, register `Resources`).
The parameter `enableDefaultPageTSconfig` can be found
in the module `Settings`, card `Extension Configuration`,
section `pizpalue`.

Possible impact
---------------

Installations using their own configurations might get
their configuration mixed up.

Corrective action
-----------------

Disable adding default PageTS configurations in the
module `Settings`.

Pizpalue content elements (50d55658)
====================================

Up to now pizpalue content elements needed to be added by adding the related static template. In effort to simplify
configuration this additional configuration has been removed.

Possible impact
---------------

Installations where pizpalue content elements are not desired will have them now available.

Corrective action
-----------------

The main static template from pizpalue can be replaced by a custom template.

Scroll animation `josh.js`
==========================

Since AOS didn't get updated for quite some time Josh.js has been introduced. AOS animations defined by the drop down
menu (content element - Appearance - behaviour section) have been replaced by their Josh.js counter part.

Possible impact
---------------

The replacement might lead to changes in already  defined animations (e.g. there is no animation when the element
gets back into the viewport).

Additionally the configuration has been restructured which might lead to a wrong initialization from the animation module.

Corrective action
-----------------

Review scroll animated content elements and if needed configure them separately with TS constants.

Example:

.. code-block:: typoscript
   pizpalue.animation {
      aos.initParams = easing: 'ease-in-out-sine'
      1.attributes = data-aos="fade-up-right"
   }
