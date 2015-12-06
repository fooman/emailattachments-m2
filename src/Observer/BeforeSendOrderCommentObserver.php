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

class BeforeSendOrderCommentObserver extends AbstractSendOrderObserver
{
    const XML_PATH_ATTACH_PDF = 'sales_email/order_comment/attachpdf';
    const XML_PATH_ATTACH_AGREEMENT = 'sales_email/order_comment/attachagreement';
}
