<?php

namespace Fooman\EmailAttachments\Model\Email\Sender;

class CreditmemoCommentSender extends \Magento\Sales\Model\Order\Email\Sender\CreditmemoCommentSender
{
    /**
     * @var \Fooman\EmailAttachments\Model\AttachmentContainerInterface
     */
    protected $attachmentContainer;

    public function __construct(
        \Magento\Sales\Model\Order\Email\Container\Template $templateContainer,
        \Magento\Sales\Model\Order\Email\Container\CreditmemoCommentIdentity $identityContainer,
        \Magento\Sales\Model\Order\Email\SenderBuilderFactory $senderBuilderFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Fooman\EmailAttachments\Model\AttachmentContainer $attachmentContainer
    ) {
        parent::__construct(
            $templateContainer,
            $identityContainer,
            $senderBuilderFactory,
            $logger,
            $addressRenderer,
            $eventManager
        );
        $this->attachmentContainer = $attachmentContainer;
    }

    public function send(\Magento\Sales\Model\Order\Creditmemo $creditmemo, $notify = true, $comment = '')
    {
        $this->eventManager->dispatch(
            'fooman_emailattachments_before_send_creditmemo_comment',
            [

                'attachment_container' => $this->attachmentContainer,
                'creditmemo'              => $creditmemo
            ]
        );
        return parent::send($creditmemo, $notify, $comment);
    }
}
