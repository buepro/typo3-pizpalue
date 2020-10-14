.. include:: ../../Includes.txt

.. _config_eventnews:

=====================
Extension `eventnews`
=====================

Layout
======

Per default the calendar view shows a calendar from the month and a list from events taking place in the selected month.

In case just the calendar should be shown the field `Template Layout` (register Template) can be set to `Compact (month)`.
To extend the view with a filter panel the layout `Extended (month)` can be selected.

Titles
======

The default title can be overridden by the `subheader` field in case the `header` field is empty. It is only rendered
if the field ´header type` is not `hidden`. The header type is taken into account.

Various
=======

.. tip::
   To only show upcoming events the field `Time limit (LOW)` can be set to `now` in the plugin content element.

.. tip::
   To show events in search results add the following TS to the setup where the plugin is located:
   `plugin.tx_news.settings.overrideFlexformSettingsIfEmpty:= removeFromList(eventRestriction)`
