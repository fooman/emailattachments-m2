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
class BeforeSendCreditmemoCommentObserverTest extends Common
{
    /**
     * @magentoDataFixture   Magento/Sales/_files/creditmemo_with_list.php
     * @magentoConfigFixture current_store sales_email/creditmemo_comment/attachpdf 1
     */
    public function testWithAttachment()
    {
        $creditmemo = $this->sendEmail();
        $pdf = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('\Magento\Sales\Model\Order\Pdf\Creditmemo')->getPdf([$creditmemo]);
        $this->compareWithReceivedPdf($pdf);
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/creditmemo_with_list.php
     * @magentoDataFixture   Magento/CheckoutAgreements/_files/agreement_active_with_html_content.php
     * @magentoConfigFixture current_store sales_email/creditmemo_comment/attachagreement 1
     */
    public function testWithHtmlTermsAttachment()
    {
        $this->sendEmail();
        $this->checkReceivedHtmlTermsAttachment();
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/creditmemo_with_list.php
     * @magentoDataFixture   Fooman/EmailAttachments/_files/agreement_active_with_text_content.php
     * @magentoConfigFixture current_store sales_email/creditmemo_comment/attachagreement 1
     */
    public function testWithTextTermsAttachment()
    {
        $this->sendEmail();
        $this->checkReceivedTxtTermsAttachment();
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/creditmemo_with_list.php
     * @magentoConfigFixture current_store sales_email/creditmemo_comment/attachpdf 0
     */
    public function testWithoutAttachment()
    {
        $this->sendEmail();

        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertFalse($pdfAttachment);
    }

    /**
     * @magentoDataFixture   Magento/Sales/_files/creditmemo_with_list.php
     * @magentoDataFixture   Magento/CheckoutAgreements/_files/agreement_active_with_html_content.php
     * @magentoConfigFixture current_store sales_email/creditmemo_comment/attachagreement 1
     * @magentoConfigFixture current_store sales_email/creditmemo_comment/attachpdf 1
     */
    public function testMultipleAttachments()
    {
        $this->testWithAttachment();
        $this->checkReceivedHtmlTermsAttachment();
    }

    protected function getCreditmemo()
    {
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Sales\Model\ResourceModel\Order\Creditmemo\Collection'
        )->setPageSize(1);
        return $collection->getFirstItem();
    }

    /**
     * @return \Magento\Sales\Api\Data\CreditmemoInterface
     */
    protected function sendEmail()
    {
        $creditmemo = $this->getCreditmemo();
        $creditmemoSender = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Sales\Model\Order\Email\Sender\CreditmemoCommentSender');

        $creditmemoSender->send($creditmemo);
        return $creditmemo;
    }
}
