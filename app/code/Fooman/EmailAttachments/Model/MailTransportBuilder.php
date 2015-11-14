<?php

namespace Fooman\EmailAttachments\Model;

class MailTransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{

    public function addAttachment( Api\AttachmentInterface $attachment ) {
        $this->message->createAttachment(
            $attachment->getContent(),
            $attachment->getMimeType(),
            $attachment->getDisposition(),
            $attachment->getEncoding(),
            $attachment->getFilename()
        );
    }

}
