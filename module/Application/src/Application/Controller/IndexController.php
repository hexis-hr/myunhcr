<?php

namespace Application\Controller;

use Administration\Entity\Appointment;
use Administration\Entity\Complaint;
use Administration\Entity\SurveyResult;
use Application\Form\BookAnAppointmentForm;
use Application\Form\ComplaintForm;
use Administration\Provider\ProvidesEntityManager;
use Application\Form\ChooseSurveyForm;

use Application\Form\ReportIncidentForm;
use Application\Form\SettingsForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Paginator\Paginator;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class IndexController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction()
    {
        $this->layout()->setVariable('body_class', 'pg-welcome');
        return new ViewModel();
    }

    public function styleguideAction()
    {
        $this->layout()->setVariable('body_class', 'pg-styleguide');
        return new ViewModel();
    }

    public function bookAnAppointmentAction()
    {
        $this->layout()->setVariable('body_class', 'pg-bookAppoint');

        $form = new BookAnAppointmentForm($this->getEntityManager());

        return new ViewModel(array(
            'form' => $form,
        ));
    }

    public function bookAnAppointment2Action()
    {
        $this->layout()->setVariable('body_class', 'pg-bookAppoint -step2');

        $request = $this->getRequest();
        $appointment = new Appointment();
        $form = new BookAnAppointmentForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\Appointment'));
        $form->bind($appointment);

        if ($request->isPost() && $this->params()->fromQuery('step') == 'done') {

            $container = new Container('userSettings');
            $userSettings = $this->getEntityManager()->getRepository('Administration\Entity\UserSettings')
                ->findOneBy(array('guid' => $container->id));
            $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                ->findOneBy(array('id' => $userSettings->getCountry()->getId()));
            $appointment->setCountry($country);

            $category = $this->getEntityManager()->getRepository('Administration\Entity\AppointmentCategory')
                ->findOneBy(array('id' => $this->params()->fromRoute('appointmentType')));
            $appointment->setCategory($category);

            $appointment->setAuthId($this->params()->fromRoute('authentification'));
            $appointment->setDate(new \DateTime($this->params()->fromPost('appointmentDate')));
            $appointment->setTime(new \DateTime($this->params()->fromPost('appointmentTime')));

            $this->getEntityManager()->persist($appointment);
            $this->getEntityManager()->flush();

            return $this->redirect()->toRoute('app', array('action' => 'book-an-appointment3'));
        }

        return new ViewModel(array(
            'form' => $form,
            'appointmentType' => $this->params()->fromPost('appointmentType'),
            'authentification' => $this->params()->fromPost('authentification'),
        ));

    }

    public function bookAnAppointment3Action()
    {
        $this->layout()->setVariable('body_class', 'pg-bookAppoint -step3');
        return new ViewModel();
    }

    public function checkYourStatusAction()
    {
        $this->layout()->setVariable('body_class', 'pg-checkStatus');
        return new ViewModel();
    }

    public function checkYourStatus2Action()
    {
        $this->layout()->setVariable('body_class', 'pg-checkStatus -step2');
        return new ViewModel();
    }

    public function checkYourStatus3Action()
    {
        $this->layout()->setVariable('body_class', 'pg-checkStatus -step3');
        return new ViewModel();
    }

    public function faqAction()
    {
        $this->layout()->setVariable('body_class', 'pg-faq -information');

        $query = $this->getEntityManager()->createQuery('
            SELECT faq
            FROM \Administration\Entity\Faq faq
            LEFT JOIN \Administration\Entity\FaqCategory fac WITH faq.category = fac.id
            WHERE fac.category = :category');
        $query->setParameter('category', 'Information');
        $faq = $query->getResult();

        return new ViewModel(array('faq' => $faq));
    }

    public function faqMyunhcrAction()
    {
        $this->layout()->setVariable('body_class', 'pg-faq -myunhcr');

        $query = $this->getEntityManager()->createQuery('
            SELECT faq
            FROM \Administration\Entity\Faq faq
            LEFT JOIN \Administration\Entity\FaqCategory fac WITH faq.category = fac.id
            WHERE fac.category = :category');
        $query->setParameter('category', 'MyUNHCR');
        $faq = $query->getResult();

        return new ViewModel(array('faq' => $faq));
    }

    public function faqRefugeeAction()
    {
        $this->layout()->setVariable('body_class', 'pg-faq -feedback');

        $query = $this->getEntityManager()->createQuery('
            SELECT faq
            FROM \Administration\Entity\Faq faq
            LEFT JOIN \Administration\Entity\FaqCategory fac WITH faq.category = fac.id
            WHERE fac.category = :category');
        $query->setParameter('category', 'Feedback');
        $faq = $query->getResult();

        return new ViewModel(array('faq' => $faq));
    }

    public function fileAComplaintAction()
    {
        $this->layout()->setVariable('body_class', 'pg-fileComplaint');

        $request = $this->getRequest();
        $complaint = new Complaint();
        $form = new ComplaintForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\Complaint'));
        $form->bind($complaint);

        if ($request->isPost()) {

            $form->setData($request->getPost());
            if ($form->isValid()) {

                $container = new Container('userSettings');
                $userSettings = $this->getEntityManager()->getRepository('Administration\Entity\UserSettings')
                    ->findOneBy(array('guid' => $container->id));
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $userSettings->getCountry()->getId()));

                $complaint->setContent($request->getPost('feedbackMessage'));
                $complaint->setCountry($country);
                $complaint->setDate(new \DateTime('now'));

                $this->getEntityManager()->persist($complaint);
                $this->getEntityManager()->flush();

                return $this->redirect()->toUrl('/file-a-complaint-finish.html');
            }
        }

        return new ViewModel(array(
            'form' => $form,
        ));

    }

    public function fileAComplaintFinishAction()
    {
        $this->layout()->setVariable('body_class', 'pg-fileComplaint -finish');
        return new ViewModel();
    }

    public function settingsAction()
    {
        $this->layout()->setVariable('body_class', 'pg-settings');

        $container = new Container('userSettings');
        $request = $this->getRequest();
        if ($request->isPost()) {
            //save user settings in session
            $container->notifications = $request->getPost('notifications');
            $container->language = $request->getPost('language');
            $container->country = $request->getPost('country');
            $container->location = $request->getPost('location');
            $container->category = $request->getPost('category');

            return $this->redirect()->toUrl('/menu-page.html');
        }

        $settingsForm = new SettingsForm($this->getEntityManager());

        if (isset($container->notifications)) {
            if (!$container->notifications) {
                $row = $settingsForm->get('notifications');
                $row->setAttributes(array(
                    'checked' => null,
                ));
            }
        }

        $row = $settingsForm->get('language');
        if (isset($container->language)) {
            $row->setAttributes(array(
                'value' => $container->language,
                'selected' => true,
            ));
            $overlayValues ['language'] = $row->getValueOptions()[$container->language];
        } else {
            $overlayValues ['language'] = array_values($row->getValueOptions())[0];
        }

        $row = $settingsForm->get('country');
        $row->setAttribute('data-ajax-href', $this->url()->fromRoute('showCountryLocation'));
        if (isset($container->country)) {
            $row->setAttributes(array(
                'value' => $container->country,
                'selected' => true,
            ));
            $overlayValues ['country'] = $row->getValueOptions()[$container->country];
        } else {
            $overlayValues ['country'] = array_values($row->getValueOptions())[0];
        }

        $row = $settingsForm->get('location');
        if (isset($container->location)) {
            $row->setAttributes(array(
                'value' => $container->location,
                'selected' => true,
            ));
            $overlayValues ['location'] = $row->getValueOptions()[$container->location];
        } else {
            $overlayValues ['location'] = array_values($row->getValueOptions())[0];
        }

        $row = $settingsForm->get('category');
        if (isset($container->category)) {
            $row->setAttributes(array(
                'value' => $container->category,
                'selected' => true,
            ));
            $overlayValues ['category'] = $row->getValueOptions()[$container->category];
        } else {
            $overlayValues ['category'] = array_values($row->getValueOptions())[0];
        }


        return new ViewModel(array('form' => $settingsForm, 'overlayValues' => $overlayValues));
    }
    public function showCountryLocationAction ()
    {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $countryId = (int)$this->params()->fromPost('countryId');
            $allLocations = $this->getEntityManager()->getRepository('Administration\Entity\CountryLocation')
                ->findBy(array('country' => $countryId));

            if ($allLocations) {
                $locations = array();
                foreach ($allLocations as $location) {
                    $locations[$location->getId()] = $location->getName();
                }
                asort($locations);

                $result = new JsonModel(array(
                    'success' => true,
                    'countryId' => $countryId,
                    'locations' => $locations,
                ));
            } else {
                $result = new JsonModel(array(
                    'success' => true,
                    'countryId' => $countryId,
                ));
            }

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

    public function listBySectorAction()
    {
        $this->layout()->setVariable('body_class', 'pg-listBySector');
        return new ViewModel();
    }

    public function listOfOrganizationsAction()
    {
        $this->layout()->setVariable('body_class', 'pg-listByOrg');
        return new ViewModel();
    }

    public function mapOfServiceAction()
    {
        $this->layout()->setVariable('body_class', 'pg-mapOfServices');
        return new ViewModel();
    }

    public function menuPageAction()
    {
        $this->layout()->setVariable('body_class', 'pg-homepage');

        $request = $this->getRequest();

        $sessionContainer = new Container('locale');
        if ($request->isPost()) {
            switch ($request->getPost("language")) {
                case 'de_DE':
                    $userlocale = 'de_DE';
                    break;
                case 'it_IT':
                    $userlocale = 'it_IT';
                    break;
                case 'en_US':
                    $userlocale = 'en_US';
                    break;
                case 'ar_JO':
                    $userlocale = 'ar_JO';
                    break;

                default :
                    $userlocale = 'en_US';
            }

            $sessionContainer->offsetSet('userlocale', $userlocale);
            return $this->redirect()->toUrl('/menu-page.html');
        }

        return new ViewModel();
    }

    public function newsAndEventsAction()
    {
        $this->layout()->setVariable('body_class', 'pg-newsEvents');

        $mNews = $this->getEntityManager()->getRepository('Administration\Entity\News');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mNews->createQueryBuilder('News')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(1);

        $page = (int)$this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function newsArticleAction()
    {
        $this->layout()->setVariable('body_class', 'pg-newsArticle');

        $id = (int) $this->params()->fromRoute('id');

        $news = $this->getEntityManager()->getRepository('Administration\Entity\News')
            ->findOneBy(array('id' => $id));

        return new ViewModel(array('news' => $news));
    }

    public function reportAnIncidentAction()
    {
        $step = (int) $this->params()->fromRoute('step', 1);

        $this->layout()->setVariable('body_class', 'pg-reportIncident -step' . $step);

        $request = $this->getRequest();
        $form = new ReportIncidentForm($this->getEntityManager(), $this->getServiceLocator());

        //save form values in session
        $container = new Container('incidentForm');

        if ($request->isPost()) {

            $step++;
            $postValues = $request->getPost();

            foreach ($postValues as $key => $value) {
                $container->{$key} = $value;
            }

            if ($step >= 5) {
                // get data from session, populate form, validate and create incident
                $container->getManager()->getStorage()->clear('incidentForm');
            }

        }

        return new ViewModel(array(
            'step' => $step,
            'form' => $form,
        ));
    }

    public function takeASurveyAction()
    {
        $this->layout()->setVariable('body_class', 'pg-takeSurvey');

        $request = $this->getRequest();

        $form = new ChooseSurveyForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\SurveyODK'));

        if ($request->isPost()) {

            $surveyResults = new SurveyResult();

            $surveyId = (int) $this->params()->fromPost('survey');
            $authId = $this->params()->fromPost('authentification');
            $birthDate = $this->params()->fromPost('date');

            $surveyResults->setAuthId($authId);
            $surveyResults->setBirthDate($birthDate);

            $container = new Container('userSettings');
            $userSettings = $this->getEntityManager()->getRepository('Administration\Entity\UserSettings')
                ->findOneBy(array('guid' => $container->id));
            $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                ->findOneBy(array('id' => $userSettings->getCountry()->getId()));
            $surveyResults->setCountry($country);

            $survey = $this->getEntityManager()->getRepository('Administration\Entity\SurveyODK')
                ->findOneBy(array('id' => $surveyId));
            $surveyResults->setSurvey($survey);

            $this->getEntityManager()->persist($surveyResults);
            $this->getEntityManager()->flush();

            return $this->redirect()->toUrl($survey->getUrl());
        }

        return new ViewModel(array('form' => $form));
    }

    public function updatePersonalDataAction()
    {
        $this->layout()->setVariable('body_class', 'pg-updatePersonal');
        return new ViewModel();
    }

    public function updatePersonalData2Action()
    {
        $this->layout()->setVariable('body_class', 'pg-updatePersonal -step2');
        return new ViewModel();
    }

    public function updatePersonalData3Action()
    {
        $this->layout()->setVariable('body_class', 'pg-updatePersonal -step3');
        return new ViewModel();
    }

    public function updatePersonalData4Action()
    {
        $this->layout()->setVariable('body_class', 'pg-updatePersonal -step4');
        return new ViewModel();
    }

    public function surveyQuestionsAction()
    {
//        $this->layout()->setVariable('body_class', 'pg-surveyQuest');
//
//        $request = $this->getRequest();
//
//        $surveyId = (int) $this->params()->fromPost('survey');
//        $authId = $this->params()->fromPost('authentification');
//        $birthDate = $this->params()->fromPost('date');
//
//        $postSurvey = $this->params()->fromRoute('survey') != null ? true : false;
//
//        if ($request->isPost() && $postSurvey) {
//
//            foreach ($this->params()->fromPost() as $fieldset) {
//                foreach ($fieldset as $fieldName => $fieldValue) {
//                    $surveyResult = new SurveyResult();
//                    $surveyResult->setFieldName($fieldName);
//                    $surveyResult->setFieldValue($fieldValue);
//                    $surveyResult->setBirthDate($birthDate);
//                    $surveyResult->setAuthId($authId);
//                    $surveyResult->setForm($survey->getForm());
//                    $this->getEntityManager()->persist($surveyResult);
//                    $this->getEntityManager()->flush();
//                }
//            }
//
//            return $this->redirect()->toRoute('app', array('action' => 'menu-page'));
//        }
//
//        return new ViewModel(array('form' => $form, 'surveyId' => $surveyId, 'authId' => $authId, 'birthDate' => $birthDate));

    }

    public function surveyFinishAction()
    {
        $this->layout()->setVariable('body_class', 'pg-surveyFinish');
        return new ViewModel();
    }

}

