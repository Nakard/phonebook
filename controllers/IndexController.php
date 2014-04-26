<?php

use Phonebook\Repository\PhoneNumberRepository;
use Phonebook\Form\Phonebook_Form_Abstract;

class Phonebook_IndexController extends Zend_Controller_Action
{
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
            ->clearActionContexts('remove')
            ->clearActionContexts('editPerson')
            ->setActionContext('remove', 'json')
            ->setActionContext('editPerson','json')
            ->setActionContext('editNumber','json')
            ->initContext();
        /**
         * @var Zend_Controller_Action_Helper_AjaxContext $ajaxContext
         */
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->clearActionContexts('remove')
            ->clearActionContexts('editPerson')
            ->addActionContext('index','html')
            ->addActionContext('editPerson','html')
            ->addActionContext('editNumber','html')
            ->initContext();
    }

    public function indexAction()
    {
        /**
         * @var Zend_Controller_Request_Http $request
         */
        $request = $this->getRequest();
        $page = $this->_getParam('page');
        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        /**
         * @var PhoneNumberRepository $phoneNumberRepository
         */
        $phoneNumberRepository = $this->entityManager->getRepository('\Phonebook\Entity\PhoneNumber');
        $numbers = $phoneNumberRepository->getNumbersHydrated();

        $paginator = Zend_Paginator::factory($numbers);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $numbersOnPage = $paginator->getCurrentItems();

        if($request->isXmlHttpRequest())
        {
            $this->_helper->viewRenderer('numbers');
        }

        $this->view->numbers = $numbersOnPage;
        $this->view->paginator = $paginator;
        $this->view->page = $page;
    }

    public function removeAction()
    {
        $phoneNumberId = $this->_getParam('id');
        /**
         * @var PhoneNumberRepository $phoneNumberRepository
         */
        $phoneNumberRepository = $this->entityManager->getRepository('\Phonebook\Entity\PhoneNumber');
        $phoneNumber = $phoneNumberRepository->find($phoneNumberId);
        $title = 'Phone number remove';
        try
        {
            $this->entityManager->remove($phoneNumber);
            $this->entityManager->flush();
            $message = 'Phone number id: '.$phoneNumberId.' removed';
            $status = '200';
        }
        catch(\Doctrine\ORM\ORMInvalidArgumentException $e)
        {
            $message = 'There was an error during removing the number from the database';
            $status = '400';
        }

        $json = array(
            'message'   =>  $message,
            'status'    =>  $status,
            'title'     =>  $title,
        );

        $this->_helper->json($json);

    }


    public function editAction()
    {
        $entityId = $this->_getParam('id');
        $entityResolver = new \Phonebook\Resolver\EntityResolver();
        $entityClass = ucfirst($entityResolver->typeTranslate($this->_getParam('type')));

        /**
         * @var Zend_Controller_Request_Http $request
         */
        $request = $this->getRequest();
        $entityRepository = $this->entityManager->getRepository('\\Phonebook\\Entity\\'.$entityClass);
        $entity = $entityRepository->find($entityId);
        $entityFormClass = "\\Phonebook\\Form\\Phonebook_Form_Edit".$entityClass;
        /**
         * @var Phonebook_Form_Abstract $entityForm
         */
        $entityForm = new $entityFormClass();
        $entityFormSetMethod = 'set'.$entityClass;
        $entityForm->$entityFormSetMethod($entity);
        $title = $entityResolver->getEntityMessage($entityClass, 'title');
        $message = $entityResolver->getEntityMessage($entityClass, 'message');
        $status = '200';
        $formErrors = array();
        $validationClosure = null;
        $validationMethod = $entityResolver->getEntityMessage($entityClass, 'validate');

        if($request->isPost())
        {
            if($entityForm->isValid($request->getPost()))
            {
                try
                {
                    if(!is_null($validationMethod))
                        $validationClosure = array($entityRepository, $validationMethod);
                    $values = $entityForm->getValues();
                    $entityResolver->callEntitySetMethods($entity, $values, $validationClosure);
                    $this->entityManager->persist($entity);
                    $this->entityManager->flush();
                }
                catch(\Exception $e)
                {
                    $this->setExceptionErrorResponse($formErrors,$status,$message,$e);
                }
            }
            else
            {
                $this->setFormErrorResponse($formErrors, $status, $message, $entityForm);
            }
            $this->_helper->json(array(
                'title'         =>  $title,
                'status'        =>  $status,
                'message'       =>  $message,
                'formErrors'    =>  $formErrors,
                'form'          =>  $entityForm->render()
            ));
            return;
        }

        $this->view->modalData = array(
            'title' =>  $title,
            'form'  =>  $entityForm->render()
        );
    }

    /**
     * Sets data for form error json output
     *
     * @param array     $formErrors
     * @param string    $status
     * @param string    $message
     */
    protected function setFormErrorResponse(array &$formErrors, &$status, &$message, Phonebook_Form_Abstract &$form)
    {
        $formErrors = array_merge($formErrors, $form->getMessages());
        $status = '400';
        $message = 'There are errors in the form';
    }

    /**
     * Sets data for exception error json output
     *
     * @param array     $formErrors
     * @param string    $status
     * @param string    $message
     * @param Exception $e
     */
    protected function setExceptionErrorResponse(array &$formErrors, &$status, &$message, \Exception $e)
    {
        $formErrors[] = $e->getMessage();
        $status = '400';
        $message = 'Database error occurred';
    }
}

