<?php

namespace Administration\Controller;

use Administration\Entity\Faq;
use Administration\Entity\FaqCategory;
use Administration\Form\FaqCategoryForm;
use Administration\Form\FaqForm;

use Administration\Provider\ProvidesEntityManager;
use Doctrine\ORM\Query;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class FaqController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction () {

        $faq = $this->getEntityManager()->getRepository('Administration\Entity\Faq')->findAll();

        $dql = 'Select faqCat from Administration\Entity\Faq faq left join Administration\Entity\FaqCategory faqCat
         with faqCat.id = faq.category group by faq.category';
        $categories = $this->getEntityManager()->createQuery($dql)->getResult();

        return new ViewModel(array(
            'data' => $faq,
            'categories' => $categories,
        ));
    }

    public function addAction () {

        $request = $this->getRequest();
        $faq = new Faq();
        $form = new FaqForm($this->getEntityManager(), $this->getServiceLocator());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\Faq'));
        $form->bind($faq);

        if ($request->isPost()) {

            //todo
//            $formFilter = new UserFormFilter();
//            $form->setInputFilter($formFilter->getAddInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $faq->setCountry($country);
                $this->getEntityManager()->persist($faq);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addMessage('New FAQ successfully added.');
                return $this->redirect()->toRoute('faq');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editAction () {

        $request = $this->getRequest();
        $faqId = (int) $this->params()->fromRoute('id');
        $faq = $this->getEntityManager()->getRepository('Administration\Entity\Faq')
            ->findOneBy(array('id' => $faqId));

        $form = new FaqForm($this->getEntityManager(), $this->getServiceLocator());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\Faq'));
        $form->bind($faq);

        if ($request->isPost()) {

            //todo
//            $formFilter = new UserFormFilter();
//            $form->setInputFilter($formFilter->getAddInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $faq->setCountry($country);
                $this->getEntityManager()->persist($faq);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addMessage('New FAQ successfully added.');
                return $this->redirect()->toRoute('faq');
            }
        }

        return new ViewModel(array('form' => $form, 'faqId' =>$faqId));
    }

    public function deleteAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $faq = $this->getEntityManager()->getRepository('Administration\Entity\Faq')
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

    public function viewFaqCategoryAction () {

        $mFaqCategory = $this->getEntityManager()->getRepository('Administration\Entity\FaqCategory');

        $adapter = new DoctrineAdapter(new ORMPaginator($query = $mFaqCategory->createQueryBuilder('FaqCategory')));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        $page = (int) $this->params()->fromQuery('page');

        if ($page)
            $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'data' => $paginator,
        ));
    }

    public function addFaqCategoryAction () {

        $request = $this->getRequest();
        $faqCategory = new FaqCategory();

        $form = new FaqCategoryForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\FaqCategory'));
        $form->bind($faqCategory);

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $faqCategory->setCountry($country);
                $this->getEntityManager()->persist($faqCategory);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addMessage('New FAQ category successfully added.');
                return $this->redirect()->toRoute('faq/viewFaqCategory');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    public function editFaqCategoryAction () {

        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id');
        $faqCategory = $this->getEntityManager()->getRepository('Administration\Entity\FaqCategory')
            ->findOneBy(array('id' => $id));

        $form = new FaqCategoryForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\FaqCategory'));
        $form->bind($faqCategory);

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));
                $faqCategory->setCountry($country);
                $this->getEntityManager()->persist($faqCategory);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addMessage('FAQ category successfully edited.');
                return $this->redirect()->toRoute('faq/viewFaqCategory');
            }
        }

        return new ViewModel(array('form' => $form, 'id' => $id));
    }

    public function deleteFaqCategoryAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $id = (int)$this->params()->fromRoute('id');
            $faqCategory = $this->getEntityManager()->getRepository('Administration\Entity\FaqCategory')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($faqCategory);
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
