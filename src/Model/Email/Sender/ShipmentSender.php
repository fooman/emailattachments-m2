<?php
/**
 * @author     Kristof Ringleff
 * @package    Fooman_EmailAttachments
 * @copyright  Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fooman\EmailAttachments\Model\Email\Sender;

class ShipmentSender extends \Magento\Sales\Model\Order\Email\Sender\ShipmentSender
{
    /**
     * @var \Fooman\EmailAttachments\Model\AttachmentContainerInterface
     */
    protected $attachmentContainer;

    public function __construct(
        \Magento\Sales\Model\Order\Email\Container\Template $templateContainer,
        \Magento\Sales\Model\Order\Email\Container\ShipmentIdentity $identityContainer,
        \Magento\Sales\Model\Order\Email\SenderBuilderFactory $senderBuilderFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Magento\Sales\Model\ResourceModel\Order\Shipment $shipmentResource,
        \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Fooman\EmailAttachments\Model\AttachmentContainer $attachmentContainer
    ) {
        parent::__construct(
            $templateContainer,
            $identityContainer,
            $senderBuilderFactory,
            $logger,
            $addressRenderer,
            $paymentHelper,
            $shipmentResource,
            $globalConfig,
            $eventManager
        );
        $this->attachmentContainer = $attachmentContainer;
    }


    public function send(\Magento\Sales\Model\Order\Shipment $shipment, $forceSyncMode = false)
    {
        $this->eventManager->dispatch(
            'fooman_emailattachments_before_send_shipment',
            [

                'attachment_container' => $this->attachmentContainer,
                'shipment'             => $shipment
            ]
        );
        return parent::send($shipment, $forceSyncMode);
    }
}
