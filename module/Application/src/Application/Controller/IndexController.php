<?php

namespace Application\Controller;

use Administration\Entity\SurveyResult;
use Application\Form\ChooseSurveyForm;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Zend\Session\Container;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;


class IndexController extends AbstractActionController {

    protected $em;

    public function setEntityManager (EntityManager $em) {
        $this->em = $em;
    }

    public function getEntityManager () {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

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
        return new ViewModel();
    }

    public function bookAnAppointment2Action()
    {
        $this->layout()->setVariable('body_class', 'pg-bookAppoint -step2');
        return new ViewModel();
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

    public function chooseYourSurveyAction()
    {
        $this->layout()->setVariable('body_class', 'pg-chooseSurvey');

        $surveyForm = new ChooseSurveyForm($this->getEntityManager());

        return new ViewModel(array('form' => $surveyForm));
    }

    public function faqAction()
    {
        $this->layout()->setVariable('body_class', 'pg-faq -information');
        return new ViewModel();
    }

    public function faqMyunhcrAction()
    {
        $this->layout()->setVariable('body_class', 'pg-faq -myunhcr');
        return new ViewModel();
    }

    public function faqRefugeeAction()
    {
        $this->layout()->setVariable('body_class', 'pg-faq -feedback');
        return new ViewModel();
    }

    public function fileAComplaintAction()
    {
        $this->layout()->setVariable('body_class', 'pg-fileComplaint');
        return new ViewModel();
    }

    public function fileAComplaintFinishAction()
    {
        $this->layout()->setVariable('body_class', 'pg-fileComplaint -finish');
        return new ViewModel();
    }

    public function settingsAction()
    {
        $this->layout()->setVariable('body_class', 'pg-settings');
        return new ViewModel();
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
        return new ViewModel();
    }

    public function newsArticleAction()
    {
        $this->layout()->setVariable('body_class', 'pg-newsArticle');
        return new ViewModel();
    }

    public function reportAnIncidentAction()
    {
        $this->layout()->setVariable('body_class', 'pg-reportIncident');
        return new ViewModel();
    }

    public function reportAnIncident2Action()
    {
        $this->layout()->setVariable('body_class', 'pg-reportIncident -step2');
        return new ViewModel();
    }

    public function reportAnIncident3Action()
    {
        $this->layout()->setVariable('body_class', 'pg-reportIncident -step3');
        return new ViewModel();
    }

    public function reportAnIncident4Action()
    {
        $this->layout()->setVariable('body_class', 'pg-reportIncident -step4');
        return new ViewModel();
    }

    public function reportAnIncident5Action()
    {
        $this->layout()->setVariable('body_class', 'pg-reportIncident -step5');
        return new ViewModel();
    }

    public function takeASurveyAction()
    {
        $this->layout()->setVariable('body_class', 'pg-takeSurvey');
        return new ViewModel();
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

    public function deliverSurveyAction() {
         $this->layout()->setVariable('body_class', 'pg-updatePersonal');

         $request = $this->getRequest();

         if ($request->getPost('survey') != null)
             $surveyId = (int) $this->params()->fromPost('survey');
         else
             $surveyId = (int) $this->params()->fromRoute('survey');

         $survey = $this->getEntityManager()->getRepository('Administration\Entity\Survey')
             ->findOneBy(array('id' => $surveyId));

         $formName = 'Administration\Form\SurveyForm\\' . $survey->getForm()->getFormName()
             . '\\' . $survey->getForm()->getFormName();
         $form = new $formName();
         $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\Form'));

         $postSurvey = $this->params()->fromRoute('survey') != null ? true : false;

         if ($request->isPost() && $postSurvey) {

             foreach ($this->params()->fromPost() as $fieldset) {
                 foreach ($fieldset as $fieldName => $fieldValue) {
                     $surveyResult = new SurveyResult();
                     $surveyResult->setFieldName($fieldName);
                     $surveyResult->setFieldValue($fieldValue);
                     $surveyResult->setForm($survey->getForm());
                     $this->getEntityManager()->persist($surveyResult);
                     $this->getEntityManager()->flush();
                 }
             }

             return $this->redirect()->toRoute('app', array('action' => 'menu-page'));
         }

         return new ViewModel(array('form' => $form, 'surveyId' => $surveyId));
     }

}

