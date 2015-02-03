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
use Application\Form\ReportIncidentForm;
use Application\Form\SettingsForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\MimeType;
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
        $step = (int) $this->params()->fromQuery('step', 1);

        $this->layout()->setVariable('body_class', 'pg-reportIncident -step' . $step);

        $request = $this->getRequest();
        $form = new ReportIncidentForm($this->getEntityManager(), $this->getServiceLocator());
        $form->get('submit')->setAttribute('value', 'Next');

        //save form values in session
        $containerName = 'incidentFormData';
        $container = new Container($containerName);
        $form->populateValues($container->getArrayCopy());

        if (!isset($container->files)) {
            $container->files = array();
        }

        if ($request->isXmlHttpRequest() || $request->isPost()) {

            $postValues = $request->getPost();

            foreach ($postValues as $key => $value) {
                $container->{$key} = $value;
            }

            // special case for file upload via post
            $files = $request->getFiles();

            if ($files) {
                foreach ($files as $elementName => $file) {
                    if (isset($file['error']) && $file['error'] === 0) {
                        if ($fileName = $this->validateAndSaveFile($file, $elementName)) {
                            $container->files[] = $fileName;
                        } else {

                        }
                    } else {
                        foreach ($file as $f) {
                            if (isset($f['error']) && $f['error'] === 0) {
                                if ($fileName = $this->validateAndSaveFile($file, $elementName)) {
                                    $container->files[] = $fileName;
                                } else {

                                }
                            }
                        }
                    }
                }
            }

            $formFilter = new ReportIncidentFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($container->getArrayCopy());

            switch ($step) {
                case 2:
                    $form->setValidationGroup('anonymous', 'category', 'type');
                    break;
                case 3:
                    $form->setValidationGroup('country', 'address');
                    break;
                case 4:
                    $form->setValidationGroup('description');
                    break;
                case 5:
                    $form->setValidationGroup('image', 'audio');
                    break;
            }


            if (!$form->isValid()) {
                return new ViewModel(array(
                    'step' => --$step ?: 1,
                    'form' => $form,
                ));
            }

            if ($step >= 5) {
                // get data from session, populate form, validate and create incident

                $data = $container->getArrayCopy();

                $incident = new Incident();

                foreach ($data as $key => $value) {
                    if (method_exists($incident, 'set' . ucfirst($key))) {
                        $incident->{'set' . ucfirst($key)}($value);
                    } else if ($key == 'files') {
                        foreach ($value as $v) if (!empty($v)) {
                            $file = $this->getEntityManager()->getRepository('Administration\Entity\File')
                                ->findOneBy(array('name' => $v));
                            $incident->addFile($file);
                        }
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

        var_dump($request->getFiles()->toArray());


        return new JsonModel(array(
            'status' => 'success',
        ));

    }

    /**
     *
     * Save any file providing $fileDetails array from $_FILES or formatted uploaded file
     * Provide expected type to allow mime type filtering
     *
     * @param $fileDetails
     * @param $expectedType
     *
     * @throws \Exception if invalid expected type
     * @return boolean/id - return false on failure and unique_id on success
     */
    private function validateAndSaveFile ($fileDetails, $expectedType) {

        $allowedTypes = array('image', 'audio');

        if (!in_array($expectedType, $allowedTypes)) {
            throw new \Exception('Expected type *' . $expectedType . '* is not supported');
        }

        $mimeTypes = $this->getMimeTypes();

        $audioMimeTypes = array();

        foreach ($mimeTypes as $type) {
            if (strpos($type, 'audio') !== false) {
                $audioMimeTypes[] = $type;
            }
        }

        $imageValidator = new IsImage();
        $audioValidator = new MimeType($audioMimeTypes);

        if ($expectedType == 'image' && $imageValidator->isValid($fileDetails['tmp_name'])) {
            $outputName = uniqid('image', true);
        } else if ($expectedType == 'audio' && $audioValidator->isValid($fileDetails['name'])) {
            $outputName = uniqid('audio', true);
        } else {
            return false;
        }

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $outputMimeType = finfo_file($fileInfo, $fileDetails['tmp_name']);
        finfo_close($fileInfo);

        $globalConfig = $this->serviceLocator->get('config');

        $targetFile = $globalConfig['fileDir'] . basename($outputName);

        if (move_uploaded_file($fileDetails['tmp_name'], $targetFile)) {

            $file = new \Administration\Entity\File();

            $file->setName($outputName);
            $file->setOriginalName($fileDetails['name']);
            $file->setType($expectedType);
            $file->setMimeType($outputMimeType);
            $file->setSize($fileDetails['size']);

            $this->getEntityManager()->persist($file);
            $this->getEntityManager()->flush();

        } else {
            return false;
        }

        return $outputName;

    }

    private function getMimeTypes ()
    {
        return array(
            "323" => "text/h323",
            "acx" => "application/internet-property-stream",
            "ai" => "application/postscript",
            "aif" => "audio/x-aiff",
            "aifc" => "audio/x-aiff",
            "aiff" => "audio/x-aiff",
            "asf" => "video/x-ms-asf",
            "asr" => "video/x-ms-asf",
            "asx" => "video/x-ms-asf",
            "au" => "audio/basic",
            "avi" => "video/x-msvideo",
            "axs" => "application/olescript",
            "bas" => "text/plain",
            "bcpio" => "application/x-bcpio",
            "bin" => "application/octet-stream",
            "bmp" => "image/bmp",
            "c" => "text/plain",
            "cat" => "application/vnd.ms-pkiseccat",
            "cdf" => "application/x-cdf",
            "cer" => "application/x-x509-ca-cert",
            "class" => "application/octet-stream",
            "clp" => "application/x-msclip",
            "cmx" => "image/x-cmx",
            "cod" => "image/cis-cod",
            "cpio" => "application/x-cpio",
            "crd" => "application/x-mscardfile",
            "crl" => "application/pkix-crl",
            "crt" => "application/x-x509-ca-cert",
            "csh" => "application/x-csh",
            "css" => "text/css",
            "dcr" => "application/x-director",
            "der" => "application/x-x509-ca-cert",
            "dir" => "application/x-director",
            "dll" => "application/x-msdownload",
            "dms" => "application/octet-stream",
            "doc" => "application/msword",
            "dot" => "application/msword",
            "dvi" => "application/x-dvi",
            "dxr" => "application/x-director",
            "eps" => "application/postscript",
            "etx" => "text/x-setext",
            "evy" => "application/envoy",
            "exe" => "application/octet-stream",
            "fif" => "application/fractals",
            "flr" => "x-world/x-vrml",
            "gif" => "image/gif",
            "gtar" => "application/x-gtar",
            "gz" => "application/x-gzip",
            "h" => "text/plain",
            "hdf" => "application/x-hdf",
            "hlp" => "application/winhlp",
            "hqx" => "application/mac-binhex40",
            "hta" => "application/hta",
            "htc" => "text/x-component",
            "htm" => "text/html",
            "html" => "text/html",
            "htt" => "text/webviewhtml",
            "ico" => "image/x-icon",
            "ief" => "image/ief",
            "iii" => "application/x-iphone",
            "ins" => "application/x-internet-signup",
            "isp" => "application/x-internet-signup",
            "jfif" => "image/pipeg",
            "jpe" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "jpg" => "image/jpeg",
            "js" => "application/x-javascript",
            "latex" => "application/x-latex",
            "lha" => "application/octet-stream",
            "lsf" => "video/x-la-asf",
            "lsx" => "video/x-la-asf",
            "lzh" => "application/octet-stream",
            "m13" => "application/x-msmediaview",
            "m14" => "application/x-msmediaview",
            "m3u" => "audio/x-mpegurl",
            "man" => "application/x-troff-man",
            "mdb" => "application/x-msaccess",
            "me" => "application/x-troff-me",
            "mht" => "message/rfc822",
            "mhtml" => "message/rfc822",
            "mid" => "audio/mid",
            "mny" => "application/x-msmoney",
            "mov" => "video/quicktime",
            "movie" => "video/x-sgi-movie",
            "mp2" => "video/mpeg",
            "mp3" => "audio/mpeg",
            "mpa" => "video/mpeg",
            "mpe" => "video/mpeg",
            "mpeg" => "video/mpeg",
            "mpg" => "video/mpeg",
            "mpp" => "application/vnd.ms-project",
            "mpv2" => "video/mpeg",
            "ms" => "application/x-troff-ms",
            "mvb" => "application/x-msmediaview",
            "nws" => "message/rfc822",
            "oda" => "application/oda",
            "p10" => "application/pkcs10",
            "p12" => "application/x-pkcs12",
            "p7b" => "application/x-pkcs7-certificates",
            "p7c" => "application/x-pkcs7-mime",
            "p7m" => "application/x-pkcs7-mime",
            "p7r" => "application/x-pkcs7-certreqresp",
            "p7s" => "application/x-pkcs7-signature",
            "pbm" => "image/x-portable-bitmap",
            "pdf" => "application/pdf",
            "pfx" => "application/x-pkcs12",
            "pgm" => "image/x-portable-graymap",
            "pko" => "application/ynd.ms-pkipko",
            "pma" => "application/x-perfmon",
            "pmc" => "application/x-perfmon",
            "pml" => "application/x-perfmon",
            "pmr" => "application/x-perfmon",
            "pmw" => "application/x-perfmon",
            "pnm" => "image/x-portable-anymap",
            "pot" => "application/vnd.ms-powerpoint",
            "ppm" => "image/x-portable-pixmap",
            "pps" => "application/vnd.ms-powerpoint",
            "ppt" => "application/vnd.ms-powerpoint",
            "prf" => "application/pics-rules",
            "ps" => "application/postscript",
            "pub" => "application/x-mspublisher",
            "qt" => "video/quicktime",
            "ra" => "audio/x-pn-realaudio",
            "ram" => "audio/x-pn-realaudio",
            "ras" => "image/x-cmu-raster",
            "rgb" => "image/x-rgb",
            "rmi" => "audio/mid",
            "roff" => "application/x-troff",
            "rtf" => "application/rtf",
            "rtx" => "text/richtext",
            "scd" => "application/x-msschedule",
            "sct" => "text/scriptlet",
            "setpay" => "application/set-payment-initiation",
            "setreg" => "application/set-registration-initiation",
            "sh" => "application/x-sh",
            "shar" => "application/x-shar",
            "sit" => "application/x-stuffit",
            "snd" => "audio/basic",
            "spc" => "application/x-pkcs7-certificates",
            "spl" => "application/futuresplash",
            "src" => "application/x-wais-source",
            "sst" => "application/vnd.ms-pkicertstore",
            "stl" => "application/vnd.ms-pkistl",
            "stm" => "text/html",
            "svg" => "image/svg+xml",
            "sv4cpio" => "application/x-sv4cpio",
            "sv4crc" => "application/x-sv4crc",
            "t" => "application/x-troff",
            "tar" => "application/x-tar",
            "tcl" => "application/x-tcl",
            "tex" => "application/x-tex",
            "texi" => "application/x-texinfo",
            "texinfo" => "application/x-texinfo",
            "tgz" => "application/x-compressed",
            "tif" => "image/tiff",
            "tiff" => "image/tiff",
            "tr" => "application/x-troff",
            "trm" => "application/x-msterminal",
            "tsv" => "text/tab-separated-values",
            "txt" => "text/plain",
            "uls" => "text/iuls",
            "ustar" => "application/x-ustar",
            "vcf" => "text/x-vcard",
            "vrml" => "x-world/x-vrml",
            "wav" => "audio/x-wav",
            "wcm" => "application/vnd.ms-works",
            "wdb" => "application/vnd.ms-works",
            "wks" => "application/vnd.ms-works",
            "wmf" => "application/x-msmetafile",
            "wps" => "application/vnd.ms-works",
            "wri" => "application/x-mswrite",
            "wrl" => "x-world/x-vrml",
            "wrz" => "x-world/x-vrml",
            "xaf" => "x-world/x-vrml",
            "xbm" => "image/x-xbitmap",
            "xla" => "application/vnd.ms-excel",
            "xlc" => "application/vnd.ms-excel",
            "xlm" => "application/vnd.ms-excel",
            "xls" => "application/vnd.ms-excel",
            "xlt" => "application/vnd.ms-excel",
            "xlw" => "application/vnd.ms-excel",
            "xof" => "x-world/x-vrml",
            "xpm" => "image/x-xpixmap",
            "xwd" => "image/x-xwindowdump",
            "z" => "application/x-compress",
            "zip" => "application/zip"
        );
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

