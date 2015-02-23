<?php

namespace Administration\Controller;

use Administration\Entity\ServiceActivity;
use Administration\Entity\Service;
use Administration\Entity\ServiceOrganization;
use Administration\Entity\ServiceSector;
use Administration\Form\Filter\ServiceActivityFormFilter;
use Administration\Form\Filter\ServiceFormFilter;
use Administration\Form\Filter\ServiceOrganizationFormFilter;
use Administration\Form\Filter\ServiceSectorFormFilter;
use Administration\Form\ServiceActivityForm;
use Administration\Form\ServiceForm;
use Administration\Form\ServiceOrganizationForm;
use Administration\Form\ServiceSectorForm;
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

        $mService = $this->getEntityManager()->getRepository('Administration\Entity\Service');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mService->createQueryBuilder('Service')));
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

            $formFilter = new ServiceFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
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

            $formFilter = new ServiceFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $service->setCountry($country);
                $this->getEntityManager()->persist($service);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('Service successfully edited.');
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

    public function viewOrganizationAction () {

        $mOrganization = $this->getEntityManager()->getRepository('Administration\Entity\ServiceOrganization');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mOrganization->createQueryBuilder('ServiceOrganization')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        $page = (int)$this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function addOrganizationAction () {

        $request = $this->getRequest();

        $serviceOrganization = new ServiceOrganization();
        $form = new ServiceOrganizationForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\ServiceOrganization'));
        $form->bind($serviceOrganization);

        if ($request->isPost()) {

            $formFilter = new ServiceOrganizationFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $serviceOrganization->setCountry($country);
                $this->getEntityManager()->persist($serviceOrganization);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('New service organization successfully added.');
                return $this->redirect()->toRoute('service/viewOrganization');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editOrganizationAction () {

        $request = $this->getRequest();
        $serviceOrganizationId = (int) $this->params()->fromRoute('id');
        $serviceOrganization = $this->getEntityManager()->getRepository('Administration\Entity\ServiceOrganization')
            ->findOneBy(array('id' => $serviceOrganizationId));

        $form = new ServiceOrganizationForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\ServiceOrganization'));
        $form->bind($serviceOrganization);

        if ($request->isPost()) {

            $formFilter = new ServiceOrganizationFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $serviceOrganization->setCountry($country);
                $this->getEntityManager()->persist($serviceOrganization);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('Service organization successfully edited.');
                return $this->redirect()->toRoute('service/viewOrganization');
            }
        }

        return new ViewModel(array('form' => $form, 'serviceOrganizationId' => $serviceOrganizationId));
    }

    public function deleteOrganizationAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $serviceOrganization = $this->getEntityManager()->getRepository('Administration\Entity\ServiceOrganization')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($serviceOrganization);
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

    public function viewSectorAction () {

        $mSector = $this->getEntityManager()->getRepository('Administration\Entity\ServiceSector');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mSector->createQueryBuilder('ServiceSector')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        $page = (int)$this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function addSectorAction () {

        $request = $this->getRequest();

        $serviceSector = new ServiceSector();
        $form = new ServiceSectorForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\ServiceSector'));
        $form->bind($serviceSector);

        if ($request->isPost()) {

            $formFilter = new ServiceSectorFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $serviceSector->setCountry($country);
                $this->getEntityManager()->persist($serviceSector);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('New service Sector successfully added.');
                return $this->redirect()->toRoute('service/viewSector');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editSectorAction () {

        $request = $this->getRequest();
        $serviceSectorId = (int) $this->params()->fromRoute('id');
        $serviceSector = $this->getEntityManager()->getRepository('Administration\Entity\ServiceSector')
            ->findOneBy(array('id' => $serviceSectorId));

        $form = new ServiceSectorForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\ServiceSector'));
        $form->bind($serviceSector);

        if ($request->isPost()) {

            $formFilter = new ServiceSectorFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $serviceSector->setCountry($country);
                $this->getEntityManager()->persist($serviceSector);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('Service Sector successfully edited.');
                return $this->redirect()->toRoute('service/viewSector');
            }
        }

        return new ViewModel(array('form' => $form, 'serviceSectorId' => $serviceSectorId));
    }

    public function deleteSectorAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $serviceSector = $this->getEntityManager()->getRepository('Administration\Entity\ServiceSector')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($serviceSector);
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

    public function viewActivityAction () {

        $mActivity = $this->getEntityManager()->getRepository('Administration\Entity\ServiceActivity');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mActivity->createQueryBuilder('ServiceActivity')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        $page = (int)$this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function addActivityAction () {

        $request = $this->getRequest();

        $activity = new ServiceActivity();
        $form = new ServiceActivityForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\ServiceActivity'));
        $form->bind($activity);

        if ($request->isPost()) {

            $formFilter = new ServiceActivityFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $activity->setCountry($country);
                $this->getEntityManager()->persist($activity);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('New activity successfully added.');
                return $this->redirect()->toRoute('service/viewActivity');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editActivityAction () {

        $request = $this->getRequest();
        $activityId = (int) $this->params()->fromRoute('id');
        $activity = $this->getEntityManager()->getRepository('Administration\Entity\ServiceActivity')
            ->findOneBy(array('id' => $activityId));

        $form = new ServiceActivityForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\ServiceActivity'));
        $form->bind($activity);

        if ($request->isPost()) {

            $formFilter = new ServiceActivityFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $activity->setCountry($country);
                $this->getEntityManager()->persist($activity);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('Activity successfully edited.');
                return $this->redirect()->toRoute('service/viewActivity');
            }
        }

        return new ViewModel(array('form' => $form, 'activityId' => $activityId));
    }

    public function deleteActivityAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $activity = $this->getEntityManager()->getRepository('Administration\Entity\ServiceActivity')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($activity);
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
