.. include:: ../Includes.txt


.. _user-animation:

=====================
Animation and effects
=====================

.. _usr-animation:

Animation
=========

Content elements can easily be animated by adding animation classes to the field `Additional classes` found in the
`Appearance` register. Animations are provided by `animate.css <https://github.com/animate-css/animate.css>`__ and the
available animation classes can be obtained from the `demo site <https://animate.style/>`__.

**Example:** To apply a bouncing effect the following classes could be used: `animate__animated animate__bounce`.

.. _usr-scrollanimation:

Scroll animation
================

The scroll animation feature allows to call further attention to content elements while the user is scrolling on the
page. Typically content elements are moved in from the side of the page, faded up, zoomed in or flipped.

A scroll animation can be assigned to a content element by either selecting one of the predefined animations found in
the "Behaviour" section or by assigning data attributes to the "Additional attributes" field in the "Appearance" tab
from the content element properties dialog.

They can be further customized by help of the :ref:`constant editor <config_scrollanimation>`.

Currently the libraries `Josh.js <https://github.com/mamunhpath/josh.js>`__ and `AOS <https://github.com/michalsnik/aos>`__
are supported.

.. warning::
   AOS didn't get updated for quite some time. This is why it has been marked as deprecated hence shouldn't be used for
   new projects any more.

Josh.js
-------

The js library `Josh.js <https://github.com/mamunhpath/josh.js>`__ can be used to animate content elements with the
css library `Animate.css <https://github.com/animate-css/animate.css>`__. To add a scroll animation the related
attributes can be added to the field `Additional attributes`. The available data attributes can be looked up at
`github <https://github.com/mamunhpath/josh.js#advanced-usage>`__.

Example:

.. code-block:: html

   data-josh-anim-name="pulse" data-josh-duration="1500ms" data-josh-delay="3.5s" data-josh-iteration="5"


.. _usr-revealFooter:

Reveal footer
=============

An interesting feature is the footer to be revealed when scrolling down.
The user gets the impression like the content area forms a curtain being lifted
from the footer while scrolling to the end of the page.

To use this effect the constant "Reveal footer" can be set in the constant
editor. The constant can be found in the category "PIZPALUE: CUSTOMER BASE".

