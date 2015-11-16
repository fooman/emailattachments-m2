<?php

namespace Fooman\EmailAttachments\Observer;

class AbstractSendShipmentObserver extends AbstractObserver
{

    const XML_PATH_ATTACH_PDF = 'sales_email/shipment/attachpdf';

    protected $pdfRenderer;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Fooman\EmailAttachments\Model\AttachmentFactory $attachmentFactory,
        \Magento\Sales\Model\Order\Pdf\Shipment $pdfRenderer
    ) {
        parent::__construct($scopeConfig, $attachmentFactory);
        $this->pdfRenderer = $pdfRenderer;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        /**
         * @var $shipment \Magento\Sales\Api\Data\ShipmentInterface
         */
        $shipment = $observer->getShipment();
        if ($this->scopeConfig->getValue(
            static::XML_PATH_ATTACH_PDF,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $shipment->getStoreId()
        )
        ) {

            $pdf = $this->pdfRenderer->getPdf([$shipment]);
            $this->attachPdf($pdf, $observer);
        }

    }
}
