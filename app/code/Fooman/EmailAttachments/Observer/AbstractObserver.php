<?php

namespace Fooman\EmailAttachments\Observer;

abstract class AbstractObserver implements \Magento\Framework\Event\ObserverInterface
{
    protected $attachmentFactory;

    public function __construct(
        \Fooman\EmailAttachments\Model\AttachmentFactory $attachmentFactory
    ) {
        $this->attachmentFactory = $attachmentFactory;
    }
}
