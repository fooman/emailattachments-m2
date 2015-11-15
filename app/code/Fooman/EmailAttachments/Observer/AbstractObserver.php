<?php

namespace Fooman\EmailAttachments\Observer;

abstract class AbstractObserver implements \Magento\Framework\Event\ObserverInterface
{
    protected $attachmentFactory;

    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Fooman\EmailAttachments\Model\AttachmentFactory $attachmentFactory
    ) {
        $this->scopeConfig = $scopeConfig;
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
