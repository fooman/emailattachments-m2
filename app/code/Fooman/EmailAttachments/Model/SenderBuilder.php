<?php

namespace Fooman\EmailAttachments\Model;

class SenderBuilder extends \Magento\Sales\Model\Order\Email\SenderBuilder
{

    protected $attachmentContainer;

    public function __construct(
        \Magento\Sales\Model\Order\Email\Container\Template $templateContainer,
        \Magento\Sales\Model\Order\Email\Container\IdentityInterface $identityContainer,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Fooman\EmailAttachments\Model\AttachmentContainerInterface $attachmentContainer
    ) {
        parent::__construct($templateContainer, $identityContainer, $transportBuilder);
        $this->attachmentContainer = $attachmentContainer;
    }

    public function getAttachmentContainer()
    {
        return $this->attachmentContainer;
    }


    public function send()
    {
        if ($this->attachmentContainer->hasAttachments()) {
            foreach ($this->attachmentContainer->getAttachments() as $attachment) {
                $this->transportBuilder->addAttachment($attachment);
            }
        }

        parent::send();
    }
}
