<?php

namespace Fooman\EmailAttachments\Observer;

class Common extends \PHPUnit_Framework_TestCase
{
    protected $mailhogClient;
    protected $objectManager;

    const BASE_URL = 'http://127.0.0.1:8025/api/';

    protected function setUp()
    {
        parent::setUp();
        $this->mailhogClient = new \Zend_Http_Client();
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
    }

    public function getLastEmail()
    {
        $this->mailhogClient->setUri(self::BASE_URL . 'v2/messages?limit=1');
        $lastEmail = json_decode($this->mailhogClient->request()->getBody(), true);
        $lastEmailId = $lastEmail['items'][0]['ID'];
        $this->mailhogClient->resetParameters(true);
        $this->mailhogClient->setUri(self::BASE_URL . 'v1/messages/' . $lastEmailId);
        return json_decode($this->mailhogClient->request()->getBody(), true);
    }

    public function getAttachmentOfType($email, $type)
    {
        if (isset($email['MIME']['Parts'])) {
            foreach ($email['MIME']['Parts'] as $part) {
                if (!isset($type, $part['Headers']['Content-Type'])) {
                    continue;
                }
                if ($part['Headers']['Content-Type'][0] == $type) {
                    return $part;
                }
            }
        }

        return false;
    }
}
