<?php
/**
 * PhoneNumber.php
 *
 * Creation date: 2014-04-24
 * Creation time: 18:43
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Entity;

/**
 * Class PhoneNumber
 * @package Phonebook\Entity
 *
 * @Entity(repositoryClass="Phonebook\Repository\PhoneNumberRepository")
 * @Table(name="phone_numbers")
 * @HasLifecycleCallbacks
 */
class PhoneNumber {

    /**
     * @var int $id
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @var int $person
     * @ManyToOne(targetEntity="Person", inversedBy="phoneNumbers", cascade={"persist"})
     */
    protected $person;

    /**
     * @var int $phoneNumber
     * @Column(type="integer")
     */
    protected $phoneNumber;

    /**
     * @param int $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return int
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param Person $person
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
} 