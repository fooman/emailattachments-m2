<?php

namespace Fooman\EmailAttachments\Model\Order\Creditmemo\Sender;

class EmailSender extends \Magento\Sales\Model\Order\Creditmemo\Sender\EmailSender
{
    /**
     * @var \Fooman\EmailAttachments\Model\AttachmentContainerInterface
     */
    protected $attachmentContainer;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    public function __construct(
        \Magento\Sales\Model\Order\Email\Container\Template $templateContainer,
        \Magento\Sales\Model\Order\Email\Container\CreditmemoIdentity $identityContainer,
        \Magento\Sales\Model\Order\Email\SenderBuilderFactory $senderBuilderFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Magento\Sales\Model\ResourceModel\Order\Creditmemo $creditmemoResource,
        \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Fooman\EmailAttachments\Model\AttachmentContainer $attachmentContainer
    ) {
        parent::__construct($templateContainer, $identityContainer, $senderBuilderFactory, $logger, $addressRenderer,
            $paymentHelper, $creditmemoResource, $globalConfig, $eventManager);

        $this->eventManager = $eventManager;
        $this->attachmentContainer = $attachmentContainer;
    }


    public function send(
        \Magento\Sales\Api\Data\OrderInterface $order,
        \Magento\Sales\Api\Data\CreditmemoInterface $creditmemo,
        \Magento\Sales\Api\Data\CreditmemoCommentCreationInterface $comment = null,
        $forceSyncMode = false
    )
    {
        $this->eventManager->dispatch(
            'fooman_emailattachments_before_send_creditmemo',
            [

                'attachment_container' => $this->attachmentContainer,
                'creditmeno'             => $creditmemo
            ]
        );
        return parent::send($order, $creditmemo, $comment, $forceSyncMode);
    }


}