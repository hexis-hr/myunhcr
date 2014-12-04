<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Zend\Session\Container;


class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $this->layout()->setVariable('body_class', 'welcome');
        return new ViewModel();
    }

    public function styleguideAction()
    {
        $this->layout()->setVariable('body_class', 'styleguide');
        return new ViewModel();
    }

    public function bookAnAppointmentAction()
    {
        return new ViewModel();
    }

    public function bookAnAppointment2Action()
    {
        return new ViewModel();
    }

    public function bookAnAppointment3Action()
    {
        return new ViewModel();
    }

    public function bookAnAppointment4Action()
    {
        return new ViewModel();
    }

    public function checkYourStatusAction()
    {
        return new ViewModel();
    }

    public function checkYourStatus2Action()
    {
        return new ViewModel();
    }

    public function checkYourStatus3Action()
    {
        return new ViewModel();
    }

    public function chooseYourSurveyAction()
    {
        return new ViewModel();
    }

    public function faqAction()
    {
        return new ViewModel();
    }

    public function faqMyunhcrAction()
    {
        return new ViewModel();
    }

    public function faqRefugeeAction()
    {
        return new ViewModel();
    }

    public function fileAComplaintAction()
    {
        return new ViewModel();
    }

    public function settingsAction()
    {
        $this->layout()->setVariable('body_class', 'settings');
        return new ViewModel();
    }

    public function informationServicesMenuAction()
    {
        return new ViewModel();
    }

    public function listBySectorAction()
    {
        return new ViewModel();
    }

    public function listOfOrganizationsAction()
    {
        return new ViewModel();
    }

    public function mapOfServiceAction()
    {
        return new ViewModel();
    }

    public function menuPageAction()
    {
        $this->layout()->setVariable('body_class', 'homepage');

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

    public function myUNHCRServicesMenuAction()
    {
        return new ViewModel();
    }

    public function newsAndEventsAction()
    {
        return new ViewModel();
    }

    public function refugeeFeedbackAction()
    {
        return new ViewModel();
    }

    public function reportAnIncidentAction()
    {
        return new ViewModel();
    }

    public function reportAnIncident2Action()
    {
        return new ViewModel();
    }

    public function reportAnIncident3Action()
    {
        return new ViewModel();
    }

    public function reportAnIncident4Action()
    {
        return new ViewModel();
    }

    public function reportAnIncident5Action()
    {
        return new ViewModel();
    }

    public function takeASurveyAction()
    {
        return new ViewModel();
    }

    public function updatePersonalDataAction()
    {
        return new ViewModel();
    }

    public function updatePersonalData2Action()
    {
        return new ViewModel();
    }

    public function updatePersonalData3Action()
    {
        return new ViewModel();
    }

    public function updatePersonalData4Action()
    {
        return new ViewModel();
    }

    public function whatIsYourGenderAction()
    {
        return new ViewModel();
    }


}

