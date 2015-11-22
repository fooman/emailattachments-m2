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
class BeforeSendOrderCommentObserverTest extends Common
{
    /**
     * @magentoDataFixture   Magento/Sales/_files/order.php
     * @magentoConfigFixture current_store sales_email/order_comment/attachpdf 1
     */
    public function testWithAttachment()
    {
        $moduleManager = $this->objectManager->create('Magento\Framework\Module\Manager');
        $order = $this->sendOrderCommentEmail();
        if (!$moduleManager->isEnabled('Fooman_PrintOrderPdf')) {
            $this->markTestSkipped('Fooman_PrintOrderPdf required for attaching order pdf');
        }
        $pdf = $this->objectManager->create('\Fooman\PrintOrderPdf\Model\Pdf\Order')->getPdf([$order]);
        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertEquals(strlen($pdf->render()), strlen(base64_decode($pdfAttachment['Body'])));
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/order.php
     * @magentoConfigFixture current_store sales_email/order_comment/attachpdf 0
     */
    public function testWithoutAttachment()
    {
        $this->sendOrderCommentEmail();

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
    protected function sendOrderCommentEmail()
    {
        $order = $this->getOrder();
        $orderSender = $this->objectManager
            ->create('Magento\Sales\Model\Order\Email\Sender\OrderCommentSender');

        $orderSender->send($order);
        return $order;
    }
}
