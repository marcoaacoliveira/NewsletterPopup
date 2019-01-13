<?php

namespace Redstage\NewsletterPopup\Controller\Newsletter;

use Magento\Customer\Api\AccountManagementInterface as CustomerAccountManagement;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validator\EmailAddress as EmailValidator;
use Magento\Newsletter\Controller\Subscriber\NewAction;
use Magento\Newsletter\Model\Subscriber;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Action to save requests to Newsletters
 */
class Save extends NewAction
{

    protected $formKeyValidator;
    protected $jsonFactory;

    /**
     * Initialize depedencies.
     *
     * @param Context $context
     * @param SubscriberFactory $subscriberFactory
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param CustomerUrl $customerUrl
     * @param CustomerAccountManagement $customerAccountManagement
     * @param EmailValidator|null $emailValidator
     * @param Validator $formKeyValidator
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        SubscriberFactory $subscriberFactory,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        CustomerUrl $customerUrl,
        CustomerAccountManagement $customerAccountManagement,
        EmailValidator $emailValidator = null,
        Validator $formKeyValidator,
        JsonFactory $jsonFactory
    ) {

        $this->formKeyValidator = $formKeyValidator;
        $this->jsonFactory = $jsonFactory;
        parent::__construct(
            $context,
            $subscriberFactory,
            $customerSession,
            $storeManager,
            $customerUrl,
            $customerAccountManagement,
            $emailValidator = null
        );
    }

    /**
     * Execute save Newsletter on Popup
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|string
     */
    public function execute()
    {
        $request = $this->getRequest();
        $result = $this->jsonFactory->create();
        // Check validate form key
        if (!$this->formKeyValidator->validate($request)) {
            $result_json["message"] = __("Something Wrong, try again later");
            return $result->setData($result_json);
        }

        // Email is required and must be a post
        if (!$this->getRequest()->isPost() || !$this->getRequest()->getPost('email')) {
            $result_json["message"] = __('Email is required');
            return $result->setData($result_json);
        }

        $email = (string)$this->getRequest()->getPost('email');

        try {
            $this->validateEmailFormat($email);
            $this->validateGuestSubscription();
            $this->validateEmailAvailable($email);

            $subscriber = $this->_subscriberFactory->create()->loadByEmail($email);
            if ($subscriber->getId()
                && (int)$subscriber->getSubscriberStatus() === Subscriber::STATUS_SUBSCRIBED
            ) {
                $result_json["message"] = __('This email address is already subscribed.');
                return $result->setData($result_json);
            }

            $status = (int)$this->_subscriberFactory->create()->subscribe(
                $email,
                $request->getParam('name'),
                $request->getParam('phone')
            );
            $result_json["saved"] = "true";
            if ($status == Subscriber::STATUS_NOT_ACTIVE) {
                $result_json["message"] = __("Waiting for approval");
            }
            $result_json["message"] = __("Subscribed successfully");
            return $result->setData($result_json);
        } catch (LocalizedException $e) {
            $result_json["message"] = __('There was a problem with the subscription: %1', $e->getMessage());
            return $result->setData($result_json);
        } catch (\Exception $e) {
            $result_json["message"] = __('Something went wrong with the subscription.');
            return $result->setData($result_json);
        }
    }
}
