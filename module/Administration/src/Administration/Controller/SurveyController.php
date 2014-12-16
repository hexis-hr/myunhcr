<?php

namespace Administration\Controller;

use Administration\Entity\File;
use Administration\Form\FileForm;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\PhpEnvironment\Response;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

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

        $globalConfig = $this->serviceLocator->get('config');
        $request = $this->getRequest();

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

                if (move_uploaded_file($surveyFile['tmp_name'], $target_file)) {
                    $this->flashMessenger()->addMessage('The file ' . basename($surveyFile['name']) . ' has been uploaded.');
                } else {
                    $this->flashMessenger()->addMessage('Sorry, there was an error uploading your file.');
                    return $this->redirect()->toRoute('translation');
                }

                $file->setName($surveyFile['name']);
                $file->setType('survey');
                $file->setMimeType($surveyFile['type']);
                $file->setSize($surveyFile['size']);

                $this->getEntityManager()->persist($file);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('survey');
            }
        }

        $addedSurveys = array();
        $files = $this->getEntityManager()->getRepository('Administration\Entity\File')->findBy(array('type' => 'survey'));
        foreach ($files as $f) {
            $addedSurveys[$f->getId()] = $f->getName();
        }

        return new ViewModel(array('form' => $form, 'surveys' => $addedSurveys));
    }

    public function downloadAction () {

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

}
