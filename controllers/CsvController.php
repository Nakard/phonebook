<?php
/**
 * CsvController.php
 *
 * Creation date: 2014-04-27
 * Creation time: 12:31
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

class Phonebook_CsvController extends Zend_Controller_Action{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    public function init()
    {
        $registry = Zend_Registry::getInstance();
        $this->entityManager = $registry->entityManager;
        /**
         * @var Zend_Controller_Action_Helper_ContextSwitch $contextSwitch
         */
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch
            ->clearActionContexts('export')
            ->initContext();
        /**
         * @var Zend_Controller_Action_Helper_AjaxContext $ajaxContext
         */
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->clearActionContexts('export')
            ->initContext();
    }

    public function exportAction()
    {
        /**
         * @var \Phonebook\Repository\PhoneNumberRepository $phoneNumberRepository
         */
        $phoneNumberRepository = $this->entityManager->getRepository('\Phonebook\Entity\PhoneNumber');
        $phoneNumbers = $phoneNumberRepository->getExport();
        $filename = 'export.csv';
        $csvHandler = new \Phonebook\File\CsvHandler();
        $csvHandler->exportPhoneNumbers($filename,$phoneNumbers);
        header('Set-Cookie: fileDownload=true; path=/');
        header('Cache-Control: max-age=60, must-revalidate');
        header("Content-type: text/csv");
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        readfile($filename);
    }

    public function importAction()
    {
        $form = new \Phonebook\Form\Phonebook_Form_FileUpload();
        $adapter = new Zend_File_Transfer_Adapter_Http();
        $formErrors = array();
        $title = 'Import phone numbers';
        $status = '200';
        $iterator = 0;
        if($adapter->receive())
        {
            /**
             * @var \Phonebook\Repository\PersonRepository $personRepository
             */
            $personRepository = $this->entityManager->getRepository('\\Phonebook\\Entity\\Person');
            try
            {
                $filename = $adapter->getFileName();
                $csvHandler = new \Phonebook\File\CsvHandler();
                $preparedArray = $csvHandler->parseImport($filename);
                $iterator = $personRepository->mergeImport($preparedArray);
                $message =
                    $iterator.
                    ' numbers were successfully imported and merged,no errors occurred during import';
            }
            catch(\Exception $e)
            {
                $formErrors[] = $e->getMessage();
                $message = 'Some errors occurred while importing data';
                $status = '400';
            }
            $this->_helper->json(array(
                'title'         =>  $title,
                'message'       =>  $message,
                'formErrors'    =>  $formErrors,
                'status'        =>  $status,
                'count'         =>  $iterator,
            ));
        }

        $this->view->modalData = array(
            'title' =>  $title,
            'form'  =>  $form->render()
        );
    }
} 