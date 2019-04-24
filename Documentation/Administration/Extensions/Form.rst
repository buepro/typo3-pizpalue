.. include:: ../Includes.txt

.. _administration_form:

==============
Extension form
==============

EmailFinisher
=============

The email finisher from the form extension has been extended to provide data from the finisher to the fluid template.
As a result the array "finisherOptions" holds the elements senderName, senderAddress, recipientName, recipientAddress
and subject. To add the sender name to the fluid template use :ts:`{finisherOptions.senderName}`.
