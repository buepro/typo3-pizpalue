.. include:: ../../Includes.txt

.. _admin_form:

==============
Extension form
==============


.. _admin_dataconsent:

Data consent
============

Currently there isn't a form element available providing a checkbox with a label linking to an other page.
To customize such a label the classes `pp-label-dataprotection` and `pp-dataprotection` were introduced.

How to use it:

1. Create a content element with the text to be assigned to the checkbox and assign the class
   `pp-label-dataprotection` to it.

2. In the form definition assign the class `pp-dataprotection` to the checkbox:

   .. code-block:: yaml

      type: Checkbox
      identifier: privacy
      label: Datenschutzvereinbarung
      properties:
         containerClassAttribute: 'custom-control custom-checkbox pp-dataprotection'

.. note::

   You might have a loo to the contact form (yaml definition and content element) to see how it could be done.


.. _admin_form_emailfinisher:

Email finisher
==============

The email finisher from the form extension has been extended to provide data from the finisher to the fluid template.
As a result the array "finisherOptions" holds the elements senderName, senderAddress, recipientName, recipientAddress
and subject. To add the sender name to the fluid template use :typoscript:`{finisherOptions.senderName}`.


.. _admin_form_mailtosystem:

Mail to system form finisher
============================

A form finisher has been added allowing to send an email to a system for further processing the user data. A use case
might be to send plaintext emails from the web site to a system processing the data for a CRM.

To customize the content being sent a template can be created and referenced to as following:

.. code-block:: yaml

   finishers:
      ...
      -
      options:
         subject: Subject for CRM
         recipientAddress: info@domain.com
         recipientName: 'CRM Admin'
         senderAddress: sender@domain.com
         senderName: 'Web Admin'
         replyToAddress: ''
         carbonCopyAddress: ''
         blindCarbonCopyAddress: ''
         format: plaintext
         attachUploads: false
         templateRootPaths:
            30: 'EXT:user_customer/Resources/Private/Templates/Form/Finishers/MailToSystem/'
      -
      ...

.. note::
   The customized template might be started off by using the template found under
   "typo3conf/ext/pizpalue/Resources/Private/Templates/Form/Finishers/MailToSystem/Plaintext.html"
