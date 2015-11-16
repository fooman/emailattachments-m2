<?php

namespace Fooman\EmailAttachments\Observer;

class BeforeSendShipmentCommentObserver extends AbstractSendShipmentObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/shipment_comment/attachpdf';
}
