.. include:: ../Includes.txt


.. _administration_mailtosystem:

============================
Mail to system form finisher
============================

A form finisher has been added allowing to send an email to a system for further processing the user data. A use case
might be to send plaintext emails from the web site to a system processing the data for a CRM.

To customize the content being sent a template can be created and referenced to as following:

.. codeblock::
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
