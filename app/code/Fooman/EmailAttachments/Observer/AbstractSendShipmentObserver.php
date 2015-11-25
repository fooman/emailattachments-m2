<?php
/**
 * @author     Kristof Ringleff
 * @package    Fooman_EmailAttachments
 * @copyright  Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fooman\EmailAttachments\Observer;

class AbstractSendShipmentObserver extends AbstractObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/shipment/attachpdf';
    const XML_PATH_ATTACH_AGREEMENT = 'sales_email/shipment/attachagreement';

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
            $this->attachPdf(
                $this->pdfRenderer->getPdfAsString([$shipment]),
                $this->pdfRenderer->getFileName(__('Packing Slip ' . $shipment->getIncrementId())),
                $observer->getAttachmentContainer()
            );
        }
    }
}
