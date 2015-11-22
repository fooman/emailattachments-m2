<?php

namespace Fooman\EmailAttachments\Model\Api;

interface PdfRendererInterface
{
    public function getPdfAsString(array $salesObjects);

    public function getFileName($input = '');

}
