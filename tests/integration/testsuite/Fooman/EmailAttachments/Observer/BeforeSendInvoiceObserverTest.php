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

/**
 * @magentoAppArea       adminhtml
 * @magentoAppIsolation  enabled
 */
class BeforeSendInvoiceObserverTest extends Common
{
    /**
     * @magentoDataFixture   Magento/Sales/_files/invoice.php
     * @magentoConfigFixture current_store sales_email/invoice/attachpdf 1
     * @magentoAppIsolation  enabled
     */
    public function testWithAttachment()
    {
        $invoice = $this->sendEmail();
        $this->comparePdfs($invoice);
        return $invoice;
    }

    private function comparePdfs($invoice, $number = 1)
    {
        $moduleManager = $this->objectManager->create('Magento\Framework\Module\Manager');
        if ($moduleManager->isEnabled('Fooman_PdfCustomiser')) {
            $pdf = $this->objectManager
                ->create('\Fooman\PdfCustomiser\Model\PdfRenderer\InvoiceAdapter')
                ->getPdfAsString([$invoice]);
            $this->comparePdfAsStringWithReceivedPdf(
                $pdf,
                sprintf('TAXINVOICE_%s.pdf', $invoice->getIncrementId()),
                $number
            );
        } else {
            $pdf = $this->objectManager->create('\Magento\Sales\Model\Order\Pdf\Invoice')->getPdf([$invoice]);
            $this->compareWithReceivedPdf($pdf, $number);
        }
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/invoice.php
     * @magentoDataFixture   Magento/CheckoutAgreements/_files/agreement_active_with_html_content.php
     * @magentoConfigFixture current_store sales_email/invoice/attachagreement 1
     */
    public function testWithHtmlTermsAttachment()
    {
        $this->sendEmail();
        $this->checkReceivedHtmlTermsAttachment();
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/invoice.php
     * @magentoDataFixture   Fooman/EmailAttachments/_files/agreement_active_with_text_content.php
     * @magentoConfigFixture current_store sales_email/invoice/attachagreement 1
     */
    public function testWithTextTermsAttachment()
    {
        $this->sendEmail();
        $this->checkReceivedTxtTermsAttachment();
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/invoice.php
     * @magentoConfigFixture current_store sales_email/invoice/attachpdf 0
     */
    public function testWithoutAttachment()
    {
        $this->sendEmail();

        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertFalse($pdfAttachment);
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/invoice.php
     * @magentoDataFixture   Magento/CheckoutAgreements/_files/agreement_active_with_html_content.php
     * @magentoConfigFixture current_store sales_email/invoice/attachagreement 1
     * @magentoConfigFixture current_store sales_email/invoice/attachpdf 1
     */
    public function testMultipleAttachments()
    {
        $this->testWithAttachment();
        $this->checkReceivedHtmlTermsAttachment();
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/invoice.php
     * @magentoDataFixture   Magento/CheckoutAgreements/_files/agreement_active_with_html_content.php
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store sales_email/invoice/attachagreement 1
     * @magentoConfigFixture current_store sales_email/invoice/attachpdf 1
     * @magentoConfigFixture current_store sales_email/invoice/copy_method copy
     * @magentoConfigFixture current_store sales_email/invoice/copy_to copyto@example.com
     */
    public function testWithCopyToRecipient()
    {
        $invoice = $this->testWithAttachment();
        $this->checkReceivedHtmlTermsAttachment(1);
        $this->checkReceivedHtmlTermsAttachment(2);
        $this->comparePdfs($invoice, 2);
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/invoice.php
     * @magentoDataFixture   Magento/CheckoutAgreements/_files/agreement_active_with_html_content.php
     * @magentoAppIsolation  enabled
     * @magentoConfigFixture current_store sales_email/invoice/attachagreement 1
     * @magentoConfigFixture current_store sales_email/invoice/attachpdf 1
     * @magentoConfigFixture current_store sales_email/invoice/copy_method bcc
     * @magentoConfigFixture current_store sales_email/invoice/copy_to copyto@example.com
     */
    public function testWithBccRecipient()
    {
        $invoice = $this->testWithAttachment();
        $this->checkReceivedHtmlTermsAttachment(1);
        $this->checkReceivedHtmlTermsAttachment(2);
        $this->comparePdfs($invoice, 2);
    }

    protected function getInvoice()
    {
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Sales\Model\ResourceModel\Order\Invoice\Collection'
        )->setPageSize(1);
        return $collection->getFirstItem();
    }

    /**
     * @return \Magento\Sales\Api\Data\InvoiceInterface
     */
    protected function sendEmail()
    {
        $invoice = $this->getInvoice();
        $invoiceSender = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Sales\Model\Order\Email\Sender\InvoiceSender');

        $invoiceSender->send($invoice);
        return $invoice;
    }
}
