<?php
/**
 * Person.php
 *
 * Creation date: 2014-04-24
 * Creation time: 17:32
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Phonebook\Exceptions\UniquePersonPhoneNumberException;


/**
 * Class Person
 * @package Phonebook\Entity
 *
 * @Entity(repositoryClass="Phonebook\Repository\PersonRepository")
 * @Table(name="persons", uniqueConstraints={@UniqueConstraint(name="fullname_idx", columns={"firstName","lastName"})})
 *
 */
class Person {

    /**
     * @var int $id
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * @var string $firstName
     * @Column(type="string", length=50, nullable=true)
     */
    protected $firstName;

    /**
     * @var string $lastName
     * @Column(type="string",length=50, nullable=true)
     */
    protected $lastName;

    /**
     * @var PhoneNumber[]
     * @OneToMany(targetEntity="PhoneNumber", mappedBy="person", cascade={"persist"})
     */
    protected $phoneNumbers = null;

    /**
     * Initializing phone numbers collection
     */
    public function __construct()
    {
        $this->phoneNumbers = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|null
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    /**
     * @param PhoneNumber $phoneNumber
     */
    public function addPhoneNumber(PhoneNumber $phoneNumber)
    {
        $this->assertPersonHasNumber($phoneNumber);
        $this->phoneNumbers[] = $phoneNumber;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @throws \Phonebook\Exceptions\UniquePersonPhoneNumberException
     */
    public function assertPersonHasNumber(PhoneNumber $phoneNumber)
    {
        /**
         * @var Phonenumber $number
         */
        foreach($this->getPhoneNumbers() as $number)
        {
            if((int) $number->getPhoneNumber() === (int)$phoneNumber->getPhoneNumber())
                throw new UniquePersonPhoneNumberException();
        }
    }

    /**
     * Sets person credentials
     *
     * @param $firstName
     * @param $lastName
     */
    public function setCredentials($firstName, $lastName)
    {
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
    }
} 