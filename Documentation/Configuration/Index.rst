.. include:: ../Includes.txt


.. _configuration:

Configuration
==============

.. _seo:

SEO
---

You might follow these steps to setup seo features:

#. Install `extension realurl <https://extensions.typo3.org/extension/realurl/>`__
#. Verify 404-handling. You might need to adjust configurations by help of the install tool (e.g. pageNotFound_handling).
#. Review sitemap by adding `?eID=dd_googlesitemap <https://www.pizpalue.buechler.pro/?eID=dd_googlesitemap>`__ to the domain
#. Review seo tags like title-tag and description meta-tag
#. Register domain as new property at google search console
#. Verify ownership by adding google-site-verification code to the related seo constant (see figure below)
#. Register domain in google analytics
#. Add google analytics code to the related seo constant (see figure below)

.. figure:: ../Images/Configuration/ConstantEditorSeo.jpg
   :width: 500px
   :alt: SEO related constants in "PIZPALUE CUSTOMER" category

   SEO related constants in "PIZPALUE CUSTOMER" category

.. _404:

404-Handling
------------

The 404-handling can be configured in the install tool. An example configuration might look as following:

========================================== ===================================================
Parameter                                  Value
========================================== ===================================================
[FE][pageNotFound_handling]                REDIRECT:https://www.pizpalue.buechler.pro/404/
[FE][pageNotFound_handling_statheader]     HTTP/1.0 404 Not Found
========================================== ===================================================

.. _scrollanimation:

Scroll animation
----------------

The scroll animation feature allows to call further attention to content elements while the user is scrolling on the
page. Typically content elements are moved in from the side of the page.

The feature can be enabled in the constant editor (PIZPALUE: CUSTOMER - Features).

Since this feature is based on the dimensions from the visible area and the content element problems might come up
where the content element dimensions change upon scrolling, like it is the case with the lazy image loading feature.
This is why the images are configured to be fully preloaded when the scroll animation feature is enabled. This might be
overwritten with the following TS:

   ::
      lib.contentElement.settings.preload.images = 0

.. _cookieconsent:

Cookie consent
--------------

To show a cookie dialog the "Enable Cookie Consent"-parameter has to be set (available through the constant editor
under "PIZPALUE: CUSTOMER"). As well a link to a privacy policy page can be set in the cookie dialog.

Further configurations regarding the cookie dialog can be found und "PIZPALUE: CUSTOMER VARIOUS" in the constant editor.

.. _info
For Google Analytics a control block can be embedded by using the string ###GoogleAnalyticsStatus### in a content
element.

.. _note
The cookie dialog is rendered with a partial. You might need to update your template by embedding
   ::
      <f:render partial="Structure/CookieConsent" arguments="{_all}" />
