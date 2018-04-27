<?php

namespace Fooman\EmailAttachments\Model\Email;

use Zend\Mime\Mime;
use Zend\Mime\Part;

class Message extends \Magento\Framework\Mail\Message implements \Magento\Framework\Mail\MailMessageInterface {

    /**
     * @var [] \Fooman\EmailAttachments\Model\Api\AttachmentInterface
     */
    private $_attachments;

    /**
     * @var \Zend\Mail\Message
     */
    private $zendMessage;

    /**
     * Message type
     *
     * @var string
     */
    private $messageType = self::TYPE_TEXT;

    /**
     * Initialize dependencies.
     *
     * @param string $charset
     */
    public function __construct($charset = 'utf-8') {
        $this->zendMessage = new \Zend\Mail\Message();
        $this->zendMessage->setEncoding($charset);
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated
     * @see \Magento\Framework\Mail\Message::setBodyText
     * @see \Magento\Framework\Mail\Message::setBodyHtml
     */
    public function setMessageType($type) {
        $this->messageType = $type;
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated
     * @see \Magento\Framework\Mail\Message::setBodyText
     * @see \Magento\Framework\Mail\Message::setBodyHtml
     * @see https://akrabat.com/sending-attachments-in-multipart-emails-with-zendmail/
     */
    public function setBody($bodyText) {
        if (is_string($bodyText) && $this->messageType === Mime::TYPE_HTML) {
            $part = $this->createHtmlMimePart($bodyText);
        }
        else {
            $part = $this->createTextMimePart($bodyText);
        }


        $body = new \Zend\Mime\Message();
        if (!empty($this->_attachments)) {
//            $content = new \Zend\Mime\Message();
//            $content->addPart($part);
//
//            $contentPart = new Part($content->generateMessage());
//            $contentPart->type = Mime::MULTIPART_ALTERNATIVE . ";\n" . ' boundary="' . $content->getMime()->boundary() . '"';
//            $body->addPart($contentPart);
            $body->addPart($part);
            $messageType = Mime::MULTIPART_RELATED;

            foreach ($this->_attachments as $attachment) {
                $attachmentPart = new Part($attachment->getContent());
                $attachmentPart->filename = $this->_encodedFileName($attachment->getFilename());
                $attachmentPart->type = $attachment->getMimeType();
                $attachmentPart->encoding = $attachment->getEncoding();
                $attachmentPart->disposition = $attachment->getDisposition();
                $body->addPart($attachmentPart);
            }
        }
        else {
            $body->addPart($part);
            $messageType = $this->messageType;
        }

        $this->zendMessage->setBody($body);
        $this->zendMessage->getHeaders()->get('content-type')->setType($messageType);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject($subject) {
        $this->zendMessage->setSubject($subject);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject() {
        return $this->zendMessage->getSubject();
    }

    /**
     * {@inheritdoc}
     */
    public function getBody() {
        return $this->zendMessage->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function setFrom($fromAddress) {
        $this->zendMessage->setFrom($fromAddress);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addTo($toAddress) {
        $this->zendMessage->addTo($toAddress);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addCc($ccAddress) {
        $this->zendMessage->addCc($ccAddress);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addBcc($bccAddress) {
        $this->zendMessage->addBcc($bccAddress);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setReplyTo($replyToAddress) {
        $this->zendMessage->setReplyTo($replyToAddress);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRawMessage() {
        return $this->zendMessage->toString();
    }

    /**
     * Create HTML mime message from the string.
     *
     * @param string $htmlBody
     * @return \Zend\Mime\Message
     */
    private function createHtmlMimeFromString($htmlBody) {
        $htmlPart = new Part($htmlBody);
        $htmlPart->setCharset($this->zendMessage->getEncoding());
        $htmlPart->setType(Mime::TYPE_HTML);
        $mimeMessage = new \Zend\Mime\Message();
        $mimeMessage->addPart($htmlPart);
        return $mimeMessage;
    }

    /**
     * Create HTML mime part from the string.
     *
     * @access private
     * @param string $html
     * @return \Zend\Mime\Part
     */
    private function createHtmlMimePart($html) {
        $htmlPart = new Part($html);
        $htmlPart->setCharset($this->zendMessage->getEncoding());
        $htmlPart->setType(Mime::TYPE_HTML);        
        $htmlPart->setEncoding(Mime::ENCODING_QUOTEDPRINTABLE);
        return $htmlPart;
    }

    /**
     * Create plain text mime part from the string.
     *
     * @access private
     * @param string $text
     * @return \Zend\Mime\Part
     */
    private function createTextMimePart($text) {
        $textPart = new Part($text);
        $textPart->setCharset($this->zendMessage->getEncoding());
        $textPart->setType(Mime::TYPE_TEXT);
        $textPart->setEncoding(Mime::ENCODING_QUOTEDPRINTABLE);
        return $textPart;
    }

    private function _encodedFileName($subject) {
        return sprintf('=?utf-8?B?%s?=', base64_encode($subject));
    }

    /**
     * {@inheritdoc}
     */
    public function setBodyHtml($html) {
        $this->setMessageType(self::TYPE_HTML);
        return $this->setBody($html);
    }

    /**
     * {@inheritdoc}
     */
    public function setBodyText($text) {
        $this->setMessageType(self::TYPE_TEXT);
        return $this->setBody($text);
    }

    /**
     * Add attachment for this message
     * 
     * @access public
     * @param \Fooman\EmailAttachments\Model\Api\AttachmentInterface $attachment
     */
    public function addAttachment($attachment) {
        $this->_attachments[] = $attachment;
    }

}
