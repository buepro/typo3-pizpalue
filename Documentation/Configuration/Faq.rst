.. include:: ../Includes.txt


.. _configuration_faq:

=================
Configuration FAQ
=================

Layout/Design/Theme
===================

**Q**: How can I change the design (colors, header, footer, etc.)?

**A**: The easiest way is to change TS constants through the constant editor. Watch out for categories starting with
`PIZPALUE:`. Please note that changes are applied to the page where the constant is defined and all sub-pages.

---

**Q**: I have to adapt more aspects from the distribution for my project. As well it would be good to use Git in the
work flow. What is the best way to do it?

**A**: For that purpose the extension user_customer could be adjusted and the work progress could be maintained in Git.

Header
------

**Q**: The start page uses a sticky header. How can I get a sticky header on all pages?

**A**: The extension user_customer defines this behaviour
(see :file:`typo3conf/ext/user_customer/Configuration/TypoScript/constants.typoscript` and
:file:`typo3conf/ext/user_customer/Configuration/TypoScript/Default/constants.typoscript`) with a condition. The easiest
way to overrule it is by changing the TS constant :typoscript:`page.theme.navigation.type` (found in
PIZPALUE: CUSTOMER BASE, section Menu). Alternatively the extension user_customer might be adapted.

---

**Q**: How can I get rid of the breadcrumb menu?

**A**: At this time PIZPALUE doesn't provide a constant for it. You might change it by selecting the constant category
BOOTSTRAP PACKAGE: NAVIGATION (constant title "Breadcrumb").
