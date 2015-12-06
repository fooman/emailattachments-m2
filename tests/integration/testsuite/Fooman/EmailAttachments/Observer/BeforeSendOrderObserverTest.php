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
        $order = $this->sendEmail();
        if (!$moduleManager->isEnabled('Fooman_PrintOrderPdf')) {
            $this->markTestSkipped('Fooman_PrintOrderPdf required for attaching order pdf');
        }
        $pdf = $this->objectManager->create('\Fooman\PrintOrderPdf\Model\Pdf\Order')->getPdf([$order]);
        $this->compareWithReceivedPdf($pdf);
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/order.php
     * @magentoDataFixture   Magento/CheckoutAgreements/_files/agreement_active_with_html_content.php
     * @magentoConfigFixture current_store sales_email/order/attachagreement 1
     */
    public function testWithHtmlTermsAttachment()
    {
        $this->sendEmail();
        $this->checkReceivedHtmlTermsAttachment();
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/order.php
     * @magentoDataFixture   Fooman/EmailAttachments/_files/agreement_active_with_text_content.php
     * @magentoConfigFixture current_store sales_email/order/attachagreement 1
     */
    public function testWithTextTermsAttachment()
    {
        $this->sendEmail();
        $this->checkReceivedTxtTermsAttachment();
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/order.php
     * @magentoConfigFixture current_store sales_email/order/attachpdf 0
     */
    public function testWithoutAttachment()
    {
        $this->sendEmail();

        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertFalse($pdfAttachment);
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/order.php
     * @magentoDataFixture   Magento/CheckoutAgreements/_files/agreement_active_with_html_content.php
     * @magentoConfigFixture current_store sales_email/order/attachagreement 1
     * @magentoConfigFixture current_store sales_email/order/attachpdf 1
     */
    public function testMultipleAttachments()
    {
        $this->testWithAttachment();
        $this->checkReceivedHtmlTermsAttachment();
    }

    protected function getOrder()
    {
        $collection = $this->objectManager->create(
            'Magento\Sales\Model\ResourceModel\Order\Collection'
        )->setPageSize(1);
        return $collection->getFirstItem();
    }

    /**
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    protected function sendEmail()
    {
        $order = $this->getOrder();
        $orderSender = $this->objectManager
            ->create('Magento\Sales\Model\Order\Email\Sender\OrderSender');

        $orderSender->send($order);
        return $order;
    }
}
