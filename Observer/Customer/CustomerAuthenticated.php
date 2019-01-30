<?php
/*
 * Copyright © 2019 Krish Technolabs. All rights reserved.
 * See COPYING.txt for license details
 */

namespace Ktpl\RememberMe\Observer\Customer;

use Ktpl\RememberMe\Helper\Data as RememberMeCookie;

class CustomerAuthenticated implements \Magento\Framework\Event\ObserverInterface {

    /**
     * @var RememberMeCookie
     */
    private $cookieHelper;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    public function __construct(\Magento\Framework\App\RequestInterface $request, RememberMeCookie $cookieHelper) {
        $this->request = $request;
        $this->cookieHelper = $cookieHelper;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
    \Magento\Framework\Event\Observer $observer
    ) {
        try {
            $model = $observer->getModel();
            $login = $this->request->getPost('login');
            $email = $model->getEmail();
            $password = $observer->getPassword();
            if (array_key_exists('rememberme', $login)) {
                $logindetails = ['username' => $email, 'password' => $password, 'remchkbox' => 1];
                $logindetails = json_encode($logindetails);
                $this->cookieHelper->set($logindetails, $this->cookieHelper->getCookielifetime());
            } else {
                $this->cookieHelper->delete('remember');
            }
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }

}
