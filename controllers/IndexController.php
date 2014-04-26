<?php

use Phonebook\Repository\PhoneNumberRepository;

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

    public function editPersonAction()
    {
        $personId = $this->_getParam('id');
        /**
         * @var Zend_Controller_Request_Http $request
         */
        $request = $this->getRequest();
        /**
         * @var \Phonebook\Entity\Person $person
         */
        $person = $this->entityManager->find('\Phonebook\Entity\Person',$personId);
        $form = new \Phonebook\Form\Phonebook_Form_EditPerson();
        $form->setPerson($person);
        $title = 'Edit person credentials';
        $message = 'Credentials changed successfully';
        $status = '200';
        $formErrors = array();
        if($request->isPost())
        {
            if($form->isValid($request->getPost()))
            {
                $values = $form->getValues();
                $person->setFirstName($values['firstName']);
                $person->setLastName($values['lastName']);
                $this->entityManager->persist($person);
                try
                {
                    $this->entityManager->flush();
                }
                catch(\Doctrine\DBAL\DBALException $e)
                {
                    $formErrors[] = $e->getMessage();
                    $status = '400';
                    $message = 'Database error occurred';
                }
            }
            else
            {
                $formErrors = array_merge($formErrors, $form->getMessages());
                $status = '400';
                $message = 'There are errors in the form';
            }

            $this->_helper->json(array(
                'title'         =>  $title,
                'status'        =>  $status,
                'message'       =>  $message,
                'formErrors'    =>  $formErrors,
                'form'          =>  $form->render()
            ));
            return;
        }

        $this->view->modalData = array(
            'title' =>  $title,
            'form'  =>  $form->render()
        );
    }
}

