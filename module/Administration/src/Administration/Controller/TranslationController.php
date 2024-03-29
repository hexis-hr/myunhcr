<?php

namespace Administration\Controller;

use Administration\Entity\File;
use Administration\Entity\Translation;
use Administration\Form\Filter\TranslationFormFilter;
use Administration\Form\TranslationForm;
use Administration\Provider\ProvidesEntityManager;

use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\PhpEnvironment\Response;

use Doctrine\ORM\Query;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class TranslationController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction () {

        $addedTranslations = array();
        $files = $this->getEntityManager()->getRepository('Administration\Entity\File')->findBy(array('type' => 'translation'));
        foreach ($files as $f) {
            $addedTranslations[$f->getId()] = $f->getName();
        }

        return new ViewModel(array('translations' => $addedTranslations));
    }

    public function addAction (){

        $request = $this->getRequest();
        $globalConfig = $this->serviceLocator->get('config');

        $file = null;
        $locale = $request->getPost('locale');
        $fileName = $locale . '.po';
        $file = $this->getEntityManager()->getRepository('Administration\Entity\File')
            ->findOneBy(array('name' => $fileName));

        if (is_null($file))
            $file = new File();

        $form = new TranslationForm($this->getEntityManager());
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager(), 'Administration\Entity\File'));
        $form->bind($file);

        if ($request->isPost()) {

            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $formFilter = new TranslationFormFilter();
            $form->setInputFilter($formFilter->getInputFilter());
            $form->setData($post);

            if ($form->isValid()) {

                $translateFile = $post['translationFile'];
                $target_file = $globalConfig['languageDir'] . basename($fileName);
                $newMoFile = $globalConfig['languageDir'] . basename($locale . '.mo');

                if (move_uploaded_file($translateFile['tmp_name'], $target_file)) {
                    exec('msgfmt -cv -o ' . $newMoFile . ' ' . $target_file);
                    $this->flashMessenger()->addSuccessMessage('The file ' . basename($translateFile['name']) . ' has been uploaded.
                     Its name is changed to ' . basename($fileName));
                } else {
                    $this->flashMessenger()->addMessage('Sorry, there was an error uploading your file.');
                    return $this->redirect()->toRoute('translation');
                }

                $file->setName($fileName);
                $file->setType('translation');
                $file->setMimeType($translateFile['type']);
                $file->setSize($translateFile['size']);

                $this->getEntityManager()->persist($file);
                $this->getEntityManager()->flush();

                $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                    ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));

                $translation = new Translation();
                $translation->setName($post['translationName']);
                $translation->setCountry($country);
                $translation->setFile($file);

                $this->getEntityManager()->persist($translation);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('translation');
            }
        }

        return new ViewModel(array('form' => $form));
    }


    public function downloadTranslationAction () {

        $globalConfig = $this->serviceLocator->get('config');
        $id = (int)$this->params()->fromRoute('id');

        $file = $this->getEntityManager()->getRepository('Administration\Entity\File')
            ->findOneBy(array('id' => $id));

        $target_po = $globalConfig['languageDir'] . basename($file->getName());
        $translationName = substr($file->getName(), 0, -3);

        $fileNameMo = $translationName . '.mo';
        $target_mo = $globalConfig['languageDir'] . basename($translationName . '.mo');

        $zip = new \ZipArchive();

        $zipName = $translationName . '.zip';
        $zipFile = dirname($_SERVER['DOCUMENT_ROOT']) . '/data/uploads/' . $zipName;

        if ($zip->open($zipFile, \ZipArchive::CREATE) !== TRUE) {
            $this->flashMessenger()->addMessage("Cannot create <$zipName>\n");
            return $this->redirect()->toRoute('translation');
        }

        $zip->addFile($target_po, $file->getName());
        $zip->addFile($target_mo, $fileNameMo);

        $zip->close();

        $response = new Response();
        $response->getHeaders()->addHeaders(array(
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $zipName . '"',
            'Pragma' => 'public',
            'Content-Length' => filesize($zipFile),
        ));
        $response->setContent(file_get_contents($zipFile));

        return $response;
    }

}
