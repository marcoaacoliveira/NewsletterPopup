<?php

namespace Redstage\NewsletterPopup\Plugin;

use Psr\Log\LoggerInterface;
use Redstage\NewsletterPopup\Block\Popup as PopupBlock;
use Redstage\NewsletterPopup\Cookie\Popup;

/**
 * Class UpdateCookiePlugin
 *
 * @package Redstage\NewsletterPopup\Plugin
 */
class CookiePlugin
{

    protected $cookie;
    protected $logger;

    /**
     * CookiePlugin constructor.
     *
     * @param Popup $cookie
     * @param LoggerInterface $logger
     */
    public function __construct(Popup $cookie, LoggerInterface $logger)
    {
        $this->cookie = $cookie;
        $this->logger = $logger;
    }
    /**
     * Verify if render the block
     *
     * @param PopupBlock $subject
     * @param callable $proceed
     * @return callable
     */
    public function aroundToHtml(PopupBlock $subject, callable $proceed)
    {
        $cookie = $this->cookie->get();
        if (!$cookie) {
            try {
                $this->cookie->set('1');
            } catch (\Exception $exception) {
                $this->logger->log('error', $exception->getMessage());
            }
            return $proceed();
        }
    }

}
