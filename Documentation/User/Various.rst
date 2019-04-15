.. include:: ../Includes.txt


.. _user-various:

=======
Various
=======


.. _user-extensions:

Extensions
==========

This distribution provides some additions to extensions. Additional information might be found in the manual user
section from the related extensions.

.. _user-news:

News
----

For the news system a template "Image on top" is provided. It might be used to
render the image on top of the text (`see example <https://www.pizpalue.buechler.pro/das-plus/news/>`__).

.. figure:: ../Images/User/News_Template_ImageOnTop.jpg
   :alt: News template to render image on top

   News template to render image on top


.. _user-data-getText:

Data from type getText
======================

The `getText data type <https://docs.typo3.org/typo3cms/TyposcriptReference/DataTypes/Index.html#gettext>`__ allows
to get various data from a web site. As an example a translated text might be retrieved depending on the currently
selected page language. For this users might reference data by using curly brackets within the editor
(`see example <https://www.pizpalue.buechler.pro/das-plus/gestaltung/attribute/wissenswertes>`__):

.. figure:: ../Images/User/InlineLocalization.jpg
   :alt: Use of localized text within the editor

   Use of localized text within the editor

.. tip::
   You might reference other content as defined by the
   `"getText" data type <https://docs.typo3.org/typo3cms/TyposcriptReference/DataTypes/Gettext/Index.html>`__.


.. _usr-ppClasses:

Pizpalue classes
=================

============================= =============================================================================
Class                         Usage
============================= =============================================================================
pp-cf                         Micro clearfix hack
pp-label-dataprotection       Used in content element to define a replacement text for a data
                              protection notice check box (see contact page)
pp-parent-height              Elements using this class will get the same height as their parent element
pp-row-height                 Elements in a row using this class will have the same height
pp-row-child-height           Elements in a row using this class will have their direct child elements
                              harmonized. Each child element will have the same height as its neighbour
                              element in an other column. The class just works with "Text with images"
                              content elements.
pp-ce-background              Centers the background and sizes it to cover the area.
pp-ce-bgfixed                 Fixes the background. The result is a parallax effect. Due to mobile devices
                              not supporting this feature fully it is generally disabled on mobile devices.
pp-content-margin             Applies a margin to the container
pp-content-padding            Applies a padding to the container
pp-content-bgwhite70          Applies a white background with 70% opacity
pp-content-bggrey70           Applies a grey background with 70% opacity
pp-content-bgblack70          Applies a black background with 70% opacity
pp-gallery-item-left          Aligns the gallery items (e.g. images) to the left
pp-gallery-item-right         Aligns the gallery items to the right
pp-gallery-item-join          Joins the gallery items by removing any margin and padding
============================= =============================================================================

