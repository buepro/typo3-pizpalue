.. include:: /Includes.rst.txt

.. _admin_upgrade_13.0:

==========================================
Upgrade to version 13.0
==========================================

Breaking changes
================

Add name space prefix to content element templates (07.07.2022, e305b7d)
------------------------------------------------------------------------

Description
~~~~~~~~~~~

Content elements share rendering definitions from
`lib.contentElement`. Extensions might take advantage from
settings defined by pizpalue by referencing the lib object
(e.g.`lib.containerContentElement =< lib.contentElement`).
As a result the template and partial root paths contain
definitions from various extensions. To prevent conflicts
templates from pizpalue content elements and folders with
related partials have been prefixed with 'Pp'.

Corrective action
~~~~~~~~~~~~~~~~~

Templates or partials overriding pizpalue content element
rendering need to be adapted. In most cases the template name
and the related partial folder has to be prefixed with `Pp`.
