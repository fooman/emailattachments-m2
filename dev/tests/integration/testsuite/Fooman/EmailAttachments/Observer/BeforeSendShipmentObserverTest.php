<?php

namespace Fooman\EmailAttachments\Observer;

/**
 * @magentoAppArea adminhtml
 */
class BeforeSendShipmentObserverTest extends Common
{
    /**
     * @magentoDataFixture   Magento/Sales/_files/shipment.php
     * @magentoConfigFixture current_store sales_email/shipment/attachpdf 1
     */
    public function testWithAttachment()
    {
        $shipment = $this->sendShipmentEmail();
        $pdf = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('\Magento\Sales\Model\Order\Pdf\Shipment')->getPdf([$shipment]);
        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertEquals(strlen($pdf->render()), strlen(base64_decode($pdfAttachment['Body'])));

    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/shipment.php
     * @magentoConfigFixture current_store sales_email/shipment/attachpdf 0
     */
    public function testWithoutAttachment()
    {
        $this->sendShipmentEmail();

        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertFalse($pdfAttachment);

    }

    protected function getShipment()
    {

        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Sales\Model\ResourceModel\Order\Shipment\Collection'
        )->setPageSize(1);
        return $collection->getFirstItem();
    }

    /**
     * @return mixed
     */
    protected function sendShipmentEmail()
    {
        $shipment = $this->getShipment();
        $shipmentSender = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Sales\Model\Order\Email\Sender\ShipmentSender');

        $shipmentSender->send($shipment);
        return $shipment;
    }
}
