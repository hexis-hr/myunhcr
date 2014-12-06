<?php
namespace Administration\Controller;

use Administration\Form\LoginForm;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class AuthController extends AbstractActionController {

    protected $form;
    protected $storage;
    protected $authservice;

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                ->get('AuthService');
        }

        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()
                ->get('AuthStorage');
        }

        return $this->storage;
    }

    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('administration');
        }

        $this->layout()->setTemplate('layout/auth');

        $form = new LoginForm();

        return new ViewModel(array(
            'form' => $form,
            'messages'  => $this->flashmessenger()->getMessages()
        ));
    }

    public function authenticateAction()
    {
        $form = new LoginForm();
        $redirect = 'login';
        $request = $this->getRequest();
        if ($request->isPost()){
            $form->setData($request->getPost());
            if ($form->isValid()){

                //check authentication...
                $this->getAuthService()->getAdapter()
                    ->setIdentity($request->getPost('email'))
                    ->setCredential($request->getPost('password'));

                $result = $this->getAuthService()->authenticate();
                foreach($result->getMessages() as $message)
                {
                    //save message temporary into flashmessenger
                    $this->flashmessenger()->addMessage($message);
                }

                if ($result->isValid()) {
                    $redirect = 'administration';
                    //check if it has rememberMe :
                        $this->getSessionStorage()
                            ->setRememberMe(1);
                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    $this->getAuthService()->getStorage()->write($result->getIdentity());
                }
            }
        }

        return $this->redirect()->toRoute($redirect);
    }

    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }

}
