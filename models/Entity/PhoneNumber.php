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

use Phonebook\Exceptions\UniquePersonPhoneNumberException;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;

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
     * @Column(type="string", columnDefinition="CHAR(9) NOT NULL")
     */
    protected $phoneNumber;

    /**
     * @param int $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->assertNumberUniqueness($phoneNumber);
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
    /**
     * Checks if phone number is unique for a person
     *
     * @param   string                         $number
     * @throws  UniquePersonPhoneNumberException
     */
    public function assertNumberUniqueness($number)
    {
        $person = $this->getPerson();
        if(is_null($person))
            return;
        $personPhoneNumbers = $person->getPhoneNumbers();
        /**
         * @var PhoneNumber $phoneNumber
         */
        foreach($personPhoneNumbers as $phoneNumber)
        {
            if((int)$phoneNumber->getPhoneNumber() === (int)$number)
                throw new UniquePersonPhoneNumberException();
        }
    }
}