<?php

namespace Fooman\EmailAttachments\Model;

class AttachmentContainer implements Api\AttachmentContainerInterface
{
    protected $attachments = [];

    /**
     * @return bool
     */
    public function hasAttachments()
    {
        return sizeof($this->attachments) >= 1;
    }

    /**
     * @param Api\AttachmentInterface $attachment
     */
    public function addAttachment(Api\AttachmentInterface $attachment)
    {
        $this->attachments[] = $attachment;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @return void
     */
    public function resetAttachments()
    {
        $this->attachments = [];
    }
}
