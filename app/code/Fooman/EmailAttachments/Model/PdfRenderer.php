<?php

namespace Fooman\EmailAttachments\Model;

class PdfRenderer implements Api\PdfRendererInterface
{
    protected $pdfRenderer;

    public function __construct(
        \Magento\Sales\Model\Order\Pdf\AbstractPdf $pdfRenderer
    ) {
        $this->pdfRenderer = $pdfRenderer;
    }

    public function getPdfAsString(array $salesObject)
    {
        return $this->pdfRenderer->getPdf($salesObject)->render();
    }

    public function getFileName($input = '')
    {
        return sprintf('%s.pdf', $input);
    }
}
