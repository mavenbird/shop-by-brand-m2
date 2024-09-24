<?php
/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_Shopbybrand
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */

namespace Mavenbird\Shopbybrand\Model\Config\Source;

use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Data\OptionSourceInterface;

class StaticBlock implements OptionSourceInterface
{
    /**
     * Factory for block
     *
     * @var [type]
     */
    protected $_blockFactory;

    /**
     * Construct
     *
     * @param BlockFactory $blockFactory
     */
    public function __construct(BlockFactory $blockFactory)
    {
        $this->_blockFactory = $blockFactory;
    }

    /**
     * Options Array
     *
     * @return void
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->getOptionArray() as $identifier => $title) {
            $options[] = [
                'label' => $title,
                'value' => $identifier
            ];
        }

        return $options;
    }

    /**
     * Get Option Array
     *
     * @return void
     */
    public function getOptionArray()
    {
        $resultBlocks    = ['' => __('-- Please Select --')];
        $blockCollection = $this->_blockFactory->create()->getCollection();
        foreach ($blockCollection as $block) {
            $resultBlocks[$block->getData('identifier')] = $block->getData('title');
        }

        return $resultBlocks;
    }
}
