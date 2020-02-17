.. include:: ../Includes.txt

.. _faq_user:

====
User
====

Menu
====

**Q**: The side menu doesn't show up on mobile devices. How can a user navigate to pages referenced to in the side
menu on mobile devices? Why is the toggle menu not showing links to those pages?

**A**: Based on common practice the toggle menu shouldn't show more than two levels. Hence additional levels might be
shown on top or bottom from the main content. This feature isn't implemented yet.

To add a menu from sub pages a content element could be used (e.g. in the border or side section from the page). By
assigning the class ``d-lg-none`` (tab ``Appearance``, section ``Attributes``) the element wouldn't be shown on bigger
screens.

To show the side menu on mobile devices as it is on desktops the following scss can be used:

.. code-block:: scss

   .backendlayout-subnavigation_right,
   .backendlayout-subnavigation_left {
      .frame-type-subnavigation {
         display: block !important;
      }
   }
