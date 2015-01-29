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
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class CompliantController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction () {

        $mCompliant = $this->getEntityManager()->getRepository('Administration\Entity\Compliant');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mCompliant->createQueryBuilder('Compliant')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        $page = (int)$this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function previewCompliantAction () {

        $request = $this->getRequest();

        $id = (int)$this->params()->fromRoute('id');

        $compliant = $this->getEntityManager()->getRepository('Administration\Entity\Compliant')
            ->findOneBy(array('id' => $id));

        return new ViewModel(array(
            'compliant' => $compliant,
        ));
    }

    public function deleteAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $id = (int)$this->params()->fromRoute('id');

            $compliant = $this->getEntityManager()->getRepository('Administration\Entity\Compliant')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($compliant);
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
