<?php
/**
 * Phonebook_Form_Abstract.php
 *
 * Creation date: 2014-04-26
 * Creation time: 12:57
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Form;


abstract class Phonebook_Form_Abstract extends \Zend_Form{

    /**
     * Sets default bootstrap decorators
     */
    protected function setMainDecorators()
    {
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'fieldset', 'class' => 'fieldset-bordered')),
            'Form',
        ));
        $this->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            array('Label', array('class' => 'sr-only')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')),
            array('ViewHelper', array('class' => 'form-control')),
        ));
    }

    /**
     * @inheritdoc
     */
    public function getMessages($name = null, $suppressArrayNotation = false)
    {
        $messages = parent::getMessages($name, $suppressArrayNotation);
        return $this->errorKeysTranslate($messages);
    }

    /**
     * Translates error form messages in more human readable fashion
     *
     * @param   array $messages
     * @return  array
     */
    abstract protected function errorKeysTranslate(array $messages);

} 