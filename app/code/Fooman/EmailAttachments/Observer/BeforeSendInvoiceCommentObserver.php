<?php

namespace Fooman\EmailAttachments\Observer;

class BeforeSendInvoiceCommentObserver extends AbstractSendInvoiceObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/invoice_comment/attachpdf';
}
