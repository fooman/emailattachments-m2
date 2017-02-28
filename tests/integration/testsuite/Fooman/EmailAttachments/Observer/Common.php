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

    /**
     * @param $pdf
     */
    protected function compareWithReceivedPdf($pdf)
    {
        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertEquals(strlen($pdf->render()), strlen(base64_decode($pdfAttachment['Body'])));
    }

    /**
     * @param      $pdf
     * @param bool $title
     */
    protected function comparePdfAsStringWithReceivedPdf($pdf, $title = false)
    {
        $pdfAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'application/pdf');
        $this->assertEquals(strlen($pdf), strlen(base64_decode($pdfAttachment['Body'])));
        if ($title !== false) {
            $this->assertEquals($title, $this->extractFilename($pdfAttachment));
        }
    }

    protected function checkReceivedHtmlTermsAttachment()
    {
        $termsAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'text/html; charset=UTF-8');
        $this->assertContains('Checkout agreement content: <b>HTML</b>', base64_decode($termsAttachment['Body']));
    }

    protected function checkReceivedTxtTermsAttachment()
    {
        $termsAttachment = $this->getAttachmentOfType($this->getLastEmail(), 'text/plain');
        $this->assertContains('Checkout agreement content: TEXT', base64_decode($termsAttachment['Body']));
    }

    protected function extractFilename($input)
    {
        $input = substr($input['Headers']['Content-Disposition'][0], strlen('attachment; filename="=?utf-8?B?'), -2);
        return base64_decode($input);
    }
}
