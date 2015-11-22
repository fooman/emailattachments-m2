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

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Fooman\EmailAttachments\Model\AttachmentFactory $attachmentFactory,
        \Fooman\EmailAttachments\Model\Api\PdfRendererInterface $pdfRenderer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->attachmentFactory = $attachmentFactory;
        $this->pdfRenderer = $pdfRenderer;
    }

    protected function attachPdf($pdfString, $pdfFilename, $observer)
    {
        $attachment = $this->attachmentFactory->create(
            [
                'content'  => $pdfString,
                'mimeType' => 'application/pdf',
                'fileName' => $pdfFilename
            ]
        );
        $observer->getAttachmentContainer()->addAttachment($attachment);
    }
}
