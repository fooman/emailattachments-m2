<?php
/**
 * @author     Kristof Ringleff
 * @package    Fooman_EmailAttachments
 * @copyright  Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fooman\EmailAttachments\Model;

class MailTransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{
    /**
     * @param Api\AttachmentInterface $attachment
     */
    public function addAttachment(Api\AttachmentInterface $attachment)
    {
        $this->message->addAttachment($attachment);
        
        //this doesn't work in 2.3 as \Magento\Framework\Mail\Message doesn't extend \Zend_Mail
        //any more, so the createAttachment method is undefined -> fatal error
//        $this->message->createAttachment(
//            $attachment->getContent(),
//            $attachment->getMimeType(),
//            $attachment->getDisposition(),
//            $attachment->getEncoding(),
//            $this->encodedFileName($attachment->getFilename())
//        );
    }

    protected function encodedFileName($subject)
    {
        return sprintf('=?utf-8?B?%s?=', base64_encode($subject));
    }
}
