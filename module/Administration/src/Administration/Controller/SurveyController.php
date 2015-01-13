<?php

namespace Administration\Controller;

use Administration\Entity\File;
use Administration\Form\FileForm;
use ODKParser\ODKParser;

use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\PhpEnvironment\Response;
use Zend\Paginator\Paginator;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class SurveyController extends AbstractActionController {

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

    public function indexAction () {

        $mSurvey = $this->getEntityManager()->getRepository('Administration\Entity\Survey');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mSurvey->createQueryBuilder('Survey')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);

        $page = (int)$this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        $addedSurveys = array();
        $files = $this->getEntityManager()->getRepository('Administration\Entity\File')->findBy(array('type' => 'survey'));
        foreach ($files as $f) {
            $addedSurveys[$f->getId()] = $f->getName();
        }

        return new ViewModel(array('data' => $paginator, 'surveys' => $addedSurveys));
    }

    public function addAction () {

        $globalConfig = $this->serviceLocator->get('config');
        $request = $this->getRequest();

        $odk = new ODKParser($this->getServiceLocator());
        $file = new File();
        $form = new FileForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\File'));
        $form->bind($file);

        if ($request->isPost()) {

            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            if ($form->isValid()) {

                $surveyFile = $post['file'];
                $target_file = $globalConfig['fileDir'] . basename($surveyFile['name']);

                $ext = pathinfo($surveyFile['name'], PATHINFO_EXTENSION);

                try {
                    if ($ext == "xml") {
                        if (move_uploaded_file($surveyFile['tmp_name'], $target_file)) {
                            $this->flashMessenger()->addMessage('The file ' . basename($surveyFile['name']) . ' has been uploaded.');
                        } else
                            throw new \Exception('Sorry, there was an error uploading your file.');

                        $file->setName($surveyFile['name']);
                        $file->setType('survey');
                        $file->setMimeType($surveyFile['type']);
                        $file->setSize($surveyFile['size']);

                        $this->getEntityManager()->persist($file);
                        $this->getEntityManager()->flush();

                        $odk->xmlToForm($target_file, $file->getId());

                    } else
                        throw new \Exception('File must be in .xml format');

                } catch (\Exception $e) {
                    $this->flashMessenger()->addMessage($e->getMessage());
                    return $this->redirect()->toRoute('survey');
                }

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
            $survey = $this->getEntityManager()->getRepository('Administration\Entity\Survey')
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
