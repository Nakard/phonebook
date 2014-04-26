<?php
/**
 * Phonebook_Form_Phonenumber.php
 *
 * Creation date: 2014-04-24
 * Creation time: 21:17
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Form;

use Doctrine\ORM\EntityManager;
use Phonebook\Exceptions\UniquePersonException;
use Phonebook\Form\Elements\Hash;
use Phonebook\Form\Elements\PersonNameText;
use Phonebook\Form\Elements\PhoneNumberText;
use Phonebook\Form\Elements\Submit;
use Phonebook\Repository\PersonRepository;

/**
 * Class Phonebook_Form_NewPhonenumber
 * @package Phonebook\Form
 */
class Phonebook_Form_NewPhonenumber extends Phonebook_Form_Abstract {

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setMethod('POST');

        $this->setAttribs(array(
            'class' =>  'form-inline',
            'role'  =>  'form'
        ));

        $this->addElement(new PersonNameText('firstName', 'First Name'));
        $this->addElement(new PersonNameText('lastName', 'Last Name'));
        $this->addElement(new PhoneNumberText());
        $this->addElement(new Submit('submit'));
        $this->addElement(new Hash());

        $this->setMainDecorators();
    }

    /**
     * Translates form errors array with more human readable
     *
     * @param   array   $messages
     * @return  array
     */
    protected function errorKeysTranslate(array $messages)
    {
        $translatedMessages = array();
        $translation = array(
            'firstName'     =>  'First name',
            'lastName'      =>  'Last name',
            'phoneNumber'   =>  'Phone number',
            'csrf'          =>  'CSRF Token'
        );
        foreach($messages as $formKey => $formErrors)
        {
            $translatedMessages[$translation[$formKey]] = $formErrors;
        }
        return $translatedMessages;
    }

    /**
     * Checks if form can be passed to database
     *
     * @param   PersonRepository    $repo
     * @return  UniquePersonException|bool
     */
    public function parseForm(PersonRepository &$repo)
    {
        $values = $this->getValues();

        $firstName = $values['firstName'];
        $lastName = $values['lastName'];

        if(!$repo->checkPersonUniqueness($firstName, $lastName))
        {
            return new UniquePersonException('Person with this credentials already exists !');
        }

        return true;
    }
} 