<?php
/**
 * Phonebook_Form_EditPerson.php
 *
 * Creation date: 2014-04-26
 * Creation time: 12:54
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Form;


use Phonebook\Entity\Person;
use Phonebook\Form\Elements\Hash;
use Phonebook\Form\Elements\PersonNameText;
use Phonebook\Form\Elements\Submit;

/**
 * Class Phonebook_Form_EditPerson
 * @package Phonebook\Form
 */
class Phonebook_Form_EditPerson extends Phonebook_Form_Abstract {

    public function init()
    {
        $this->setMethod('POST');

        $this->setAttrib('id','editForm');

        $this
            ->addElement(new PersonNameText('firstName','First Name'))
            ->addElement(new PersonNameText('lastName', 'Last Name'))
            ->addElement(new Submit('Save Changes'))
            ->addElement(new Hash());
        $this->setMainDecorators();
    }

    /**
     * Sets the person data in the inputs
     *
     * @param Person $person
     */
    public function setPerson(Person $person)
    {
        $firstName = $person->getFirstName();
        $lastName = $person->getLastName();

        $this->getElement('firstName')->setValue($firstName);
        $this->getElement('lastName')->setValue($lastName);
    }

    /**
     * @inheritdoc
     */
    protected function errorKeysTranslate(array $messages)
    {
        $translatedMessages = array();
        $translation = array(
            'firstName'     =>  'First name',
            'lastName'      =>  'Last name',
            'csrf'          =>  'CSRF Token'
        );
        foreach($messages as $formKey => $formErrors)
        {
            $translatedMessages[$translation[$formKey]] = $formErrors;
        }
        return $translatedMessages;
    }

} 