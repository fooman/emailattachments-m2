<?php

namespace Fooman\EmailAttachments\Observer;

abstract class AbstractObserver implements \Magento\Framework\Event\ObserverInterface
{
    protected $attachmentFactory;

    public function __construct(
        \Fooman\EmailAttachments\Model\AttachmentFactory $attachmentFactory
    ) {
        $this->attachmentFactory = $attachmentFactory;
    }

    protected function attachPdf($pdf, $observer)
    {
        $attachment = $this->attachmentFactory->create(
            [
                'content'  => $pdf->render(),
                'mimeType' => 'application/pdf',
                'fileName' => 'testing.pdf'
            ]
        );
        $observer->getAttachmentContainer()->addAttachment($attachment);
    }
}
