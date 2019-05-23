.. include:: ../Includes.txt


.. _contribute:

==========
Contribute
==========

To keep the quality development respects the guidelines established by TYPO3 found in the `Core API Reference
<https://docs.typo3.org/typo3cms/CoreApiReference/>`__.

Prefixes
========

The following prefixes are used:

====== ====================== =========================================================================================
Prefix Meaning                Description
====== ====================== =========================================================================================
pp     Pizpalue               Base prefix
ppc    Pizpalue complement    Mainly used for classes complementing other classes.
ce     Content element        Used in conjunction with content elements
====== ====================== =========================================================================================

Examples
--------

**SCSS**

.. code-block:: scss

   .pp-demo-item {
      border: blue 1px solid;
      &.ppc-right {
         border-right: green 3px solid;
      }
   }

In the above example the class `ppc-right` complements the class `pp-demo-item`. The complementing classes
help to be more specific and less exhaustive (alternatively to `ppc-right` one might have used
`pp-demo-item-right`). They should only be used in the context of other classes.