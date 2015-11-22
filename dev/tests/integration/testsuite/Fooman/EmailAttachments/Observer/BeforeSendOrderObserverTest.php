<?php

namespace Fooman\EmailAttachments\Observer;

/**
 * @magentoAppArea adminhtml
 */
class BeforeSendOrderObserverTest extends Common
{
    /**
     * @magentoDataFixture   Magento/Sales/_files/order.php
     * @magentoConfigFixture current_store sales_email/order/attachpdf 1
     */
    public function testWithAttachment()
    {
        $moduleManager = $this->objectManager->create('Magento\Framework\Module\Manager');
        $order = $this->sendOrderEmail();
        if (!$moduleManager->isEnabled('Fooman_PrintOrderPdf')) {
            $this->markTestSkipped('Fooman_PrintOrderPdf required for attaching order pdf');
        }
        $pdf = $this->objectManager->create('\Fooman\PrintOrderPdf\Model\Pdf\Order')->getPdf([$order]);
        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertEquals(strlen($pdf->render()), strlen(base64_decode($pdfAttachment['Body'])));
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/order.php
     * @magentoConfigFixture current_store sales_email/order/attachpdf 0
     */
    public function testWithoutAttachment()
    {
        $this->sendOrderEmail();

        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertFalse($pdfAttachment);
    }

    protected function getOrder()
    {
        $collection = $this->objectManager->create(
            'Magento\Sales\Model\ResourceModel\Order\Collection'
        )->setPageSize(1);
        return $collection->getFirstItem();
    }

    /**
     * @return mixed
     */
    protected function sendOrderEmail()
    {
        $order = $this->getOrder();
        $orderSender = $this->objectManager
            ->create('Magento\Sales\Model\Order\Email\Sender\OrderSender');

        $orderSender->send($order);
        return $order;
    }
}
