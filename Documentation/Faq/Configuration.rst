.. include:: ../Includes.txt

.. _faq_configuration:

=============
Configuration
=============

Layout/Design/Theme
===================

**Q**: How can I change the design (colors, header, footer, etc.)?

**A**: The easiest way is to change TS constants through the constant editor.
Watch out for categories starting with `PIZPALUE:`. Please note that changes
are applied to the page where the constant is defined and all sub-pages.

---

**Q**: I have to adapt more aspects from the template for my project. As well it
would be good to use Git in the work flow. What is the best way to do it?

**A**: For that purpose an extension should be used. An example extension is
available at `Github <https://github.com/buepro/typo3-user_pizpalue>`__.

Header
------

**Q**: The start page uses a sticky header. How can I get a sticky header on
all pages?

**A**: The extension user_pizpalue defines this behaviour
(see :file:`typo3conf/ext/user_pizpalue/Configuration/TypoScript/constants.typoscript` and
:file:`typo3conf/ext/user_pizpalue/Configuration/TypoScript/Default/constants.typoscript`)
with a condition. The easiest way to overrule it is by changing the TS constant
:typoscript:`page.theme.navigation.type` (found in PIZPALUE: CUSTOMER BASE,
section Menu). Alternatively the extension user_pizpalue might be adapted.

---

**Q**: How can I get rid of the breadcrumb menu?

**A**: At this time PIZPALUE doesn't provide a constant for it. You might change
it by selecting the constant category BOOTSTRAP PACKAGE: NAVIGATION (constant
title "Breadcrumb").

---

**Q**: How can I use an other font in the main menu?

**A**: See `bootstrap package wiki pages <https://github.com/benjaminkott/bootstrap_package/wiki>`__


Fastmenu
========

**Q**: I would like to use a SVG-Icon in the fastmenu. How can I do that?

**A**: You might define a class for the icon to be rendered and adapt scss/css
as needed.

Example TS:

.. code-block:: typoscript

   pizpalue.menu.fast.items {
      newItem {
         iconClass = uc-fastmenu-icon1
      }
   }

Example CSS:

.. code-block:: css

   .uc-fastmenu-icon1 {
      background-image: url(path/to/icon.svg)
   }
