<?php

namespace Application\Controller;

use Administration\Entity\Appointment;
use Administration\Entity\Complaint;
use Administration\Entity\Incident;
use Administration\Entity\SurveyResult;
use Administration\Entity\UserSettings;
use Application\Form\BookAnAppointmentForm;
use Application\Form\CheckStatusForm;
use Application\Form\ComplaintForm;
use Administration\Provider\ProvidesEntityManager;
use Application\Form\ChooseSurveyForm;

use Application\Form\Filter\BookAnAppointmentFormFilter;
use Application\Form\Filter\ComplaintFormFilter;
use Application\Form\Filter\ReportIncidentFormFilter;
use Application\Form\ListOfOrganizationsForm;
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

        $formContainer = new Container('appointmentFormData');

        $request = $this->getRequest();
        $appointment = new Appointment();
        $form = new BookAnAppointmentForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\Appointment'));
        $form->bind($appointment);
        $form->setData($formContainer->getArrayCopy());

        if ($request->isPost()) {

            $postValues = $request->getPost();
            foreach ($postValues as $key => $value) {
                $formContainer->{$key} = $value;
            }

            $formFilter = new BookAnAppointmentFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($formContainer->getArrayCopy());
            $form->setValidationGroup('appointmentType');
            if ($form->isValid()) {
                return $this->redirect()->toRoute('app', array('action' => 'book-an-appointment2'));
            }

        }

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

            $formFilter = new BookAnAppointmentFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());
            $form->setValidationGroup('appointmentDate', 'appointmentTime');
            if ($form->isValid()) {
                $container = new Container('userSettings');
                $userSettings = $this->getEntityManager()->getRepository('Administration\Entity\UserSettings')
                    ->findOneBy(array('guid' => $container->id));
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $userSettings->getCountry()->getId()));
                $appointment->setCountry($country);

                $formContainer = new Container('appointmentFormData');
                if (isset($formContainer->appointmentType)) {
                    $category = $this->getEntityManager()->getRepository('Administration\Entity\AppointmentCategory')
                        ->findOneBy(array('id' => $formContainer->appointmentType));
                    $appointment->setCategory($category);
                }

                if (isset($formContainer->authentification))
                    $appointment->setAuthId($formContainer->authentification);

                $appointment->setDate(new \DateTime($this->params()->fromPost('appointmentDate')));
                $appointment->setTime(new \DateTime($this->params()->fromPost('appointmentTime')));

                $this->getEntityManager()->persist($appointment);
                $this->getEntityManager()->flush();

                $formContainer->getManager()->getStorage()->clear('appointmentFormData');

                return $this->redirect()->toRoute('app', array('action' => 'book-an-appointment3'));
            }
        }

        return new ViewModel(array(
            'form' => $form,
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

        $form = new CheckStatusForm($this->getEntityManager());

        return new ViewModel(array('form' => $form));
    }

    public function checkYourStatus2Action()
    {
        $this->layout()->setVariable('body_class', 'pg-checkStatus -step2');
        return new ViewModel();
    }

    public function checkYourStatus3Action()
    {
        $this->layout()->setVariable('body_class', 'pg-checkStatus -step3');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://www.unhcrmenadagdata.org/myUNHCRWebApi/api/RSDResults?CaseNumber='
                    . $request->getPost('authentification')  . '&DateOfBirth=' . $request->getPost('date')
                    . '&format=json&nojsoncallback=1'
            ));

            if (!($resp = curl_exec($curl)))
                return new ViewModel(array('status' => false));

            curl_close($curl);

            switch (json_decode($resp)->ResultId) {
                case 1: $class = '-positive'; $name = 'Accepted'; break;
                case 2: $class = '-unknown'; $name = 'Pending'; break;
                case 3: $class = '-negative'; $name = 'Rejected'; break;
                default: $class = ''; $name = '';
            }

            return new ViewModel(array(
                'status' => true,
                'class' => $class,
                'name' => $name,
                'message' => json_decode($resp)->ResultMessage
            ));

        } else {
            return $this->redirect()->toRoute('app', array('action' => 'check-your-status'));
        }


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

            $formFilter = new ComplaintFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
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

            //save user settings
            if (isset($container->id)) {
                $userSettings = $this->getEntityManager()->getRepository('Administration\Entity\UserSettings')
                    ->findOneBy(array('guid' => $container->id));
            }
            if (!$userSettings) {
                $userSettings = new UserSettings();
                $userSettings->setGuid(uniqid());
                $container->id = $userSettings->getGuid();
            }

            $userSettings->setNotifications($request->getPost('notifications'));

            $language = $this->getEntityManager()->getRepository('Administration\Entity\Translation')
                ->findOneBy(array('id' => $request->getPost('language')));
            $userSettings->setLanguage($language);
            $languageFile = $this->getEntityManager()->getRepository('Administration\Entity\File')
                ->findOneBy(array('id' => $language->getFile()->getId()));
            $container->offsetSet('userlocale', substr($languageFile->getName(), 0, 5));

            $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                ->findOneBy(array('id' => $request->getPost('country')));
            $userSettings->setCountry($country);

            $location = $this->getEntityManager()->getRepository('Administration\Entity\CountryLocation')
                ->findOneBy(array('id' => $request->getPost('location')));
            $userSettings->setLocation($location);

            $category = $this->getEntityManager()->getRepository('Administration\Entity\Settings')
                ->findOneBy(array('id' => $request->getPost('category')));
            $userSettings->setCategory($category);

            $this->getEntityManager()->persist($userSettings);
            $this->getEntityManager()->flush();

            return $this->redirect()->toUrl('/menu-page.html');
        }

        $settingsForm = new SettingsForm($this->getEntityManager());

        if (isset($container->id))
            $settings = $this->getEntityManager()->getRepository('Administration\Entity\UserSettings')
                ->findOneBy(array('guid' => $container->id));

        if (isset ($settings) && !$settings->getNotifications()) {
            $row = $settingsForm->get('notifications');
            $row->setAttributes(array(
                'checked' => null,
            ));
        }

        $row = $settingsForm->get('language');
        if (isset ($settings) && $settings->getLanguage()) {
            $row->setAttributes(array(
                'value' => $settings->getLanguage()->getId(),
                'selected' => true,
            ));
            $overlayValues ['language'] = $row->getValueOptions()[$settings->getLanguage()->getId()];
        } else {
            $overlayValues ['language'] = isset(array_values($row->getValueOptions())[0]) ?
                array_values($row->getValueOptions())[0] : '';
        }

        $row = $settingsForm->get('country');
        $row->setAttribute('data-ajax-href', $this->url()->fromRoute('showCountryLocation'));
        if (isset ($settings) && $settings->getCountry()) {
            $row->setAttributes(array(
                'value' => $settings->getCountry()->getId(),
                'selected' => true,
            ));
            $overlayValues ['country'] = $row->getValueOptions()[$settings->getCountry()->getId()];
        } else {
            $overlayValues ['country'] = isset(array_values($row->getValueOptions())[0]) ?
                array_values($row->getValueOptions())[0] : '';
        }

        $row = $settingsForm->get('location');
        if (isset ($settings) && $settings->getLocation()) {
            $row->setAttributes(array(
                'value' => $settings->getLocation()->getId(),
                'selected' => true,
            ));
            $overlayValues ['location'] = $row->getValueOptions()[$settings->getLocation()->getId()];
        } else {
            $overlayValues ['location'] = isset(array_values($row->getValueOptions())[0]) ?
                array_values($row->getValueOptions())[0] : '';
        }

        $row = $settingsForm->get('category');
        if (isset ($settings) && $settings->getCategory()) {
            $row->setAttributes(array(
                'value' => $settings->getCategory()->getId(),
                'selected' => true,
            ));
            $overlayValues ['category'] = $row->getValueOptions()[$settings->getCategory()->getId()];
        } else {
            $overlayValues ['category'] = isset(array_values($row->getValueOptions())[0]) ?
                array_values($row->getValueOptions())[0] : '';
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

    public function listOfSectorsAction()
    {
        $this->layout()->setVariable('body_class', 'pg-listOfSectors');

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $request->isPost()) {

            $keyword = $request->getPost('keyword');

            if ($keyword == 'all') {
                $sectors = $this->getEntityManager()->getRepository('Administration\Entity\ServiceSector')->findAll();
            } else {
                $sectors = $this->getEntityManager()->getRepository('Administration\Entity\ServiceSector')
                    ->createQueryBuilder('ss')
                    ->where('ss.sectorName LIKE :sector')
                    ->setParameter('sector', '%' . $keyword . '%')
                    ->getQuery()
                    ->getResult();
            }

            if (count($sectors) > 0) {
                $responseSectors = array();
                foreach ($sectors as $sector) {
                    $responseSectors[$sector->getId()] = array(
                            'sectorName' => $sector->getSectorName(),
                            'sectorAcronym' => $sector->getSectorAcronym());
                }

                return new JsonModel(array(
                    'status' => 'success',
                    'sectors' => $responseSectors,
                ));
            } else {
                return new JsonModel(array(
                    'status' => 'invalid',
                ));
            }
        }

        $sectors = $this->getEntityManager()->getRepository('Administration\Entity\ServiceSector')->findAll();

        return new ViewModel(array('sectors' => $sectors));
    }

    public function sectorDetailsAction()
    {
        $this->layout()->setVariable('body_class', 'pg-sectorDetailss');
        return new ViewModel();
    }

    public function listOfOrganizationsAction()
    {
        $this->layout()->setVariable('body_class', 'pg-listOfOrgs');

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $request->isPost()) {

            $keyword = $request->getPost('keyword');

            if ($keyword == 'all') {
                $orgs = $this->getEntityManager()->getRepository('Administration\Entity\ServiceOrganization')->findAll();
            } else {
                $orgs = $this->getEntityManager()->getRepository('Administration\Entity\ServiceOrganization')
                    ->createQueryBuilder('org')
                    ->where('org.organizationName LIKE :organization')
                    ->setParameter('organization', '%' . $keyword . '%')
                    ->getQuery()
                    ->getResult();
            }

            if (count($orgs) > 0) {
                $responseOrgs = array();
                foreach ($orgs as $org) {
                    $responseOrgs[$org->getId()] = array(
                        'orgName' => $org->getOrganizationName(),
                        'orgAcronym' => $org->getOrganizationAcronym(),
                        'orgUrl' => $this->url()->fromRoute('organizationDetails', array('id' => $org->getId())));
                }

                return new JsonModel(array(
                    'status' => 'success',
                    'orgs' => $responseOrgs,
                ));
            } else {
                return new JsonModel(array(
                    'status' => 'invalid',
                ));
            }
        }

        $organizations = $this->getEntityManager()->getRepository('Administration\Entity\ServiceOrganization')
            ->findAll();

        return new ViewModel(array(
            'organizations' => $organizations,
        ));
    }

    public function organizationDetailsAction()
    {
        $this->layout()->setVariable('body_class', 'pg-organizationDetailss');

        $id = $this->params()->fromRoute('id');
        $organization = $this->getEntityManager()->getRepository('Administration\Entity\ServiceOrganization')
            ->findOneBy(array('id' => $id));

        $relatedServices = $this->getEntityManager()->getRepository('Administration\Entity\Service')
            ->findBy(array('organization' => $id));

        return new ViewModel(array(
            'organization' => $organization,
            'services' => $relatedServices,
        ));
    }

    public function mapOfServiceAction()
    {
        $this->layout()->setVariable('body_class', 'pg-mapOfServices');
        return new ViewModel();
    }

    public function menuPageAction()
    {
        $this->layout()->setVariable('body_class', 'pg-homepage');

        return new ViewModel();
    }

    public function newsAndEventsAction()
    {
        $this->layout()->setVariable('body_class', 'pg-newsEvents');

        $mNews = $this->getEntityManager()->getRepository('Administration\Entity\News');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mNews->createQueryBuilder('News')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(2);

        $page = (int)$this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function newsAndEventsPartialAction () {

        $this->layout()->setVariable('body_class', 'pg-newsEvents');

        $offset = (int) $this->params()->fromRoute('offset');
        $dql = 'SELECT news FROM Administration\Entity\News news
            WHERE news.id > :offset';

        $query = $this->getEntityManager()->createQuery($dql)->setParameters(array(
            'offset' => $offset,
        ));

        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(2);

        return new ViewModel(array(
            'data' => $paginator,
            'kurac' => false,
        ));
    }

    public function newsArticleAction()
    {
        $this->layout()->setVariable('body_class', 'pg-newsArticle');

        $id = (int) $this->params()->fromRoute('id');

        $news = $this->getEntityManager()->getRepository('Administration\Entity\News')
            ->findOneBy(array('id' => $id));

        $next = true;
        $nextArticle = null;
        $lastId = $this->getEntityManager()
            ->createQuery("SELECT max(news.id) FROM Administration\\Entity\\News news")
            ->getResult();
        if ($lastId[0][1] != $id)
            while ($next) {
                $nextArticle = $this->getEntityManager()->getRepository('Administration\Entity\News')
                    ->findOneBy(array('id' => ++$id));
                if ($nextArticle != null)
                    $next = false;
            }
        return new ViewModel(array(
            'news' => $news,
            'nextArticle' => $nextArticle,
        ));
    }

    public function reportAnIncidentAction()
    {
        $step = (int) $this->params()->fromQuery('step', 1);

        $this->layout()->setVariable('body_class', 'pg-reportIncident -step' . $step);

        $request = $this->getRequest();
        $form = new ReportIncidentForm($this->getEntityManager(), $this->getServiceLocator());
        $form->get('submit')->setAttribute('value', 'Next');

        //save form values in session
        $container = new Container(ReportIncidentForm::$formName);
        $form->populateValues($container->getArrayCopy());

        if ($request->isXmlHttpRequest() || $request->isPost()) {

            $postValues = $request->getPost();

            foreach ($postValues as $key => $value) {
                $container->{$key} = $value;
            }

            $formFilter = new ReportIncidentFormFilter($this->getEntityManager(), $this->getServiceLocator(), $container);
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData(array_merge($container->getArrayCopy(), $request->getFiles()->toArray()));

            $validate = true;
            $validationGroups = array();
            switch ($step) {
                case 5:
                    $validationGroups[] = 'image';
                    $validationGroups[] = 'audio';
                case 4:
                    $validationGroups[] = 'description';
                case 3:
                    $validationGroups[] = 'country';
                    $validationGroups[] = 'address';
                case 2:
                    $validationGroups[] = 'anonymous';
                    $validationGroups[] = 'category';
                    $validationGroups[] = 'type';
                    break;
                case 1:
                    $validate = false;
                    break;
            }

            $form->setValidationGroup($validationGroups);

            // upon first ajax load of the page we need to skip validation
            if ($validate) {
                if (!$form->isValid()) {
                    return new ViewModel(array(
                        'step' => --$step ?: 1,
                        'form' => $form,
                    ));
                }
            }

            if ($step >= 5) {
                // get data from session, populate form, validate and create incident

                $incident = new Incident();
                $data = $container->getArrayCopy();

                foreach ($data as $key => $value) {
                    if (method_exists($incident, 'set' . ucfirst($key))) {
                        $incident->{'set' . ucfirst($key)}($value);
                    } else if (strpos($key, 'file:') !== false) {
                        $file = $this->getEntityManager()->getRepository('Administration\Entity\File')
                            ->findOneBy(array('name' => $value));
                        if (isset($file))
                            $incident->addFile($file);
                    }
                }
                $category = $this->getEntityManager()->getRepository('Administration\Entity\IncidentCategory')
                    ->findOneBy(array('id' => $data['category']));

                $incident->setCategory($category);

                $type = $this->getEntityManager()->getRepository('Administration\Entity\IncidentType')
                    ->findOneBy(array('id' => $data['type']));

                $incident->setType($type);

                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $data['country']));

                $incident->setCountry($country);

                $this->getEntityManager()->persist($incident);
                $this->getEntityManager()->flush();

                $container->getManager()->getStorage()->clear('incidentFormData');
            }

        }

        return new ViewModel(array(
            'step' => $step,
            'form' => $form,
        ));
    }

    public function uploadFileAction () {

        $request = $this->getRequest();

        $files = array();
        $translator = $this->getServiceLocator()->get('viewhelpermanager')->get('translate');
        foreach ($request->getFiles()->toArray() as $type => $file) {
            $filter = new ReportIncidentFormFilter($this->getEntityManager(), $this->getServiceLocator(),
                new Container(ReportIncidentForm::$formName));
            if ($outputName = $filter->validateFileType($file, $type)) {
                $files[] = array(
                    'name' => $file['name'],
                );
            } else {
                return new JsonModel(array(
                    'status' => 'error',
                    'message' => $translator(ReportIncidentFormFilter::getErrorMessage($type)),
                ));
            }
        }

        return new JsonModel(array(
            'status' => 'success',
            'files' => $files,
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
        $this->layout()->setVariable('body_class', 'pg-surveyQuest');
        return new ViewModel();

    }

    public function surveyFinishAction()
    {
        $this->layout()->setVariable('body_class', 'pg-surveyFinish');
        return new ViewModel();
    }

    public function getAjaxMarkersAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $nelat = $this->params()->fromPost('nelat');
            $nelng = $this->params()->fromPost('nelng');
            $swlat = $this->params()->fromPost('swlat');
            $swlng = $this->params()->fromPost('swlng');

            if ($nelat < $swlat) {
                $minLat = $nelat;
                $maxLat = $swlat;
            } else {
                $minLat = $swlat;
                $maxLat =  $nelat;
            }

            if ($nelng < $swlng) {
                $minLng = $nelng;
                $maxLng = $swlng;
            } else {
                $minLng = $swlng;
                $maxLng =  $nelng;
            }

            $dql = 'SELECT service FROM Administration\Entity\Service service
            LEFT JOIN Administration\Entity\ServiceActivity activity WITH service.activity = activity.id
            WHERE service.latitude BETWEEN :minLat AND :maxLat AND service.longitude BETWEEN :minLng AND :maxLng
            AND activity.activityStart <= :today AND activity.activityEnd >= :today';

            $today = new \DateTime('now');
            $query = $this->getEntityManager()->createQuery($dql)->setParameters(array(
                'minLat' => $minLat,
                'maxLat' => $maxLat,
                'minLng' => $minLng,
                'maxLng' => $maxLng,
                'today' => $today->format('Y-m-d'),
            ));

            $services = $query->getResult();

            $arr = array();
            foreach ($services as $service) {
                $arr[$service->getId()] = array($service->getServiceName(), $service->getLatitude(), $service->getLongitude(),
                    $service->getDescription(), $service->getServiceUrl(), $service->getActivity()->getActivityName(),
                    $service->getActivity()->getActivityCategory(), $service->getActivity()->getActivityStart(),
                    $service->getActivity()->getActivityEnd(), $service->getOrganization()->getOrganizationName(),
                    $service->getSector()->getSectorName());
            }

            $result = new JsonModel(array(
                'success' => true,
                'data' => $arr,
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

}

