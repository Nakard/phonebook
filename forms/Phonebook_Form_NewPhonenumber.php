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

        $this->addElement('text','firstName', array(
            'placeholder'   =>  'First name',
            'label'         =>  'First name',
            'class'         =>  'form-control',
            'required'      =>  true,
            'filters'       =>  array('StringTrim', 'StripTags'),
            'validators'    =>  array(
                array('validator' => 'StringLength', 'options' => array(1,50)),
                array('notEmpty'),
                'Alpha'
            )
        ));
        $this->addElement('text','lastName', array(
            'placeholder'   =>  'Last name',
            'label'         =>  'Last name',
            'class'         =>  'form-control',
            'required'      =>  true,
            'filters'       =>  array('StringTrim', 'StripTags'),
            'validators'    =>  array(
                array('validator' => 'StringLength', 'options' => array(1,50)),
                array('validator' => 'notEmpty', 'messages' => array(
                    'isEmpty'
                )),
                'Alpha'
            )
        ));

        $this->addElement('text','phoneNumber',array(
            'placeholder'   =>  'Phone number',
            'label'         =>  'Phone number',
            'class'         =>  'form-control',
            'required'      =>  true,
            'filters'       =>  array('StringTrim', 'StripTags'),
            'validators'    =>  array(
                array('notEmpty'),
                'Digits'
            )
        ));
        $this->addElement('submit', 'submit', array(
            'ignore'    =>  true,
            'label'     =>  'Submit',
            'class'     =>  'btn btn-primary'
        ));
        $this->addElement('hash','csrf', array(
            'ignore'    =>  true,
        ));


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