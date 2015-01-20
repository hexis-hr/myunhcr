<?php

namespace Administration\Controller;

use Administration\Entity\Country;
use Administration\Entity\News;
use Administration\Form\CountrySettingsForm;
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

class SettingsController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction () {

        $form = new CountrySettingsForm($this->getEntityManager(), $this->getServiceLocator());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\Country'));

        $activeCountries = $this->getEntityManager()->getRepository('Administration\Entity\Country')
            ->findBy(array(), array('id' => 'DESC'));

        return new ViewModel(array('form' => $form, 'activeCountries' => $activeCountries));

    }

    public function activateCountryAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $id = (int) $this->params()->fromPost('countryId');
            $mCountry = $this->getEntityManager()->getRepository('Administration\Entity\CodeCountries')
                ->findOneBy(array('id' => $id));

            $country = new Country();
            $country->setCountryId($mCountry->getId());
            $country->setName($mCountry->getName());
            $this->getEntityManager()->persist($country);
            $this->getEntityManager()->flush();

            $result = new JsonModel(array(
                'success' => true,
                'countryName' => $country->getName(),
                'countryId' => $country->getId(),
                'deleteUrl' => $this->url()->fromRoute('settings/deleteActiveCountry', array('id' => $country->getId())),
                'editUrl' => $this->url()->fromRoute('settings/editActiveCountry', array('id' => $country->getId())),
                'countrySelectionUrl' => $this->url()->fromRoute('countrySelection', array('id' => $country->getId())),
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

    public function editActiveCountryAction () {

        $countryId = (int) $this->params()->fromRoute('id');
        $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
            ->findOneBy(array('id' => $countryId));

        $form = new CountrySettingsForm($this->getEntityManager(), $this->getServiceLocator());

        return new ViewModel(array('form' => $form, 'country' => $country, 'languages' => $country->getLanguages()));
    }

    public function editCountryLanguageAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $id = (int) $this->params()->fromRoute('id');
            $language = $this->params()->fromPost('language');

            $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                ->findOneBy(array('id' => $id));

            $country->setLanguages($language);
            $this->getEntityManager()->persist($country);
            $this->getEntityManager()->flush();

            $result = new JsonModel(array(
                'success' => true,
                'language' => $language,
                'countryId' => $id,
                'deleteUrl' => $this->url()->fromRoute('settings/deleteActiveLanguage',
                        array('id' => $id, 'language' => $language)),
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }


    public function deleteActiveCountryAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $id = (int)$this->params()->fromRoute('id');
            $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                ->findOneBy(array('id' => $id));

            $name = $country->getName();
            $this->getEntityManager()->remove($country);
            $this->getEntityManager()->flush();

            $result = new JsonModel(array(
                'success' => true,
                'countryId' => $id,
                'countryName' => $name,
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

    public function deleteActiveLanguageAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $id = (int)$this->params()->fromRoute('id');
            $language = $this->params()->fromRoute('language');

            $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                ->findOneBy(array('id' => $id));

            $country->removeLanguage($language);
            $this->getEntityManager()->persist($country);
            $this->getEntityManager()->flush();

            $result = new JsonModel(array(
                'success' => true,
                'countryId' => $id,
                'language' => $language,
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

}
