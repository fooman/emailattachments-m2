<?php

namespace Fooman\EmailAttachments\Test\Unit\Model;

class AttachmentContainerTest extends \PHPUnit_Framework_TestCase
{
    const TEST_CONTENT = 'Testing content';
    const TEST_MIME = 'text/plain';
    const TEST_FILENAME = 'filename.txt';
    const TEST_DISPOSITION = 'Disposition';
    const TEST_ENCODING = 'ENCODING';


    /**
     * @var \Fooman\EmailAttachments\Model\AttachmentContainer
     */
    protected $attachmentContainer;

    /**
     * @var \Fooman\EmailAttachments\Model\Attachment
     */
    protected $attachment;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->attachment = $objectManager->getObject(
            'Fooman\EmailAttachments\Model\Attachment',
            [
                'content'     => self::TEST_CONTENT,
                'mimeType'    => self::TEST_MIME,
                'fileName'    => self::TEST_FILENAME,
                'disposition' => self::TEST_DISPOSITION,
                'encoding'    => self::TEST_ENCODING
            ]
        );
        $this->attachmentContainer = $objectManager->getObject(
            'Fooman\EmailAttachments\Model\AttachmentContainer'
        );
        $this->attachmentContainer->resetAttachments();
    }

    public function testHasAttachments()
    {
        $this->assertFalse($this->attachmentContainer->hasAttachments());
        $this->attachmentContainer->addAttachment($this->attachment);
        $this->assertTrue($this->attachmentContainer->hasAttachments());
    }

    public function testResetAttachments()
    {
        $this->attachmentContainer->addAttachment($this->attachment);
        $this->assertTrue($this->attachmentContainer->hasAttachments());
        $this->attachmentContainer->resetAttachments();
        $this->assertFalse($this->attachmentContainer->hasAttachments());
    }

    public function testAttachments()
    {
        $this->attachmentContainer->addAttachment($this->attachment);
        $this->assertEquals([$this->attachment], $this->attachmentContainer->getAttachments());
    }
}
