<?php

namespace Redstage\NewsletterPopup\Model;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Override Subscriber to change save and include more
 *
 * @package Redstage\NewsletterPopup\Model
 */
class Subscriber extends \Magento\Newsletter\Model\Subscriber
{
    /**
     * Subscribes by email
     *
     * @param string|null $email
     * @param string|null $name
     * @param string $phone
     * @throws \Exception
     * @return int
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function subscribe($email, $name = null, $phone = null)
    {
        $this->loadByEmail($email);

        if ($this->getId() && $this->getStatus() == self::STATUS_SUBSCRIBED) {
            return $this->getStatus();
        }

        if (!$this->getId()) {
            $this->setSubscriberConfirmCode($this->randomSequence());
        }

        $isConfirmNeed = $this->_scopeConfig->getValue(
            self::XML_PATH_CONFIRMATION_FLAG,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ) == 1 ? true : false;

        $isSubscribeOwnEmail = $this->_customerSession->isLoggedIn()
            && $this->_customerSession->getCustomerDataObject()->getEmail() == $email;

        if (!$this->getId() || $this->getStatus() == self::STATUS_UNSUBSCRIBED
            || $this->getStatus() == self::STATUS_NOT_ACTIVE
        ) {
            if ($isConfirmNeed === true) {
                $this->setStatus(self::STATUS_NOT_ACTIVE);
            } else {
                $this->setStatus(self::STATUS_SUBSCRIBED);
            }
            $this->setSubscriberEmail($email);
            $this->setData('name', $name);
            $this->setData('phone', $phone);
        }

        if ($isSubscribeOwnEmail) {
            try {
                $customer = $this->customerRepository->getById($this->_customerSession->getCustomerId());
                $this->setStoreId($customer->getStoreId());
                $this->setCustomerId($customer->getId());
            } catch (NoSuchEntityException $e) {
                $this->setStoreId($this->_storeManager->getStore()->getId());
                $this->setCustomerId(0);
            }
        } else {
            $this->setStoreId($this->_storeManager->getStore()->getId());
            $this->setCustomerId(0);
        }

        $this->setStatusChanged(true);

        try {
            /* Save model before sending out email */
            $this->save();
            if ($isConfirmNeed === true) {
                $this->sendConfirmationRequestEmail();
            } else {
                $this->sendConfirmationSuccessEmail();
            }
            return $this->getStatus();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
