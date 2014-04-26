<?php
/**
 * EditController.php
 *
 * Creation date: 2014-04-26
 * Creation time: 12:28
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

class Phonebook_EditController extends Zend_Controller_Action {

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
            ->setActionContext('person', 'json')
            ->setActionContext('number', 'json')
            ->initContext();
    }

    public function personAction()
    {

    }

    public function numberAction()
    {

    }

} 