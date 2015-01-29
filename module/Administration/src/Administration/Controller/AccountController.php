<?php
namespace Administration\Controller;

use Administration\Entity\User;
use Administration\Form\Filter\UserFormFilter;
use Administration\Form\UserForm;

use Administration\Provider\ProvidesEntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class AccountController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction () {

        $mUser = $this->getEntityManager()->getRepository('Administration\Entity\User');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mUser->createQueryBuilder('User')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        $page = (int)$this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function addAction () {

        $request = $this->getRequest();
        $user = new User();

        $form = new UserForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\User'));
        $form->bind($user);

        if ($request->isPost()) {

            $formFilter = new UserFormFilter();
            $form->setInputFilter($formFilter->getAddInputFilter());
            $form->setData($request->getPost());

            if ($this->getEntityManager()->getRepository('Administration\Entity\User')->findOneBy(
                array('email' => $form->get('email')->getValue()))
            ) {

                $this->flashMessenger()->addErrorMessage('Email address you entered is already in use. Please enter new one.');

                return $this->redirect()->toRoute('account/add');

            } elseif ($form->isValid()) {

                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('New user successfully added.');

                return $this->redirect()->toRoute('account');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editAction () {

        $request = $this->getRequest();
        $translate = $this->getServiceLocator()->get('viewhelpermanager')->get('translate');

        $id = (int)$this->params()->fromRoute('id');
        $userData = $this->getEntityManager()->getRepository('Administration\Entity\User')->findOneBy(array('id' => $id));

        $form = new UserForm();

        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\User'));
        $form->bind($userData);

        if ($request->isPost()) {

            $formFilter = new UserFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            if (!User::hashPassword($userData, $request->getPost('oldPassword')) && $request->getPost('oldPassword') != "") {
                $form->setMessages(array(
                    'oldPassword' => array($translate('Old password incorrect')),
                ));

                return new ViewModel(array(
                    'form' => $form,
                    'userId' => $id
                ));
            }

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $this->getEntityManager()->persist($userData);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('account');
            }
        }

        return new ViewModel(array('form' => $form, 'userId' => $id));
    }

    public function deleteAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $user = $this->getEntityManager()->getRepository('Administration\Entity\User')->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($user);
            $this->getEntityManager()->flush();

            $result = new JsonModel(array(
                'success' => true,
                'id' => $id,
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

    public function profileAction () {

        $request = $this->getRequest();
        $translator = $this->getServiceLocator()->get('viewhelpermanager')->get('translate');

        $userId = $this->getServiceLocator()->get('AuthService')->getIdentity()->getId();
        $user = $this->getEntityManager()->getRepository('Administration\Entity\User')->findOneBy(array('id' => $userId));

        $form = new UserForm();
        $form->get('type')->setAttribute('disabled', 'disabled');
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\User'));
        $form->bind($user);

        if ($request->isPost()) {

            $formFilter = new UserFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());

            if (!User::hashPassword($user, $request->getPost('oldPassword')) && $request->getPost('oldPassword') != "") {
                $form->setMessages(array(
                    'oldPassword' => array($translator('Old password incorrect')),
                ));
                return new ViewModel(array(
                    'form' => $form,
                    'userId' => $userId,
                ));
            }

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage($translator('Your profile data successfully changes.'));

                $this->redirect()->toRoute('account/profile');
            }
        }

        return new ViewModel(array('form' => $form, 'userId' => $userId, 'type' => $user->getType()));
    }

}
