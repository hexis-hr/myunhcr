<?php

namespace Administration\Controller;

use Administration\Entity\News;
use Administration\Entity\Service;
use Administration\Entity\ServicePartner;
use Administration\Form\NewsForm;

use Administration\Form\ServiceForm;
use Administration\Form\ServicePartnerForm;
use Administration\Provider\ProvidesEntityManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;

use Doctrine\ORM\Query;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class ServiceController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction () {

        $mNews = $this->getEntityManager()->getRepository('Administration\Entity\Service');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mNews->createQueryBuilder('Service')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        $page = (int)$this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function addServiceAction () {

        $request = $this->getRequest();

        $service = new Service();
        $form = new ServiceForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\Service'));
        $form->bind($service);

        if ($request->isPost()) {

            //todo
//            $formFilter = new UserFormFilter();
//            $form->setInputFilter($formFilter->getAddInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $service->setCountry($country);
                $this->getEntityManager()->persist($service);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('New service successfully added.');
                return $this->redirect()->toRoute('service');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editServiceAction () {

        $request = $this->getRequest();
        $serviceId = (int) $this->params()->fromRoute('id');
        $service = $this->getEntityManager()->getRepository('Administration\Entity\Service')
            ->findOneBy(array('id' => $serviceId));

        $form = new ServiceForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\Service'));
        $form->bind($service);

        if ($request->isPost()) {

            //todo
//            $formFilter = new UserFormFilter();
//            $form->setInputFilter($formFilter->getAddInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $service->setCountry($country);
                $this->getEntityManager()->persist($service);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('New service successfully added.');
                return $this->redirect()->toRoute('service');
            }
        }

        return new ViewModel(array('form' => $form, 'serviceId' => $serviceId));
    }

    public function deleteServiceAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $service = $this->getEntityManager()->getRepository('Administration\Entity\Service')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($service);
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

    public function viewPartnerAction () {

        $mNews = $this->getEntityManager()->getRepository('Administration\Entity\ServicePartner');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mNews->createQueryBuilder('ServicePartner')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        $page = (int)$this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function addPartnerAction () {

        $request = $this->getRequest();

        $servicePartner = new ServicePartner();
        $form = new ServicePartnerForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\ServicePartner'));
        $form->bind($servicePartner);

        if ($request->isPost()) {

            //todo
//            $formFilter = new UserFormFilter();
//            $form->setInputFilter($formFilter->getAddInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $servicePartner->setCountry($country);
                $this->getEntityManager()->persist($servicePartner);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('New service partner successfully added.');
                return $this->redirect()->toRoute('service/viewPartner');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editPartnerAction () {

        $request = $this->getRequest();
        $servicePartnerId = (int) $this->params()->fromRoute('id');
        $servicePartner = $this->getEntityManager()->getRepository('Administration\Entity\ServicePartner')
            ->findOneBy(array('id' => $servicePartnerId));

        $form = new ServicePartnerForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\ServicePartner'));
        $form->bind($servicePartner);

        if ($request->isPost()) {

            //todo
//            $formFilter = new UserFormFilter();
//            $form->setInputFilter($formFilter->getAddInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $servicePartner->setCountry($country);
                $this->getEntityManager()->persist($servicePartner);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('New news successfully added.');
                return $this->redirect()->toRoute('service/viewPartner');
            }
        }

        return new ViewModel(array('form' => $form, 'servicePartnerId' => $servicePartnerId));
    }

    public function deletePartnerAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $servicePartner = $this->getEntityManager()->getRepository('Administration\Entity\ServicePartner')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($servicePartner);
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

}
