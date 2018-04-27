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

use \Fooman\EmailAttachments\Model\Api\AttachmentContainerInterface as ContainerInterface;

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

    public function attachContent($content, $pdfFilename, $mimeType, ContainerInterface $attachmentContainer)
    {
        $attachment = $this->attachmentFactory->create(
            [
                'content'  => $content,
                'mimeType' => $mimeType,
                'fileName' => $pdfFilename
            ]
        );
        $attachmentContainer->addAttachment($attachment);
    }

    public function attachPdf($pdfString, $pdfFilename, ContainerInterface $attachmentContainer)
    {
        $this->attachContent($pdfString, $pdfFilename, 'application/pdf', $attachmentContainer);
    }

    public function attachTxt($text, $filename, ContainerInterface $attachmentContainer)
    {
        $this->attachContent($text, $filename, 'text/plain', $attachmentContainer);
    }

    public function attachHtml($html, $filename, ContainerInterface $attachmentContainer)
    {
        $this->attachContent($html, $filename, 'text/html; charset=UTF-8', $attachmentContainer);
    }

    public function attachTermsAndConditions($storeId, ContainerInterface $attachmentContainer)
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
                $this->attachHtml(
                    $this->buildHtmlAgreement($agreement),
                    $agreement->getName() . '.html',
                    $attachmentContainer
                );
            } else {
                $this->attachTxt($agreement->getContent(), $agreement->getName() . '.txt', $attachmentContainer);
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
    
    public function getAttachmentFilename($path, $item)
    {
      $filenamemask = $this->scopeConfig->getValue(
                $path,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $item->getStoreId()
            );
      $incrementId = $item->getIncrementId();
            
      $filename = str_replace('%number%', $incrementId, $filenamemask);
      return($filename);
    }
}
