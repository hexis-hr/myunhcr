<?php

namespace Administration\Controller;

use Administration\Entity\Country;
use Administration\Entity\CountryLocation;
use Administration\Entity\News;
use Administration\Entity\Settings;
use Administration\Form\CountryLocationForm;
use Administration\Form\CountrySettingsForm;
use Administration\Form\NewsForm;
use Administration\Form\UserCategoryForm;
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

        $activeCountries = $this->getEntityManager()->getRepository('Administration\Entity\Country')
            ->findBy(array(), array('id' => 'DESC'));

        $form2 =  new UserCategoryForm();

        $userCategories = $this->getEntityManager()->getRepository('Administration\Entity\Settings')
            ->findBy(array('settingsKey' => 'userCategory'), array('id' => 'DESC'));

        $form3 =  new CountryLocationForm();

        $countryLocations = null;
        if (isset($_SESSION['countrySettings']['countryId']))
            $countryLocations = $this->getEntityManager()->getRepository('Administration\Entity\CountryLocation')
                ->findBy(array('country' => $_SESSION['countrySettings']['countryId']));

        return new ViewModel(array(
            'form' => $form,
            'activeCountries' => $activeCountries,
            'form2' => $form2,
            'userCategories' => $userCategories,
            'form3' => $form3,
            'countryLocations' => $countryLocations,
        ));

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

    public function addUserCategoryAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $userCategory = $this->params()->fromPost('userCategory');

            $settings = new Settings();
            $settings->setSettingsKey('userCategory');
            $settings->setSettingsValue($userCategory);

            $this->getEntityManager()->persist($settings);
            $this->getEntityManager()->flush();

            $result = new JsonModel(array(
                'success' => true,
                'userCategory' => $userCategory,
                'userCategoryId' => $settings->getId(),
                'deleteUrl' => $this->url()->fromRoute('settings/deleteUserCategory', array('id' => $settings->getId())),
                'editUrl' => $this->url()->fromRoute('settings/editUserCategory', array('id' => $settings->getId())),
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

    public function editUserCategoryAction () {

        $request = $this->getRequest();

        $userCategoryId = (int) $this->params()->fromRoute('id');
        $settings = $this->getEntityManager()->getRepository('Administration\Entity\Settings')
            ->findOneBy(array('id' => $userCategoryId));

        $form = new UserCategoryForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\Settings'));

        if ($request->isPost()) {

            //todo
            //$formFilter = new UserFormFilter();
            //$form->setInputFilter($formFilter->getAddInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $settings->setSettingsValue($request->getPost('userCategory'));
                $this->getEntityManager()->persist($settings);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addMessage('User category settings successfully edited.');
                return $this->redirect()->toRoute('settings');
            }
        }

        $form->populateValues(array(
            'userCategory' => $settings->getSettingsValue(),
        ));

        return new ViewModel(array('form' => $form, 'userCategoryId' => $userCategoryId));
    }

    public function deleteUserCategoryAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $id = (int)$this->params()->fromRoute('id');
            $settings = $this->getEntityManager()->getRepository('Administration\Entity\Settings')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($settings);
            $this->getEntityManager()->flush();

            $result = new JsonModel(array(
                'success' => true,
                'userCategoryId' => $id,
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

    public function addCountryLocationAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $location = $this->params()->fromPost('countryLocation');

            $countryLocation = new CountryLocation();
            $countryLocation->setName($location);

            $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));

            $countryLocation->setCountry($country);

            $this->getEntityManager()->persist($countryLocation);
            $this->getEntityManager()->flush();

            $result = new JsonModel(array(
                'success' => true,
                'countryLocation' => $location,
                'countryLocationId' => $countryLocation->getId(),
                'deleteUrl' => $this->url()->fromRoute('settings/deleteCountryLocation', array('id' => $countryLocation->getId())),
                'editUrl' => $this->url()->fromRoute('settings/editCountryLocation', array('id' => $countryLocation->getId())),
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

    public function editCountryLocationAction () {

        $request = $this->getRequest();

        $countryLocationId = (int) $this->params()->fromRoute('id');
        $countryLocation = $this->getEntityManager()->getRepository('Administration\Entity\CountryLocation')
            ->findOneBy(array('id' => $countryLocationId));

        $form = new CountryLocationForm();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\CountryLocation'));

        if ($request->isPost()) {

            //todo
            //$formFilter = new UserFormFilter();
            //$form->setInputFilter($formFilter->getAddInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $countryLocation->setName($request->getPost('locationName'));
                $this->getEntityManager()->persist($countryLocation);
                $this->getEntityManager()->flush();

                $this->flashMessenger()->addMessage('Country location successfully edited.');
                return $this->redirect()->toRoute('settings');
            }
        }

        $form->populateValues(array(
            'countryLocation' => $countryLocation->getName(),
        ));

        return new ViewModel(array('form' => $form, 'countryLocationId' => $countryLocationId));
    }

    public function deleteCountryLocationAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            $id = (int) $this->params()->fromRoute('id');
            $countryLocation = $this->getEntityManager()->getRepository('Administration\Entity\CountryLocation')
                ->findOneBy(array('id' => $id));

            $this->getEntityManager()->remove($countryLocation);
            $this->getEntityManager()->flush();

            $result = new JsonModel(array(
                'success' => true,
                'countryLocationId' => $id,
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

}
