.. include:: ../Includes.txt


.. _administration_fastmenu:

========
Fastmenu
========

By enabling the fastmenu a icon menu is shown on the right page border.

The menu items can be associated with a page or a content. In case a content is referenced it will be shown beside
the menu.

The following configurations are available:

============================ =========================================================================================
Category                     Purpose
============================ =========================================================================================
PIZPALUE:CUSTOMER BASE       To enable and disable the menu
PIZPALUE:CUSTOMER EXTENDED   To define the icon and referenced content/page
PIZPALUE:CUSTOMER STYLE      To define colors
============================ =========================================================================================

.. note::
   The amount of menu items can be adjusted through TS. To add a new item the following code might be used.

   .. codeblock::

      pizpalue.menu.fast.items {
         newItem {
            iconClass = ppicon ppicon-log-in
            contentUid =
            pageUid =
         }
      }

.. note::
   Embed new icons by generating your own icon font.