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


.. _usr-ppClasses:

Piz Palue classes
=================

============================= =============================================================================
Class                         Usage
============================= =============================================================================
pp-label-dataprotection       Used in wrapper grid element to define a replacement text for a data
                              protection notice check box (see contact page)
pp-parent-height              Elements using this class will get the same height as their parent element
pp-row-height                 Elements in a row using this class will have the same height
pp-row-child-height           Elements in a row using this class will have their direct child elements
                              harmonized. Each child element will have the same height as its neighbour
                              element in an other column (all headers, all images, all Text blocks
                              have the same height).
pp-ce-background              Centers the background and sizes it to cover the area.
pp-ce-bgfixed                 Fixes the background. The result is a parallax effect. Due to mobile devices
                              not supporting this feature fully it is generally disabled on mobile devices.
pp-ce-margin                  Applies a margin to the container
pp-ce-padding                 Applies a padding to the container
pp-ce-bgwhite70               Applies a white background with 70% opacity
pp-ce-gray70                  Applies a gray background with 70% opacity
pp-ce-bgblack70               Applies a black background with 70% opacity
============================= =============================================================================