<?php

namespace Fooman\EmailAttachments\Observer;

/**
 * @magentoAppArea adminhtml
 */
class BeforeSendInvoiceObserverTest extends Common
{
    /**
     * @magentoDataFixture Magento/Sales/_files/invoice.php
     */
    public function testExecute()
    {
        $invoice = $this->getInvoice();
        $invoiceSender = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Sales\Model\Order\Email\Sender\InvoiceSender');

        $invoiceSender->send($invoice);;
        $pdf = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('\Magento\Sales\Model\Order\Pdf\Invoice')->getPdf([$invoice]);
        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(),'application/pdf');
        $this->assertEquals(strlen($pdf->render()), strlen(base64_decode($pdfAttachment['Body'])));

    }

    protected function getInvoice()
    {

        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Sales\Model\ResourceModel\Order\Invoice\Collection'
        )->setPageSize(1);
        return $collection->getFirstItem();
    }
}
