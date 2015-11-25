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
     * @magentoDataFixture   Magento/CheckoutAgreements/_files/agreement_active_with_html_content.php
     * @magentoConfigFixture current_store sales_email/shipment_comment/attachagreement 1
     */
    public function testWithHtmlTermsAttachment()
    {
        $this->sendShipmentCommentEmail();
        $termsAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'text/html; charset=UTF-8');
        $this->assertContains('Checkout agreement content: <b>HTML</b>', base64_decode($termsAttachment['Body']));
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/shipment.php
     * @magentoDataFixture   Fooman/EmailAttachments/_files/agreement_active_with_text_content.php
     * @magentoConfigFixture current_store sales_email/shipment_comment/attachagreement 1
     */
    public function testWithTextTermsAttachment()
    {
        $this->sendShipmentCommentEmail();
        $termsAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'text/plain');
        $this->assertContains('Checkout agreement content: TEXT', base64_decode($termsAttachment['Body']));
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
