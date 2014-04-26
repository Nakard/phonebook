<?php

use Phonebook\Exceptions\UniquePersonException;
use Phonebook\Exceptions\UniquePersonPhoneNumberException;

class Phonebook_AddController extends Zend_Controller_Action
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    public function init()
    {
        $registry = Zend_Registry::getInstance();
        $this->entityManager = $registry->entityManager;
    }

    public function existingAction()
    {
        /**
         * @var Zend_Controller_Request_Http $request
         */
        $request = $this->getRequest();
        /**
         * @var Phonebook\Repository\PersonRepository $personRepository
         */
        $personRepository = $this->entityManager->getRepository('Phonebook\Entity\Person');
        $persons = $personRepository->findAllForSelect();
        $form = new \Phonebook\Form\Phonebook_Form_ExistingPhonenumber($persons);

        $formErrors = array();

        if($request->isPost())
        {
            if($form->isValid($request->getPost()))
            {
                try
                {
                    $values = $form->getValues();
                    $personId = $values['person'];
                    $phoneNumber = new \Phonebook\Entity\PhoneNumber();
                    $phoneNumber->setPhoneNumber($values['phoneNumber']);
                    /**
                     * @var Phonebook\Repository\PersonRepository $personRepository
                     */
                    $personRepository = $this->entityManager->getRepository('Phonebook\Entity\Person');
                    $personRepository->addNumberToPerson($phoneNumber, $personId);
                    $this->redirect('/phonebook');
                }
                catch(UniquePersonPhoneNumberException $e)
                {
                    $formErrors[] = $e->getMessage();
                }
            }
            else
            {
                $formErrors = array_merge($formErrors, $form->getMessages());
                $form = new \Phonebook\Form\Phonebook_Form_ExistingPhonenumber($persons);
            }
        }
        $this->view->formErrors = $formErrors;

        $this->view->form = $form;
    }

    public function newAction()
    {
        /**
         * @var Zend_Controller_Request_Http $request
         */
        $request = $this->getRequest();

        $form = new \Phonebook\Form\Phonebook_Form_NewPhonenumber();
        $formErrors = array();
        if($request->isPost())
        {
            if($form->isValid($request->getPost()))
            {
                /**
                 * @var Phonebook\Repository\PersonRepository $personRepository
                 */
                $personRepository = $this->entityManager->getRepository('Phonebook\Entity\Person');
                try
                {
                    $form->parseForm($personRepository);
                    $values = $form->getValues();
                    $firstName = $values['firstName'];
                    $lastName = $values['lastName'];
                    $phoneNumber = new \Phonebook\Entity\PhoneNumber();
                    $phoneNumber->setPhoneNumber($values['phoneNumber']);
                    $personRepository->insertNewPerson($firstName, $lastName, $phoneNumber);
                    $this->redirect('/phonebook');
                }
                catch(\Exception $e)
                {
                    $formErrors[] = $e->getMessage();
                }
            }
            else
            {
                $formErrors = array_merge($formErrors, $form->getMessages());
                $form = new \Phonebook\Form\Phonebook_Form_NewPhonenumber();
            }
        }
        $this->view->formErrors = $formErrors;

        $this->view->form = $form;
    }


}



