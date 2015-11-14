<?php

namespace Fooman\EmailAttachments\Model;

use Fooman\EmailAttachments\Model\Api\AttachmentInterface;

interface AttachmentContainerInterface
{

    /**
     * @return bool
     */
    public function hasAttachments();

    /**
     * @param AttachmentInterface $attachment
     */
    public function addAttachment(AttachmentInterface $attachment);

    public function getAttachments();
}
