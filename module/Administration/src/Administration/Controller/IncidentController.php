<?php

namespace Administration\Controller;

use Administration\Entity\Incident;
use Administration\Entity\IncidentCategory;
use Administration\Entity\IncidentType;
use Administration\Form\Filter\IncidentCategoryFormFilter;
use Administration\Form\Filter\IncidentFormFilter;
use Administration\Form\Filter\IncidentTypeFormFilter;
use Administration\Form\IncidentCategoryForm;
use Administration\Form\IncidentForm;
use Administration\Form\IncidentTypeForm;

use Administration\Provider\ProvidesEntityManager;
use Doctrine\ORM\Query;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class IncidentController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction () {

        $incident = $this->getEntityManager()->getRepository('Administration\Entity\Incident')->findAll();

        $dql = 'Select incidentCat from Administration\Entity\Incident incident left join Administration\Entity\IncidentCategory incidentCat
         with incidentCat.id = incident.category group by incident.category';

        $categories = $this->getEntityManager()->createQuery($dql)->getResult();

        return new ViewModel(array(
            'data' => $incident,
            'categories' => $categories,
        ));
    }

    public function addAction () {

        $request = $this->getRequest();
        $incident = new Incident();
        $form = new IncidentForm($this->getEntityManager(), $this->getServiceLocator());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\Incident'));
        $form->bind($incident);

        if ($request->isPost()) {

            $formFilter = new IncidentFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $incident->setCountry($country);
                $this->getEntityManager()->persist($incident);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addMessage('New FAQ successfully added.');
                return $this->redirect()->toRoute('incident');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editAction () {

        $request = $this->getRequest();
        $incidentId = (int) $this->params()->fromRoute('id');
        $incident = $this->getEntityManager()->getRepository('Administration\Entity\Incident')
            ->findOneBy(array('id' => $incidentId));

        $form = new IncidentForm($this->getEntityManager(), $this->getServiceLocator());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\Incident'));
        $form->bind($incident);

        if ($request->isPost()) {

            $formFilter = new IncidentFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $incident->setCountry($country);
                $this->getEntityManager()->persist($incident);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addMessage('New FAQ successfully added.');
                return $this->redirect()->toRoute('incident');
            }
        }

        return new ViewModel(array('form' => $form, 'incidentId' => $incidentId));
    }

    public function deleteAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $incident = $this->getEntityManager()->getRepository('Administration\Entity\Incident')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($incident);
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

    public function viewIncidentCategoryAction () {

        $mIncidentCategory = $this->getEntityManager()->getRepository('Administration\Entity\IncidentCategory');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mIncidentCategory->createQueryBuilder('IncidentCategory')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        $page = (int) $this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function viewIncidentTypeAction () {

        $mIncidentCategory = $this->getEntityManager()->getRepository('Administration\Entity\IncidentType');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mIncidentCategory->createQueryBuilder('IncidentType')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        $page = (int) $this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function addIncidentCategoryAction () {

        $request = $this->getRequest();
        $incidentCategory = new IncidentCategory();

        $form = new IncidentCategoryForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\IncidentCategory'));
        $form->bind($incidentCategory);

        if ($request->isPost()) {

            $formFilter = new IncidentCategoryFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $this->getEntityManager()->persist($incidentCategory);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('New Incident category successfully added.');
                return $this->redirect()->toRoute('incident/viewIncidentCategory');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editIncidentCategoryAction () {

        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id');
        $incidentCategory = $this->getEntityManager()->getRepository('Administration\Entity\IncidentCategory')
            ->findOneBy(array('id' => $id));

        $form = new IncidentCategoryForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\IncidentCategory'));
        $form->bind($incidentCategory);

        if ($request->isPost()) {

            $formFilter = new IncidentCategoryFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $this->getEntityManager()->persist($incidentCategory);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('Incident category successfully edited.');
                return $this->redirect()->toRoute('incident/viewIncidentCategory');
            }
        }

        return new ViewModel(array('form' => $form, 'id' => $id));
    }

    public function deleteIncidentCategoryAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $incidentCategory = $this->getEntityManager()->getRepository('Administration\Entity\IncidentCategory')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($incidentCategory);
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

    public function addIncidentTypeAction () {

        $request = $this->getRequest();
        $incidentType = new IncidentType();

        $form = new IncidentTypeForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\IncidentType'));
        $form->bind($incidentType);

        if ($request->isPost()) {

            $formFilter = new IncidentTypeFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $this->getEntityManager()->persist($incidentType);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('New Incident type successfully added.');
                return $this->redirect()->toRoute('incident/viewIncidentType');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editIncidentTypeAction () {

        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id');
        $incidentType = $this->getEntityManager()->getRepository('Administration\Entity\IncidentType')
            ->findOneBy(array('id' => $id));

        $form = new IncidentTypeForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\IncidentType'));
        $form->bind($incidentType);

        if ($request->isPost()) {

            $formFilter = new IncidentTypeFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $this->getEntityManager()->persist($incidentType);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('Incident type successfully edited.');
                return $this->redirect()->toRoute('incident/viewIncidentType');
            }
        }

        return new ViewModel(array('form' => $form, 'id' => $id));
    }

    public function deleteIncidentTypeAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $incidentType = $this->getEntityManager()->getRepository('Administration\Entity\IncidentType')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($incidentType);
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

    public function downloadImageAction () {

        $name = $this->params()->fromRoute('name');

        $image = $this->getEntityManager()->getRepository('Administration\Entity\File')
            ->findOneBy(array('name' => $name));

        $globalConfig = $this->serviceLocator->get('config');

        // get image content
        $response = $this->getResponse();

        $imageContent = file_get_contents($globalConfig['fileDir'] . $image->getName());

        $response->setContent($imageContent);
        $response
            ->getHeaders()
            ->addHeaderLine('Content-Transfer-Encoding', 'binary')
            ->addHeaderLine('Content-Type', 'image/png')
            ->addHeaderLine('Content-Length', mb_strlen($imageContent));

        return $response;

    }

}
