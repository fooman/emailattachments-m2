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
class BeforeSendCreditmemoObserverTest extends Common
{
    /**
     * @magentoDataFixture   Magento/Sales/_files/creditmemo_with_list.php
     * @magentoConfigFixture current_store sales_email/creditmemo/attachpdf 1
     */
    public function testWithAttachment()
    {
        $creditmemo = $this->sendCreditmemoEmail();
        $pdf = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('\Magento\Sales\Model\Order\Pdf\Creditmemo')->getPdf([$creditmemo]);
        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertEquals(strlen($pdf->render()), strlen(base64_decode($pdfAttachment['Body'])));
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/creditmemo_with_list.php
     * @magentoDataFixture   Magento/CheckoutAgreements/_files/agreement_active_with_html_content.php
     * @magentoConfigFixture current_store sales_email/creditmemo/attachagreement 1
     */
    public function testWithHtmlTermsAttachment()
    {
        $this->sendCreditmemoEmail();
        $termsAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'text/html; charset=UTF-8');
        $this->assertContains('Checkout agreement content: <b>HTML</b>', base64_decode($termsAttachment['Body']));
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/creditmemo_with_list.php
     * @magentoDataFixture   Fooman/EmailAttachments/_files/agreement_active_with_text_content.php
     * @magentoConfigFixture current_store sales_email/creditmemo/attachagreement 1
     */
    public function testWithTextTermsAttachment()
    {
        $this->sendCreditmemoEmail();
        $termsAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'text/plain');
        $this->assertContains('Checkout agreement content: TEXT', base64_decode($termsAttachment['Body']));
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/creditmemo_with_list.php
     * @magentoConfigFixture current_store sales_email/creditmemo/attachpdf 0
     */
    public function testWithoutAttachment()
    {
        $this->sendCreditmemoEmail();

        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertFalse($pdfAttachment);
    }

    protected function getCreditmemo()
    {
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Sales\Model\ResourceModel\Order\Creditmemo\Collection'
        )->setPageSize(1);
        return $collection->getFirstItem();
    }

    /**
     * @return mixed
     */
    protected function sendCreditmemoEmail()
    {
        $creditmemo = $this->getCreditmemo();
        $creditmemoSender = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Sales\Model\Order\Email\Sender\CreditmemoSender');

        $creditmemoSender->send($creditmemo);
        return $creditmemo;
    }
}
