.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _user-manual:

============
Users Manual
============

Following some hints are given regarding the use from the additions enhancing
the user experience.

Please refere as well to the manual user section from the
related extensions.


.. _ce-attributes:

Attribute enhancement for content elements
==========================================

Sometimes it would be handy to directly alter attributes from a content element container: add an additional class,
some inline style or additional attributes.

This functionality has been added by introducing additional fields to the content element table and adapting the
rendering accordingly. The new fields are available under the appearance tab in the "Attributes" palette.

.. figure:: ../Images/User/ContentElementAttributes.jpg
   :width: 500px
   :alt: Palette "Attributes" under the appearance tab in the content element form

   Palette "Attributes" under the appearance tab in the content element form


.. _user-bootstrap_grids:

Grid elements
=============

The add new content element wizards shows a tab called "Grid Elements":

.. figure:: ../Images/User/GridElements_Wizard.jpg
   :width: 500px
   :alt: "Grid Elements"-tab in new content element wizard

   "Grid Elements"-tab in new content element wizard

.. hint::
   Editing the "Appearance"-properties from a grid element doesn't alter the
   rendering hence the selections for "Frame" or "Space Before" have no influence
   to the contents appearance in the page.

   .. figure:: ../Images/User/GridElements_Appearance.jpg
      :alt: Appearance settings for grid elements don't change the rendering

      Appearance settings for grid elements don't change the rendering

.. tip::
   To influence the rendering the grid elements might be wrapped by a
   container grid element. The container grid elements allow to define
   additional classes.


.. _user-news:

News
====

For the news system a template "Image on top" is provided. It might be used to
render the image on top of the text (`see example <https://www.pizpalue.buechler.pro/das-plus/news/>`__).

.. figure:: ../Images/User/News_Template_ImageOnTop.jpg
   :alt: News template to render image on top

   News template to render image on top


.. _user-customframes:

Custom frames
=============

Additional frames can be selected for content elements (`see example <https://www.pizpalue.buechler.pro/das-plus/stilergaenzungen/>`__):

.. figure:: ../Images/User/CustomFrames.jpg
   :alt: Custom frames for content elements

   Custom frames for content elements

As for the time beeing the custom animations (Custom animation 1..3) aren't
implemented yet.


.. _user-inlineLocalization:

Inline localization
===================

Sometimes it might be handy to have a text used at different locations available
in several languages. For this users might reference a translated text by using
curly brackets within the editor (`see example <https://www.pizpalue.buechler.pro/das-plus/lokalisierung/>`__):

.. figure:: ../Images/User/InlineLocalization.jpg
   :alt: Use of localized text within the editor

   Use of localized text within the editor

.. tip::
   You might reference other content as defined by the
   `"getText" data type <https://docs.typo3.org/typo3cms/TyposcriptReference/DataTypes/Gettext/Index.html>`__.


.. _user-flexContentWidth:

Flexible content width
======================

To create content spanning the entire page width (`see example <https://www.pizpalue.buechler.pro/das-plus/flexible-inhaltsbreite/>`__)
one might use the "Full width" page layout in conjunction with the "Bootstrap
fix container" and "Full with container" grid elements:

.. figure:: ../Images/User/ContentWidth_PageAppearance.jpg
   :alt: "Full width" page layout

   "Full width" page layout

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


.. _usr-scrollanimation:

Scroll animation
================

The scroll animation feature allows to call further attention to content elements while the user is scrolling on the
page. Typically content elements are moved in from the side of the page.

To use this feature follow these steps:

1. Make sure the feature is enabled for the page where it is used (:ref:`see scroll animation configuration <config_scrollanimation>`)
2. Select a "Custom scroll animation" as a frame or specify the data attributes in the appearance tab from the content element

.. figure:: ../Images/User/ScrollAnimation_Appearance.png
   :width: 500px
   :alt: Define the scroll animation either with a frame or withe data attributes

   Define the scroll animation either with a frame or withe data attributes


.. _usr-revealFooter:

Reveal footer
=============

An interesting feature is the footer to be revealed when scrolling down.
The user gets the impression like the content area forms a curtain being lifted
from the footer while scrolling to the end of the page.

To use this effect the constant "Reveal footer" can be set in the constant
editor. The constant can be found in "PIZPALUE: Customer - Frame".

.. note::
   This feature is using "position: fixed" which causes flickering in edge
   browsers. To correct the flickering "transform: translateZ(0)" is assigned to
   the preceeding sibling container. This cancels any fixed positioning in
   the sibling container. The situation might be improved by assigning
   "transform: translateZ(0)" to the last content element in the preceeding
   sibling element. In any case the element having "transform: translateZ(0)"
   assigned should be higher than the footer.


.. _usr-ppClasses:

Piz Palue classes
=================

============================= ==================================================
Class                         Usage
============================= ==================================================
pp-label-dataprotection       Used in wrapper grid element to define a
                              replacment text for a data protection notice check
                              box (see contact page)
============================= ==================================================
