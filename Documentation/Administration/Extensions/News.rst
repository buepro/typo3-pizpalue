.. include:: ../../Includes.txt

.. _admin_news:

==============
Extension news
==============

.. _admin_news_rss_feed:

RSS Feed
========

To provide an RSS feed the following steps could be followed:

#. Create an extension template on a page where the feed should be available
#. Include static template (from extension) "Pizpalue - news RSS feed (pizpalue)"
#. Configure the behaviour using the constant editor (category "PIZPALUE: NEWS RSS")
#. Add "?type=9818&no_cache=1" to the page link to get the feed link

.. note::
   To embed external feeds the extension rss_display might be used. At the time of writing the extension didn't
   provide a view helper to get the url from enclosed images. The branch enclosure-view-helper from fork
   `chesio/rss_display <https://github.com/chesio/rss_display/tree/enclosure-view-helper>`__ provides one.