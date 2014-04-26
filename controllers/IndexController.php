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
            ->setActionContext('remove', 'json')
            ->initContext();
        /**
         * @var Zend_Controller_Action_Helper_AjaxContext $ajaxContext
         */
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('index','html')
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
        if($request->isPost() && $modalData = $request->getPost('modalData'))
        {
            $this->view->modalData = $modalData;
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
        $confirm = false;
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
            'confirm'   =>  $confirm
        );

        $this->_helper->json($json);

    }


}

