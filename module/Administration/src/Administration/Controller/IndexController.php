<?php

namespace Administration\Controller;

use Administration\Provider\ProvidesEntityManager;

use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction () {

        $users = $this->getEntityManager()->getRepository('Administration\Entity\User')
            ->findAll();

        $news = $this->getEntityManager()->getRepository('Administration\Entity\News')
            ->findAll();

        $services = $this->getEntityManager()->getRepository('Administration\Entity\Service')
            ->findAll();

        $faqs = $this->getEntityManager()->getRepository('Administration\Entity\Faq')
            ->findAll();

        $incidents = $this->getEntityManager()->getRepository('Administration\Entity\Incident')
            ->findAll();

        $appointments = $this->getEntityManager()->getRepository('Administration\Entity\Appointment')
            ->findAll();

        $appointmentCategories = $this->getEntityManager()->getRepository('Administration\Entity\AppointmentCategory')
            ->findAll();

        $organizations = $this->getEntityManager()->getRepository('Administration\Entity\ServiceOrganization')
            ->findAll();

        $sectors = $this->getEntityManager()->getRepository('Administration\Entity\ServiceSector')
            ->findAll();

        $activities = $this->getEntityManager()->getRepository('Administration\Entity\ServiceActivity')
            ->findAll();

        $surveys = $this->getEntityManager()->getRepository('Administration\Entity\SurveyODK')
            ->findAll();

        $complaints = $this->getEntityManager()->getRepository('Administration\Entity\Complaint')
            ->findAll();

        return new ViewModel(array(
            'users' => $users,
            'news' => $news,
            'services' => $services,
            'faqs' => $faqs,
            'incidents' => $incidents,
            'appointments' => $appointments,
            'appointmentCategories' => $appointmentCategories,
            'organizations' => $organizations,
            'sectors' => $sectors,
            'activities' => $activities,
            'surveys' => $surveys,
            'complaints' => $complaints,
        ));
    }

    public function countrySelectionAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            //used in administration modal and top nav
            if ($this->params()->fromRoute('id'))
                $id = (int) $this->params()->fromRoute('id');
            else
                $id = (int) $this->params()->fromPost('id');

            $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                ->findOneBy(array('id' => $id));

            //save country in session
            $container = new Container('countrySettings');
            $container->countryId = $country->getId();
            $container->countryName = $country->getName();

            $result = new JsonModel(array(
                'success' => true,
                'countryId' => $country->getId(),
                'country' => $country->getName(),
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

}
