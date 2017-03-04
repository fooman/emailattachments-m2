<?php
namespace Fooman\EmailAttachments\Model\Email\Sender;

class Plugin
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
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Fooman\EmailAttachments\Model\AttachmentContainer $attachmentContainer
    ) {
        $this->eventManager = $eventManager;
        $this->attachmentContainer = $attachmentContainer;
    }

    public function beforeSend(\Magento\Sales\Model\Order\Email\Sender $subject, $model, $forceSyncMode = false) {
        $modelWithPath = strtolower(get_class($model));
        $modelString = substr($modelWithPath, strrpos($modelWithPath, '\\') + 1);
        $this->eventManager->dispatch(
            'fooman_emailattachments_before_send_'.$modelString,
            [

                'attachment_container' => $this->attachmentContainer,
                $modelString           => $model
            ]
        );
        return null;
    }
}