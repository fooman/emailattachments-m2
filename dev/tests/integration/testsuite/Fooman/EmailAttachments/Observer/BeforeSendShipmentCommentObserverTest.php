<?php

namespace Fooman\EmailAttachments\Observer;

/**
 * @magentoAppArea adminhtml
 */
class BeforeSendShipmentCommentObserverTest extends Common
{
    /**
     * @magentoDataFixture   Magento/Sales/_files/shipment.php
     * @magentoConfigFixture current_store sales_email/shipment_comment/attachpdf 1
     */
    public function testWithAttachment()
    {
        $shipment = $this->sendShipmentCommentEmail();
        $pdf = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('\Magento\Sales\Model\Order\Pdf\Shipment')->getPdf([$shipment]);
        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertEquals(strlen($pdf->render()), strlen(base64_decode($pdfAttachment['Body'])));

    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/shipment.php
     * @magentoConfigFixture current_store sales_email/shipment_comment/attachpdf 0
     */
    public function testWithoutAttachment()
    {
        $this->sendShipmentCommentEmail();

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
    protected function sendShipmentCommentEmail()
    {
        $shipment = $this->getShipment();
        $shipmentSender = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Sales\Model\Order\Email\Sender\ShipmentCommentSender');

        $shipmentSender->send($shipment);
        return $shipment;
    }
}
