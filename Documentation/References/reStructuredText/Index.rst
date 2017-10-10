.. Tip - just do it:
      don't use TABs (= \t, tabulators)
      replace each TAB by *three blanks* (enable RegExp for Search and Replace in your IDE)
      set TAB width and indentation to THREE in your IDE
      set 'Use blanks instead of TABs' in your IDE


.. With the following include we import some definition. We do this in each and every file.
   so we can change the definition at a single place. Use the relative path to the Includes.txt file,
   which may look as well like ../../../Includes.txt for a deeply nested source file.

.. include:: ../../Includes.txt


.. Usually we define 'php' as default highlight language in Includes.txt.
   With the following 'highlight' directive we switch to reStructuredText as default highlight language.

.. highlight:: rst


.. The following, first section (= headline) is the 'Document Title'.


======================
reStructuredText
======================


Docutils and Sphinx
===================

Docutils_ basically deals with single files that contain reStructuredText_.

Sphinx_ builds on Docutils_ and allows to join many single files together to
form a *Documentation Project* like a book. And of course, since Sphinx is all
about creating documentation *projects*, it comes with a beautiful and detailed
documentation itself.

.. tip::

   You have questions about reStructuredText?
   The first thing you should think of is:
   `Visit the Sphinx-Doc website! <http://www.sphinx-doc.org/>`__

.. _Sphinx: http://www.sphinx-doc.org/


Learn about the basics of "reStructuredText"
============================================

Yes, there is original documentation about and around reStructuredText.
But, oh dear, it looks it has never been styled and it may blow your mind at some points
since the details are difficult to understand.
But, there are good news as well:
You can easily start nevertheless. Just use easy markup in the beginning.


A reading that organizes information
------------------------------------

-  `reStructuredText <http://docutils.sourceforge.net/rst.html>`_

   *Note:*
   This document is about "Markup Syntax and Parser Component of `Docutils
   <http://docutils.sourceforge.net/index.html>`_"

   *Note:* "reStructuredText" is ONE word, not two!


User Documentation
------------------

-  `A ReStructuredText Primer <http://docutils.sourceforge.net/docs/user/rst/quickstart.html>`_

   *Note:*
   This document is an informal introduction to reStructuredText.
   The `What Next? <http://docutils.sourceforge.net/docs/user/rst/quickstart.html#what-next>`_
   section in there has links to further resources, including a formal reference.

-  `Quick reStructuredText <http://docutils.sourceforge.net/docs/user/rst/quickref.html>`_

   *Note:*
   This document is just intended as a reminder.
   The full details of the markup may be found on the reStructuredText page.


-  `The reStructuredText Cheat Sheet: Syntax Reminders
   <http://docutils.sourceforge.net/docs/user/rst/cheatsheet.txt>`_

   text only; 1 page for syntax, 1 page directive & role reference


Reference Documentation
-----------------------

-  `An Introduction to reStructuredText <http://docutils.sourceforge.net/docs/ref/rst/introduction.html>`_

   including the Goals and History of reStructuredText


-  `reStructuredText Markup Specification <http://docutils.sourceforge.net/docs/ref/rst/restructuredtext.html>`_

   *Note:*
   This document is a detailed technical specification; it is not a tutorial or a primer.
   If this is your first exposure to reStructuredText, please read
   A ReStructuredText Primer and the Quick reStructuredText user reference first.


-  `reStructuredText Directives <http://docutils.sourceforge.net/docs/ref/rst/directives.html>`_

   *Note:* This document describes the directives implemented in the reference reStructuredText parser.


-  `reStructuredText Interpreted Text Roles <http://docutils.sourceforge.net/docs/ref/rst/roles.html>`_

   *Note:* This document describes the interpreted text roles implemented in the reference reStructuredText parser.


Developer Documentation
-----------------------

-  `A Record of reStructuredText Syntax Alternatives <http://docutils.sourceforge.net/docs/dev/rst/alternatives.html>`_
-  `Problems With StructuredText <http://docutils.sourceforge.net/docs/dev/rst/problems.html>`_

How-To's
--------

-  `Creating reStructuredText Directives <http://docutils.sourceforge.net/docs/howto/rst-directives.html>`_
-  `Creating reStructuredText Interpreted Text Roles <http://docutils.sourceforge.net/docs/howto/rst-roles.html>`_

