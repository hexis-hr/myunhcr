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
        $file = new File();

        $form = new TranslationForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\File'));
        $form->bind($file);

        if ($request->isPost()) {

            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            if ($form->isValid()) {

                $this->getEntityManager()->persist($file);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('translation');
            }
        }

        return new ViewModel(array('form' => $form));
    }

}
