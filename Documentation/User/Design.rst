.. include:: ../Includes.txt


.. _user-design:

======
Design
======

The arrangement and appearance from content elements can be defined on different areas. The coarse structure is defined
with the page layout where grid elements might further organize the content in columns, registers, and the like. Fine
tuning can be done on a content element basis by altering the frame, attributes and
background.


.. _user-design-page:

Page layout
===========


.. _user-design-flexContentWidth:

Flexible content width
----------------------

To create content spanning the entire page width (`see example <https://www.pizpalue.buechler.pro/das-plus/gestaltung/>`__)
a "Full width" page layout in conjunction with the "Bootstrap fix container" and "Full with container" grid elements
might be used:

.. figure:: ../Images/User/ContentWidth_PageAppearance.jpg
   :alt: "Full width" page layouts

   "Full width" page layouts

.. figure:: ../Images/User/ContentWidth_Container.jpg
   :width: 500px
   :alt: Container grid element

   Container grid element

.. tip::
   To further customize the containers css-classes, attributes and a wrapping
   container might be defined in the "Plugin Options" section from the
   "General"-tab:

   .. figure:: ../Images/User/ContentWidth_ContainerOptions.jpg
      :width: 500px
      :alt: Container grid element plugin options

      Container grid element plugin options


.. _user-design-gridelements:

Grid elements
=============

Grid elements are used to further structure the content. Typically columns, accordions and registers are used for this
purpose. The available grid elements can be found in the new content element wizard or the content properties form
under the "Grid Elements" tab:

.. figure:: ../Images/User/GridElements_Wizard.jpg
   :width: 500px
   :alt: "Grid Elements"-tab in new content element wizard

   "Grid Elements"-tab in new content element wizard


.. _user-design-contentElement:

Content element
===============

.. _user-design-customframes:

Custom frames
-------------

Additional frames can be selected for content elements (`see example <https://www.pizpalue.buechler.pro/das-plus/gestaltung/rahmen/>`__):

.. figure:: ../Images/User/CustomFrames.jpg
   :alt: Custom frames for content elements

   Custom frames for content elements

As for the time being the custom animations (Custom animation 1..3) aren't implemented yet.


.. _user-design-attributes_background:

Attributes and background for content elements
----------------------------------------------

Sometimes it would be handy to directly alter attributes and assign a background image to content elements.

This functionality has been added by introducing additional fields to the content element table and adapting the
rendering accordingly. The new fields are available under the appearance tab in the "Attributes" and "Background"
palette.

.. figure:: ../Images/User/ContentElement_Appearance.jpg
   :width: 500px
   :alt: Palette "Attributes" and "Background" under the appearance tab in the content element form

   Palette "Attributes" and "Background" under the appearance tab in the content element form

