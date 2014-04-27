<?php

use Phonebook\Exceptions\UniquePersonException;
use Phonebook\Exceptions\UniquePersonPhoneNumberException;

/**
 * Controller responsible for adding numbers and persons
 *
 * Class Phonebook_AddController
 */
class Phonebook_AddController extends Zend_Controller_Action
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Inits Doctrine Entity Manager
     */
    public function init()
    {
        $registry = Zend_Registry::getInstance();
        $this->entityManager = $registry->entityManager;
    }

    /**
     * Adds number to existing person
     */
    public function existingAction()
    {
        /**
         * @var Zend_Controller_Request_Http $request
         */
        $request = $this->getRequest();
        /**
         * @var Phonebook\Repository\PersonRepository $personRepository
         */
        $session = new Zend_Session_Namespace('Phonebook');
        $personRepository = $this->entityManager->getRepository('Phonebook\Entity\Person');
        $persons = $personRepository->findAllForSelect();
        if(empty($persons))
            $this->redirect('/phonebook/add/new');
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
                    $number = $values['phoneNumber'];
                    $phoneNumber = new \Phonebook\Entity\PhoneNumber();
                    $phoneNumber->setPhoneNumber($number);
                    /**
                     * @var \Phonebook\Repository\PersonRepository $personRepository
                     */
                    $personRepository = $this->entityManager->getRepository('Phonebook\Entity\Person');
                    /**
                     * @var \Phonebook\Entity\Person $person
                     */
                    $person = $personRepository->find($personId);
                    $firstName = $person->getFirstName();
                    $lastName = $person->getLastName();
                    $personRepository->addNumberToPerson($phoneNumber, $personId);
                    $session->message = 'Successfully added '.$number.' to '.$firstName.' '.$lastName.' numbers';
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

    /**
     * Adds a new number and person
     */
    public function newAction()
    {
        /**
         * @var Zend_Controller_Request_Http $request
         */
        $request = $this->getRequest();
        $session = new Zend_Session_Namespace('Phonebook');

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
                    $number = $values['phoneNumber'];
                    $phoneNumber = new \Phonebook\Entity\PhoneNumber();
                    $phoneNumber->setPhoneNumber($number);
                    $personRepository->insertNewPerson($firstName, $lastName, $phoneNumber);
                    $session->message = 'Successfully added '.$firstName.' '.$lastName.' with phone number '.$number;
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



