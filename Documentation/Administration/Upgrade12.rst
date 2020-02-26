.. include:: ../Includes.txt

.. _admin_upgrade_12:

===========================================
Upgrade to version 12.0 (not available yet)
===========================================

Breaking changes
================

Following breaking changes being introduced since version 11.0.0 will be outlined.

.. _admin_upgrade_12_gridelements:

Static template for ``gridelements``
------------------------------------

The static template ``Gridelements`` from the extension ``gridelements`` isn't supported any more,
``Gridelements w/DataProcessing (recommended)`` is used instead. When upgrading the following
adjustments in the (root-) template record under ``Include static (from extensions)`` have to be
carried out:

#. Replace ``Gridelements`` with ``Gridelements w/DataProcessing (recommended)``
#. Replace ``Pizpalue DEPRECIATED - Gridelements CEs`` with ``Pizpalue - gridelements w/DataProcessing``
#. Remove ``Pizpalue DEPRECIATED - Gridelements rendering (include as last)``
