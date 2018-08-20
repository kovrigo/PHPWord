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

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style;

/**
 * ListItemRun element HTML writer
 *
 * @since 0.10.0
 */
class ListItemRun extends ListItem
{

    /**
     * Write list item run
     *
     * @return string
     */
    public function write()
    {
        $placeholderTemplate = "{%s_%s_%s}";
        $numStyle = $this->element->getStyle()->getNumStyle();
        $listType = $this->getListType();
        // Write content
        $content = sprintf($placeholderTemplate, "begin", $numStyle, $listType);
        $content .= '<li>';
        $writer = new Container($this->parentWriter, $this->element);
        $content .= $writer->write();
        $content .= '</li>';
        $content .= sprintf($placeholderTemplate, "end", $numStyle, $listType);
        return $content;
    }

    /**
     * Get list type ("bullets", "decimal" etc.)
     * @return string
     */
    private function getListType() 
    {
        $numStyle = $this->element->getStyle()->getNumStyle();
        $levels = Style::getStyle($numStyle)->getLevels();
        $level = $levels[$this->element->getDepth()]; 
        return $level->getFormat();
    }

}
