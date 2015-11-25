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

abstract class AbstractObserver implements \Magento\Framework\Event\ObserverInterface
{
    protected $attachmentFactory;

    protected $scopeConfig;

    protected $pdfRenderer;

    protected $termsCollection;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Fooman\EmailAttachments\Model\AttachmentFactory $attachmentFactory,
        \Fooman\EmailAttachments\Model\Api\PdfRendererInterface $pdfRenderer,
        \Magento\CheckoutAgreements\Model\ResourceModel\Agreement\CollectionFactory $termsCollection
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->attachmentFactory = $attachmentFactory;
        $this->pdfRenderer = $pdfRenderer;
        $this->termsCollection = $termsCollection;
    }

    //TODO change input observer->attachmentContainerInterface
    public function attachContent($content, $pdfFilename, $mimeType, $observer)
    {
        $attachment = $this->attachmentFactory->create(
            [
                'content'  => $content,
                'mimeType' => $mimeType,
                'fileName' => $pdfFilename
            ]
        );
        $observer->getAttachmentContainer()->addAttachment($attachment);
    }

    public function attachPdf($pdfString, $pdfFilename, $observer)
    {
        $this->attachContent($pdfString, $pdfFilename, 'application/pdf', $observer);
    }

    public function attachTxt($text, $filename, $observer)
    {
        $this->attachContent($text, $filename, 'text/plain', $observer);
    }

    public function attachHtml($html, $filename, $observer)
    {
        $this->attachContent($html, $filename, 'text/html; charset=UTF-8', $observer);
    }

    public function attachTermsAndConditions($storeId, $observer)
    {
        /**
         * @var $agreements \Magento\CheckoutAgreements\Model\ResourceModel\Agreement\Collection
         */
        $agreements = $this->termsCollection->create();
        $agreements->addStoreFilter($storeId)->addFieldToFilter('is_active', 1);

        foreach ($agreements as $agreement) {
            /**
             * @var $agreement \Magento\CheckoutAgreements\Api\Data\AgreementInterfacet
             */
            if ($agreement->getIsHtml()) {
                $this->attachHtml($this->buildHtmlAgreement($agreement), $agreement->getName().'.html', $observer);
            } else {
                $this->attachTxt($agreement->getContent(), $agreement->getName().'.txt', $observer);
            }
        }
    }

    protected function buildHtmlAgreement(\Magento\CheckoutAgreements\Api\Data\AgreementInterface $agreement)
    {
        return sprintf(
            '<html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <title>%s</title>
                </head>
                <body>%s</body>
            </html>',
            $agreement->getName(),
            $agreement->getContent()
        );
    }
}
