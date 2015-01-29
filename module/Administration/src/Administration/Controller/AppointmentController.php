<?php

namespace Administration\Controller;

use Administration\Entity\AppointmentCategory;
use Administration\Entity\Faq;
use Administration\Entity\FaqCategory;
use Administration\Form\AppointmentCategoryForm;
use Administration\Form\FaqCategoryForm;
use Administration\Form\FaqForm;

use Administration\Provider\ProvidesEntityManager;
use Doctrine\ORM\Query;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class AppointmentController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction () {

        $appointment = $this->getEntityManager()->getRepository('Administration\Entity\Appointment')
            ->findBy(array(), array('date' => 'ASC'));

        $dql = 'Select appCat from Administration\Entity\Appointment app left join Administration\Entity\AppointmentCategory appCat
         with appCat.id = app.category group by app.category';
        $categories = $this->getEntityManager()->createQuery($dql)->getResult();

        return new ViewModel(array(
            'data' => $appointment,
            'categories' => $categories,
        ));
    }

    public function deleteAppointmentAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $appointment = $this->getEntityManager()->getRepository('Administration\Entity\Appointment')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($appointment);
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

    public function viewAppointmentCategoryAction () {

        $mFaqCategory = $this->getEntityManager()->getRepository('Administration\Entity\AppointmentCategory');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mFaqCategory->createQueryBuilder('AppointmentCategory')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        $page = (int) $this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function addAppointmentCategoryAction () {

        $request = $this->getRequest();

        $appointmentCategory = new AppointmentCategory();
        $form = new AppointmentCategoryForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\AppointmentCategory'));
        $form->bind($appointmentCategory);

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $appointmentCategory->setCountry($country);

                $this->getEntityManager()->persist($appointmentCategory);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('New Appointment category successfully added.');
                return $this->redirect()->toRoute('appointment/viewAppointmentCategory');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editAppointmentCategoryAction () {

        $request = $this->getRequest();

        $id = (int) $this->params()->fromRoute('id');
        $appointmentCategory = $this->getEntityManager()->getRepository('Administration\Entity\AppointmentCategory')
            ->findOneBy(array('id' => $id));

        $form = new AppointmentCategoryForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\AppointmentCategory'));
        $form->bind($appointmentCategory);

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $appointmentCategory->setCountry($country);

                $this->getEntityManager()->persist($appointmentCategory);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addSuccessMessage('Appointment category successfully edited.');
                return $this->redirect()->toRoute('appointment/viewAppointmentCategory');
            }
        }

        return new ViewModel(array('form' => $form, 'id' => $id));
    }

    public function deleteAppointmentCategoryAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $id = (int)$this->params()->fromRoute('id');
            $appointmentCategory = $this->getEntityManager()->getRepository('Administration\Entity\AppointmentCategory')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($appointmentCategory);
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
