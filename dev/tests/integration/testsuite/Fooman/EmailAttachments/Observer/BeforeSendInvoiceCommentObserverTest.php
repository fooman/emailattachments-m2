<?php

namespace Fooman\EmailAttachments\Observer;

/**
 * @magentoAppArea adminhtml
 */
class BeforeSendInvoiceCommentObserverTest extends Common
{
    /**
     * @magentoDataFixture   Magento/Sales/_files/invoice.php
     * @magentoConfigFixture current_store sales_email/invoice_comment/attachpdf 1
     */
    public function testWithAttachment()
    {
        $invoice = $this->sendInvoiceCommentEmail();
        $pdf = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('\Magento\Sales\Model\Order\Pdf\Invoice')->getPdf([$invoice]);
        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertEquals(strlen($pdf->render()), strlen(base64_decode($pdfAttachment['Body'])));
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/invoice.php
     * @magentoConfigFixture current_store sales_email/invoice_comment/attachpdf 0
     */
    public function testWithoutAttachment()
    {
        $this->sendInvoiceCommentEmail();

        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertFalse($pdfAttachment);
    }

    protected function getInvoice()
    {
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Sales\Model\ResourceModel\Order\Invoice\Collection'
        )->setPageSize(1);
        return $collection->getFirstItem();
    }

    /**
     * @return mixed
     */
    protected function sendInvoiceCommentEmail()
    {
        $invoice = $this->getInvoice();
        $invoiceSender = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Sales\Model\Order\Email\Sender\InvoiceCommentSender');

        $invoiceSender->send($invoice);
        return $invoice;
    }
}
