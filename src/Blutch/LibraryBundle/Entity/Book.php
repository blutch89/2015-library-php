<?php

namespace Blutch\LibraryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="tbl_books")
 * @ORM\Entity(repositoryClass="Blutch\LibraryBundle\Entity\BookRepository")
 */
class Book {

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(name="title", type="string", length=255)
	 * @Assert\NotBlank(message="book.title.not-blank")
	 */
	private $title;

	/**
	 * @ORM\Column(name="page_number", type="integer", nullable=true)
	 * @Assert\Regex(pattern="/\d/", message="book.page-number.regex")
	 */
	private $pageNumber;

	/**
	 * @ORM\Column(name="publisher", type="string", length=255, nullable=true)
	 */
	private $publisher;

	/**
	 * @ORM\Column(name="summary", type="text", nullable=true)
	 */
	private $summary;

	/**
	 * @ORM\ManyToOne(targetEntity="Author", inversedBy="books", cascade={"persist"})
	 * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=true)
	 * @Assert\NotBlank(message="book.author.not-blank")
	 */
	private $author;
	
	/**
	 * @ORM\ManyToMany(targetEntity="BookGenre", inversedBy="books", cascade={"persist"})
	 * @ORM\JoinTable(name="books_book_genres")
	 */
	private $bookGenres;
	
	public function __construct() {
		$this->bookGenres = new ArrayCollection();
	}
	
	public function markAsRead() {
		echo "Créer le fonction markAsRead de book";
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
	 * Set title
	 *
	 * @param string $title        	
	 * @return Book
	 */
	public function setTitle($title) {
		$this->title = $title;
		
		return $this;
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set pageNumber
	 *
	 * @param integer $pageNumber        	
	 * @return Book
	 */
	public function setPageNumber($pageNumber) {
		$this->pageNumber = $pageNumber;
		
		return $this;
	}

	/**
	 * Get pageNumber
	 *
	 * @return integer
	 */
	public function getPageNumber() {
		return $this->pageNumber;
	}

	/**
	 * Set publisher
	 *
	 * @param string $publisher        	
	 * @return Book
	 */
	public function setPublisher($publisher) {
		$this->publisher = $publisher;
		
		return $this;
	}

	/**
	 * Get publisher
	 *
	 * @return string
	 */
	public function getPublisher() {
		return $this->publisher;
	}

	/**
	 * Set summary
	 *
	 * @param string $summary        	
	 * @return Book
	 */
	public function setSummary($summary) {
		$this->summary = $summary;
		
		return $this;
	}

	/**
	 * Get summary
	 *
	 * @return string
	 */
	public function getSummary() {
		return $this->summary;
	}
	
	public function getAuthor() {
		return $this->author;
	}
	
	public function setAuthor($author) {
		$this->author = $author;
		
		return $this;
	}
	
	public function getBookGenres() {
		return $this->bookGenres;
	}
	
	public function addBookGenre(BookGenre $bookGenre) {
		$this->bookGenres[] = $bookGenre;
		$bookGenre->addBook($this);
	
		return $this;
	}
	
	public function removeBookGenre(BookGenre $bookGenre) {
		$this->bookGenre->removeElement($bookGenre);
	}
}
