<?php

namespace Redstage\NewsletterPopup\Block;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class ModalOverlay
 *
 * @category    Inchoo
 * @package     Inchoo_ModalOverlay
 * @copyright   Copyright (c) Inchoo (http://inchoo.net/)
 */
class Popup extends Template
{
    /**
     * ModalOverlay constructor.
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }
}
