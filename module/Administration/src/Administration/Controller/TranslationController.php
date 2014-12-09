<?php

namespace Administration\Controller;

use Administration\Entity\File;
use Administration\Form\TranslationForm;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class TranslationController extends AbstractActionController {

    protected $em;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $locales = $this->serviceLocator->get('config')['translationLocales'];

        $addedTranslations = array();
        $files = $this->getEntityManager()->getRepository('Administration\Entity\File')->findAll();
        foreach ($files as $f) {
            $addedTranslations[substr($f->getName(), 0, 5)] = $f->getName();
        }

        $file = null;
        $locale = $request->getPost('locale');
        $fileName = $locale . '.po';
        $file = $this->getEntityManager()->getRepository('Administration\Entity\File')
            ->findOneBy(array('name' => $fileName));

        if (is_null($file))
            $file = new File();

        $form = new TranslationForm();
        $form->get('locale')->setValueOptions($locales);
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\File'));
        $form->bind($file);

        if ($request->isPost()) {

            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            if ($form->isValid()) {
                $translateFile = $post['translation-file'];

                //todo: change dir to language in application module when compiling is implemented
                $target_dir = dirname($_SERVER['DOCUMENT_ROOT']) . "/data/uploads/";
                $target_file = $target_dir . basename($fileName);

                if (move_uploaded_file($translateFile['tmp_name'], $target_file)) {
                    $this->flashMessenger()->addMessage('The file ' . basename($translateFile['name']). ' has been uploaded.
                     Its name is changed to ' . basename($fileName));
                } else {
                    $this->flashMessenger()->addMessage('Sorry, there was an error uploading your file.');
                    return $this->redirect()->toRoute('translation');
                }

                $file->setName($fileName);
                $file->setType('translation' . '_' . $locale);
                $file->setMimeType($translateFile['type']);
                $file->setSize($translateFile['size']);

                $this->getEntityManager()->persist($file);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('translation');
            }
        }

        return new ViewModel(array('form' => $form, 'translations' => $addedTranslations));
    }

}
