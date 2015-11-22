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

class BeforeSendShipmentCommentObserver extends AbstractSendShipmentObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/shipment_comment/attachpdf';
}
