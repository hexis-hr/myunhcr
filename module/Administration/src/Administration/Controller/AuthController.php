<?php
namespace Administration\Controller;

use Administration\Entity\User;
use Administration\Form\LoginForm;

use Administration\Provider\ProvidesEntityManager;
use Zend\Crypt\BlockCipher;
use Zend\Crypt\Symmetric\Mcrypt;
use Zend\Http\Header\SetCookie;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class AuthController extends AbstractActionController {

    protected $form;
    protected $storage;
    protected $authservice;

    use ProvidesEntityManager;

    public function getAuthService () {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()
                ->get('AuthService');
        }

        return $this->authservice;
    }

    public function getSessionStorage () {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()
                ->get('AuthStorage');
        }

        return $this->storage;
    }

    public function loginAction () {

        $globalConfig = $this->serviceLocator->get('config');

        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()) {
            $this->flashmessenger()->addMessage('You are not allowed to enter this site.
            Please log in with another account or contact administrator.');
            return $this->redirect()->toRoute('administration');
        }

        $this->layout()->setTemplate('layout/auth');

        $form = new LoginForm();


//        if (isset($_COOKIE[$globalConfig['cookie']['rememberMeName']])) {
//
//            $blockCipher = new BlockCipher(new Mcrypt(array('algo' => 'aes')));
//            $blockCipher->setKey($globalConfig['cookie']['rememberMeKey']);
//            $value = $blockCipher->decrypt($_COOKIE[$globalConfig['cookie']['rememberMeName']]);
//
//            $userData = $this->getEntityManager()->getRepository('Administration\Entity\User')
//                ->findOneBy(array('id' => $value));
//
//        }

        return new ViewModel(array(
            'form' => $form,
            'messages' => $this->flashmessenger()->getMessages(),
        ));
    }

    public function authenticateAction () {

        $request = $this->getRequest();
        $globalConfig = $this->serviceLocator->get('config');

        $form = new LoginForm();
        $redirect = 'login';

        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {

                //check authentication...
                $this->getAuthService()->getAdapter()
                    ->setIdentity($request->getPost('email'))
                    ->setCredential($request->getPost('password'));

                $result = $this->getAuthService()->authenticate();
                foreach ($result->getMessages() as $message) {
                    //save message temporary into flashmessenger
                    $this->flashmessenger()->addMessage($message);
                }

                if ($result->isValid()) {
                    $redirect = 'administration';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberMe') == 1) {
                        $this->getSessionStorage()->setRememberMe();

                        $blockCipher = new BlockCipher(new Mcrypt(array('algo' => 'aes')));
                        $blockCipher->setKey($globalConfig['cookie']['rememberMeKey']);
                        $value = $blockCipher->encrypt($result->getIdentity()->getId());

//                        $cookie = new SetCookie($globalConfig['cookie']['rememberMeName'], $value,
//                            time() + $globalConfig['cookie']['rememberMeSeconds']); // 30 days
//                        $this->getResponse()->getHeaders()->addHeader($cookie);

                        setcookie($globalConfig['cookie']['rememberMeName'], $value,
                            time() + $globalConfig['cookie']['rememberMeSeconds']);

                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }

                    $this->getAuthService()->getStorage()->write($result->getIdentity());
                }
            }
        }

        return $this->redirect()->toRoute($redirect);
    }

    public function logoutAction () {

        $globalConfig = $this->serviceLocator->get('config');

        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();
//
//        if (isset($_COOKIE[$globalConfig['cookie']['rememberMeName']])) {
//
//            unset($_COOKIE[$globalConfig['cookie']['rememberMeName']]);
//
//            $cookie = new SetCookie($globalConfig['cookie']['rememberMeName'], '',
//                time() - $globalConfig['remember_me_seconds']); // 30 days in past
//            $this->getResponse()->getHeaders()->addHeader($cookie);
//        }

        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }

}
