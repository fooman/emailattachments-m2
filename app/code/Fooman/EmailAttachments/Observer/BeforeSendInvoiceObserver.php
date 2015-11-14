<?php

namespace Fooman\EmailAttachments\Observer;

class BeforeSendInvoiceObserver extends AbstractObserver
{

    protected $pdfRenderer;

    public function __construct(
        \Fooman\EmailAttachments\Model\AttachmentFactory $attachmentFactory,
        \Magento\Sales\Model\Order\Pdf\Invoice $pdfRenderer
    ) {
        parent::__construct($attachmentFactory);
        $this->pdfRenderer = $pdfRenderer;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $pdf = $this->pdfRenderer->getPdf([$observer->getInvoice()]);

        $attachment = $this->attachmentFactory->create(
            [
                'content'  => $pdf->render(),
                'mimeType' => 'application/pdf',
                'fileName' => 'testing.pdf'
            ]
        );
        $observer->getEvent()->getAttachmentContainer()->addAttachment($attachment);
    }
}
