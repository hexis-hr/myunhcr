<?php

namespace Administration\Controller;

use Administration\Entity\News;
use Administration\Form\NewsForm;

use Administration\Provider\ProvidesEntityManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class NewsController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction () {

        $mNews = $this->getEntityManager()->getRepository('Administration\Entity\News');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mNews->createQueryBuilder('News')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        $page = (int)$this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function addAction () {

        $request = $this->getRequest();

        $news = new News();
        $form = new NewsForm($this->getEntityManager(), $this->getServiceLocator());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\News'));
        $form->bind($news);

        if ($request->isPost()) {

            //todo
//            $formFilter = new UserFormFilter();
//            $form->setInputFilter($formFilter->getAddInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $this->getEntityManager()->persist($news);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addMessage('New news successfully added.');
                return $this->redirect()->toRoute('news');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editAction () {

        $request = $this->getRequest();
        $newsId = (int) $this->params()->fromRoute('id');
        $news = $this->getEntityManager()->getRepository('Administration\Entity\News')
            ->findOneBy(array('id' => $newsId));

        $form = new NewsForm($this->getEntityManager(), $this->getServiceLocator());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\News'));
        $form->bind($news);

        if ($request->isPost()) {

            //todo
//            $formFilter = new UserFormFilter();
//            $form->setInputFilter($formFilter->getAddInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $this->getEntityManager()->persist($news);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addMessage('New news successfully added.');
                return $this->redirect()->toRoute('news');
            }
        }

        return new ViewModel(array('form' => $form, 'newsId' =>$newsId));
    }

    public function deleteAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $faq = $this->getEntityManager()->getRepository('Administration\Entity\News')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($faq);
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

}
