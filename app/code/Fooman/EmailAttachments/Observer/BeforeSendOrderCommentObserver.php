<?php

namespace Fooman\EmailAttachments\Observer;

class BeforeSendOrderCommentObserver extends AbstractSendOrderObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/order_comment/attachpdf';
}
