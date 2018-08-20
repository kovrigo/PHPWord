<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Element\Image as ImageElement;
use PhpOffice\PhpWord\Writer\HTML\Style\Image as ImageStyleWriter;

/**
 * Image element HTML writer
 *
 * @since 0.10.0
 */
class Image extends Text
{
    /**
     * Write image
     *
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof ImageElement) {
            return '';
        }
        $content = '';
        $imageData = $this->element->getImageStringData(true);
        if ($imageData !== null) {
            $styleWriter = new ImageStyleWriter($this->element->getStyle());
            $style = $styleWriter->write();

            $dir = $this->parentWriter->getFilesDir();
            if (is_null($dir)) {
                $src = 'data:' . $this->element->getImageType() . ';base64,' . $imageData;
            } else {
                $src = $dir . $this->element->getTarget();
                $data = base64_decode($imageData);
                $image = imagecreatefromstring($data);
                $imageContentType = $this->element->getImageType();
                $imageType = explode("/", $imageContentType)[1];
                header("Content-Type: " . $imageContentType);
                switch ($imageType) {
                    case "png":
                        imageAlphaBlending($image, true);
                        imageSaveAlpha($image, true);
                        imagepng($image, $src);
                        break;
                    case "jpeg":
                        imagejpeg($image, $src);
                        break;
                    default:
                        return "";
                        break;
                }
            }

            $content .= $this->writeOpening();
            $content .= "<img border=\"0\" style=\"{$style}\" src=\"{$src}\"/>";
            $content .= $this->writeClosing();
        }

        return $content;
    }
}
