<?php

namespace Fooman\EmailAttachments\Observer;

class AbstractSendInvoiceObserver extends AbstractObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/invoice/attachpdf';

    protected $pdfRenderer;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Fooman\EmailAttachments\Model\AttachmentFactory $attachmentFactory,
        \Fooman\EmailAttachments\Model\Api\PdfRendererInterface $pdfRenderer
    ) {
        parent::__construct($scopeConfig, $attachmentFactory);
        $this->pdfRenderer = $pdfRenderer;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        /**
         * @var $invoice \Magento\Sales\Api\Data\InvoiceInterface
         */
        $invoice = $observer->getInvoice();
        if ($this->scopeConfig->getValue(
            static::XML_PATH_ATTACH_PDF,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $invoice->getStoreId()
        )
        ) {
            $this->attachPdf(
                $this->pdfRenderer->getPdfAsString([$invoice]),
                $this->pdfRenderer->getPdfFilename(__('Invoice ' . $invoice->getIncrementId())),
                $observer
            );
        }
    }
}
