<?php

namespace Fooman\EmailAttachments\Model;

class SenderBuilder extends \Magento\Sales\Model\Order\Email\SenderBuilder
{
    /**
     * @var Api\AttachmentContainerInterface
     */
    protected $attachmentContainer;

    /**
     * @param \Magento\Sales\Model\Order\Email\Container\Template          $templateContainer
     * @param \Magento\Sales\Model\Order\Email\Container\IdentityInterface $identityContainer
     * @param \Magento\Framework\Mail\Template\TransportBuilder            $transportBuilder
     * @param Api\AttachmentContainerInterface                             $attachmentContainer
     */
    public function __construct(
        \Magento\Sales\Model\Order\Email\Container\Template $templateContainer,
        \Magento\Sales\Model\Order\Email\Container\IdentityInterface $identityContainer,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Fooman\EmailAttachments\Model\Api\AttachmentContainerInterface $attachmentContainer
    ) {
        parent::__construct($templateContainer, $identityContainer, $transportBuilder);
        $this->attachmentContainer = $attachmentContainer;
    }

    /**
     * @return Api\AttachmentContainerInterface
     */
    public function getAttachmentContainer()
    {
        return $this->attachmentContainer;
    }


    /**
     * attach our attachments from the current sender to the message
     */
    public function send()
    {
        if ($this->attachmentContainer->hasAttachments()) {
            foreach ($this->attachmentContainer->getAttachments() as $attachment) {
                $this->transportBuilder->addAttachment($attachment);
            }
            $this->attachmentContainer->resetAttachments();
        }

        parent::send();
    }

    /**
     * attach our attachments from the current sender to the message
     */
    public function sendCopyTo()
    {
        if ($this->attachmentContainer->hasAttachments()) {
            foreach ($this->attachmentContainer->getAttachments() as $attachment) {
                $this->transportBuilder->addAttachment($attachment);
            }
            $this->attachmentContainer->resetAttachments();
        }
        parent::sendCopyTo();
        $this->attachmentContainer->resetAttachments();
    }
}
