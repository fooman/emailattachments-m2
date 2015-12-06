<?php
/**
 * @author     Kristof Ringleff
 * @package    Fooman_EmailAttachments
 * @copyright  Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fooman\EmailAttachments\Model\Api;

interface AttachmentInterface
{
    public function getMimeType();

    public function getFilename();

    public function getDisposition();

    public function getEncoding();

    public function getContent();
}
