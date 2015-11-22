<?php

namespace Fooman\EmailAttachments\Observer;

class AbstractSendCreditmemoObserver extends AbstractObserver
{

    const XML_PATH_ATTACH_PDF = 'sales_email/creditmemo/attachpdf';

    protected $pdfRenderer;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Fooman\EmailAttachments\Model\AttachmentFactory $attachmentFactory,
        \Magento\Sales\Model\Order\Pdf\Creditmemo $pdfRenderer
    ) {
        parent::__construct($scopeConfig, $attachmentFactory);
        $this->pdfRenderer = $pdfRenderer;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        /**
         * @var $creditmemo \Magento\Sales\Api\Data\CreditmemoInterface
         */
        $creditmemo = $observer->getCreditmemo();
        if ($this->scopeConfig->getValue(
            static::XML_PATH_ATTACH_PDF,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $creditmemo->getStoreId()
        )
        ) {
            $pdf = $this->pdfRenderer->getPdf([$creditmemo]);
            $this->attachPdf($pdf->render(), $observer);
        }

    }
}
