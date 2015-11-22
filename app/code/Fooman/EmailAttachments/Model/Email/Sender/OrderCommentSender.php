<?php

namespace Fooman\EmailAttachments\Model\Email\Sender;

class OrderCommentSender extends \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender
{
    /**
     * @var \Fooman\EmailAttachments\Model\AttachmentContainerInterface
     */
    protected $attachmentContainer;

    public function __construct(
        \Magento\Sales\Model\Order\Email\Container\Template $templateContainer,
        \Magento\Sales\Model\Order\Email\Container\OrderCommentIdentity $identityContainer,
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

    public function send(\Magento\Sales\Model\Order $order, $notify = true, $comment = '')
    {
        $this->eventManager->dispatch(
            'fooman_emailattachments_before_send_order_comment',
            [

                'attachment_container' => $this->attachmentContainer,
                'order'                => $order
            ]
        );
        return parent::send($order, $notify, $comment);
    }
}
