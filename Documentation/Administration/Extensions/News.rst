.. include:: /Includes.rst.txt

.. _admin_news:

================
Extension `news`
================

Static templates
================

This extension provides more flexibility by adding a dummy asset field, a
layout with an image on top and optimized image rendering. To get these features
the static template (from extension) `Pizpalue - news (pizpalue)` needs to be
added. The resulting template hierarchy would be as following:

#. News (news)
#. News Styles Twitter Bootstrap V5 (news)
#. Pizpalue - news (pizpalue)

.. note::
   In case the extension `eventnews` is installed the template hierarchy
   would be as following:

   #. News (news)
   #. News Styles Twitter Bootstrap V5 (news)
   #. Eventnews (eventnews)
   #. Pizpalue - news (pizpalue)
   #. Pizpalue - eventnews (eventnews)

.. note::
   To show news in modal dialog content elements the template hierarchy needs
   to be as following:

   #. News (news)
   #. News Styles Twitter Bootstrap V5 (news)
   #. Pizpalue - news (pizpalue)
   #. Pizpalue - Main (pizpalue)

.. _admin_news_rss_feed:

RSS Feed
========

To provide an RSS feed the following steps could be followed:

#. Create an extension template on a page where the feed should be available
#. Include static template (from extension) "Pizpalue - news RSS feed (pizpalue)"
#. Configure the behaviour using the constant editor (category "PIZPALUE: NEWS RSS")
#. Add "?type=9818&no_cache=1" to the page link to get the feed link

.. note::
   To embed external feeds the extension rss_display might be used. At the time
   of writing the extension didn't provide a view helper to get the url from
   enclosed images. The branch enclosure-view-helper from fork
   `chesio/rss_display <https://github.com/chesio/rss_display/tree/enclosure-view-helper>`__
   provides one.
