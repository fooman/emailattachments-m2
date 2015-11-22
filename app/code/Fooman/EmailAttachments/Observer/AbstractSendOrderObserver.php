<?php
/**
 * @author     Kristof Ringleff
 * @package    Fooman_EmailAttachments
 * @copyright  Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fooman\EmailAttachments\Observer;

class AbstractSendOrderObserver extends AbstractObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/order/attachpdf';

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        /**
         * @var $order \Magento\Sales\Api\Data\OrderInterface
         */
        $order = $observer->getOrder();
        if ($this->scopeConfig->getValue(
            static::XML_PATH_ATTACH_PDF,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $order->getStoreId()
        )
        ) {
            $this->attachPdf(
                $this->pdfRenderer->getPdfAsString([$order]),
                $this->pdfRenderer->getFileName(__('Order ' . $order->getIncrementId())),
                $observer
            );
        }
    }
}
