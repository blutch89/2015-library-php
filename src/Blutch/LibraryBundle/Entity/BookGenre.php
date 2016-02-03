<?php

namespace Blutch\LibraryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="tbl_book_genres")
 * @ORM\Entity(repositoryClass="Blutch\LibraryBundle\Entity\BookGenreRepository")
 */
class BookGenre {

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(name="title", type="string", length=255)
	 * @Assert\NotBlank(message="bookgenre.description.not-blank")
	 */
	private $description;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Book", mappedBy="bookGenres", cascade={"persist"})
	 */
	private $books;
	
	public function __construct() {
		$this->books = new ArrayCollection ();
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
	 * Set description
	 *
	 * @param string $description        	
	 * @return BookGenre
	 */
	public function setDescription($description) {
		$this->description = $description;
		
		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}
	
	public function getBooks() {
		return $this->books;
	}
	
	public function addBook(Book $book) {
		$this->books[] = $book;
	
		return $this;
	}
	
	public function removeBook(Book $book) {
		$this->books->removeElement($book);
	}
}
