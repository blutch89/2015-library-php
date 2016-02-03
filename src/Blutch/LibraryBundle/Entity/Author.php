<?php

namespace Blutch\LibraryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="tbl_authors")
 * @ORM\Entity(repositoryClass="Blutch\LibraryBundle\Entity\AuthorRepository")
 */
class Author {

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(name="first_name", type="string", length=255)
	 * @Assert\NotBlank(message="author.first-name.not-blank")
	 */
	private $firstName;

	/**
	 * @ORM\Column(name="last_name", type="string", length=255)
	 * @Assert\NotBlank(message="author.last-name.not-blank")
	 */
	private $lastName;

	/**
	 * @ORM\Column(name="birth_date", type="date", nullable=true)
	 * @Assert\Date(message="author.birthdate.date")
	 */
	private $birthDate;

	/**
	 * @ORM\Column(name="biography", type="string", nullable=true)
	 */
	private $biography;

	/**
	 * @ORM\OneToMany(targetEntity="Book", mappedBy="author", cascade={"persist", "remove"})
	 */
	private $books;

	public function __construct() {
		$this->birthDate = new \DateTime();
		$this->books = new ArrayCollection();
	}
	
	public function getFullName() {
		return $this->getFirstName() . " " . $this->getLastName();
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set firstName
	 *
	 * @param string $firstName        	
	 * @return Author
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
		
		return $this;
	}

	/**
	 * Get firstName
	 *
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * Set lastName
	 *
	 * @param string $lastName        	
	 * @return Author
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;
		
		return $this;
	}

	/**
	 * Get lastName
	 *
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	public function setBirthDateManual($year, $month, $day) {
		$this->getBirthDate()
			->setDate($year, $month, $day);
	}

	/**
	 * Get birthDate
	 *
	 * @return \DateTime
	 */
	public function getBirthDate() {
		return $this->birthDate;
	}

	/**
	 * Set biography
	 *
	 * @param string $biography        	
	 * @return Author
	 */
	public function setBiography($biography) {
		$this->biography = $biography;
		
		return $this;
	}

	/**
	 * Get biography
	 *
	 * @return string
	 */
	public function getBiography() {
		return $this->biography;
	}

	public function getBooks() {
		return $this->books;
	}

	public function addBook(Book $book) {
		$this->books[] = $book;
		$book->setAuthor($this);
		
		return $this;
	}

	public function removeBook(Book $book) {
		$this->books->removeElement($book);
	}

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     * @return Author
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }
}
