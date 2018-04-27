<?php

namespace Fooman\EmailAttachments\Model\Order\Invoice\Sender;

class EmailSender extends \Magento\Sales\Model\Order\Invoice\Sender\EmailSender
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
        \Magento\Sales\Model\Order\Email\Container\InvoiceIdentity $identityContainer,
        \Magento\Sales\Model\Order\Email\SenderBuilderFactory $senderBuilderFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Magento\Sales\Model\ResourceModel\Order\Invoice $invoiceResource,
        \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Fooman\EmailAttachments\Model\AttachmentContainer $attachmentContainer
    ) {
        parent::__construct($templateContainer, $identityContainer, $senderBuilderFactory, $logger, $addressRenderer,
            $paymentHelper, $invoiceResource, $globalConfig, $eventManager);

        $this->eventManager = $eventManager;
        $this->attachmentContainer = $attachmentContainer;
    }

    public function send(
        \Magento\Sales\Api\Data\OrderInterface $order,
        \Magento\Sales\Api\Data\InvoiceInterface $invoice,
        \Magento\Sales\Api\Data\InvoiceCommentCreationInterface $comment = null,
        $forceSyncMode = false
    ) {
        $this->eventManager->dispatch(
            'fooman_emailattachments_before_send_invoice',
            [

                'attachment_container' => $this->attachmentContainer,
                'invoice'             => $invoice
            ]
        );

        return parent::send($order, $invoice, $comment, $forceSyncMode);
    }


}