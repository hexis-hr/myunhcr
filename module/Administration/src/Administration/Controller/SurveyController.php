<?php

namespace Administration\Controller;

use Administration\Entity\SurveyODK;
use Administration\Form\Filter\SurveyODKFormFilter;
use Administration\Form\SurveyODKForm;
use Administration\Provider\ProvidesEntityManager;

use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\PhpEnvironment\Response;
use Zend\Paginator\Paginator;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class SurveyController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction () {

        $mSurvey = $this->getEntityManager()->getRepository('Administration\Entity\SurveyODK');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mSurvey->createQueryBuilder('SurveyODK')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);

        $page = (int)$this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array('data' => $paginator));

    }

    public function addAction () {

        $request = $this->getRequest();

        $surveyODK = new SurveyODK();
        $form = new SurveyODKForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\SurveyODK'));
        $form->bind($surveyODK);

        if ($request->isPost()) {

            $formFilter = new SurveyODKFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $surveyODK->setCountry($country);

                $this->getEntityManager()->persist($surveyODK);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('survey');
            }
        }

        return new ViewModel(array('form' => $form));

    }

    public function downloadSurveyAction () {

        $globalConfig = $this->serviceLocator->get('config');
        $id = (int)$this->params()->fromRoute('id');

        $file = $this->getEntityManager()->getRepository('Administration\Entity\File')
            ->findOneBy(array('id' => $id));

        $target_file = $globalConfig['fileDir'] . basename($file->getName());

        $response = new Response();
        $response->getHeaders()->addHeaders(array(
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $file->getName() . '"',
            'Pragma' => 'public',
            'Content-Length' => filesize($target_file),
        ));
        $response->setContent(file_get_contents($target_file));

        return $response;
    }

    public function manageActiveStatusAction (){

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int) $this->params()->fromRoute('id');
            $status = $this->params()->fromPost('status');
            $survey = $this->getEntityManager()->getRepository('Administration\Entity\SurveyODK')
                ->findOneBy(array('id' => $id));

            if ($status == 'true')
                $survey->setActive(1);
            else
                $survey->setActive(0);

            $this->getEntityManager()->persist($survey);
            $this->getEntityManager()->flush();

            $result = new JsonModel(array(
                'success' => true,
                'status' => $survey->getActive(),
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

    public function deleteAction () {

        $globalConfig = $this->serviceLocator->get('config');
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int) $this->params()->fromRoute('id');
            $survey = $this->getEntityManager()->getRepository('Administration\Entity\Survey')
                ->findOneBy(array('id' => $id));

            $surveyForm = $this->getEntityManager()->getRepository('Administration\Entity\Form')
                ->findOneBy(array('id' => $survey->getForm()->getId()));

            $file = $this->getEntityManager()->getRepository('Administration\Entity\File')
                ->findOneBy(array('id' => $survey->getForm()->getFile()->getId()));

            unlink($globalConfig['fileDir'] . $file->getName());
            $this->rrmdir($globalConfig['surveyFormDir'] . $survey->getForm()->getFormName());

            $this->getEntityManager()->remove($file);
            $this->getEntityManager()->flush();

            // cascade delete remove all form elements, fieldsets, forms and surveys
            $this->getEntityManager()->remove($surveyForm);
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

    public function xmlDeliverAction () {


        $globalConfig = $this->serviceLocator->get('config');

        $name = $this->params()->fromRoute('id');

        $targetFile = $globalConfig['fileDir'] . basename($name);

        $response = new Response();
        $response->getHeaders()->addHeaders(array(
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="' . $name . '"',
            'Pragma' => 'public',
            'Access-Control-Allow-Origin' => '*',
            'Content-Length' => filesize($targetFile),
        ));

        $response->setContent(file_get_contents($targetFile));

        return $response;
    }

    public function transformXmlAction () {

        $globalConfig = $this->serviceLocator->get('config');

        $name = $this->params()->fromRoute('id');
        $targetFile = $globalConfig['fileDir'] . basename($name);

        $response = new Response();
        $response->getHeaders()->addHeaders(array(
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="' . $name . '"',
            'Pragma' => 'public',
            'Access-Control-Allow-Origin' => '*',
            'Content-Length' => filesize($targetFile),
        ));

        $response->setContent(file_get_contents($targetFile));

        return $response;
    }

    private function rrmdir($dir) {
        if (!file_exists($dir))
            return true;

        if (!is_dir($dir))
            return unlink($dir);

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..')
                continue;

            if (!$this->rrmdir($dir . DIRECTORY_SEPARATOR . $item))
                return false;

        }

        return rmdir($dir);
    }
}
