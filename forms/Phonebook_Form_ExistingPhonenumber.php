<?php
/**
 * Phonebook_Form_ExistingPhonenumber.php
 * 
 * Data utworzenia: 25.04.2014
 * Czas utworzenia: 13:03
 *
 * @author Arkadiusz Moskwa <arkadiusz.moskwa@novamedia.pl>
 */

namespace Phonebook\Form;

use Phonebook\Exceptions\UniquePersonPhoneNumberException;
use Phonebook\Form\Elements\Hash;
use Phonebook\Form\Elements\PersonSelect;
use Phonebook\Form\Elements\PhoneNumberText;
use Phonebook\Form\Elements\Submit;
use Phonebook\Repository\PhoneNumberRepository;

/**
 * Class Phonebook_Form_ExistingPhonenumber
 * @package Phonebook\Form
 */
class Phonebook_Form_ExistingPhonenumber extends Phonebook_Form_Abstract{

    /**
     * Populates select options after initialization
     *
     * @param array $persons
     * @param mixed $options
     */
    public function __construct(array $persons, $options = null)
    {
        parent::__construct($options);
        /**
         * @var \Zend_Form_Element_Select $select
         */
        $select = $this->getElement('person');
        $select->addMultiOptions($persons);
    }

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

        $this->addElement(new PersonSelect());
        $this->addElement(new PhoneNumberText());
        $this->addElement(new Submit('Submit'));
        $this->addElement(new Hash());

        $this->setMainDecorators();
    }

    /**
     * @inheritdoc
     */
    protected function errorKeysTranslate(array $messages)
    {
        $translatedMessages = array();
        $translation = array(
            'phoneNumber'   =>  'Phone number',
            'csrf'          =>  'CSRF Token'
        );
        foreach($messages as $formKey => $formErrors)
        {
            $translatedMessages[$translation[$formKey]] = $formErrors;
        }
        return $translatedMessages;
    }
} 