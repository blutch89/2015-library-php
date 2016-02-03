<?php

namespace Blutch\LibraryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Blutch\LibraryBundle\Entity\Book;
use Blutch\LibraryBundle\Form\BookType;
use Blutch\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Blutch\LibraryBundle\Services\Logger\ChangeLoggerEvent;

class BookController extends Controller {

	public function indexAction() {
		$bookRepository = $this->getDoctrine()
			->getManager()
			->getRepository("BlutchLibraryBundle:Book");
		
		$books = $bookRepository->getBooks();
		
		return $this->render("BlutchLibraryBundle:Book:index.html.twig", array (
				"books" => $books 
		));
	}

	public function viewAction(Book $book) {
		return $this->render("BlutchLibraryBundle:Book:view.html.twig", array (
				"book" => $book 
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function addAction(Request $request) {
		$translator = $this->get('translator');
		$book = new Book();
		$form = $this->createForm(new BookType(), $book);
		
		if ($form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($book);
			$em->flush();
			
			// Message FLashBag
			$request->getSession()
				->getFlashBag()
				->add("notice", $translator->trans("blutch.library.book.add.flashbag"));
			
			// Evènement Logger
			$event = new ChangeLoggerEvent(ChangeLoggerEvent::ADD, "book");
			$this->get("event_dispatcher")->dispatch("blutch_library.changelogger_eventname", $event);
			
			return $this->redirect($this->generateUrl("library_book_view", array (
					"id" => $book->getId() 
			)));
		}
		
		return $this->render("BlutchLibraryBundle:Book:add.html.twig", array (
				"form" => $form->createView ()
		));
	}
	
	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function editAction(Book $book, Request $request) {
		$translator = $this->get('translator');
		$em = $this->getDoctrine()->getManager();
		
		$form = $this->createForm(new BookType(), $book);
		
		if ($form->handleRequest($request)->isValid ()) {
			$em->flush ();
				
			// Message FlashBag
			$request->getSession()
				->getFlashBag()
				->add("notice", $translator->trans("blutch.library.book.edit.flashbag"));
				
			// Evènement Logger
			$event = new ChangeLoggerEvent(ChangeLoggerEvent::EDIT, "book");
			$this->get("event_dispatcher")->dispatch("blutch_library.changelogger_eventname", $event);
			
			return $this->redirect($this->generateUrl("library_book_view", array (
					"id" => $book->getId()
			)));
		}
		
		return $this->render("BlutchLibraryBundle:Book:edit.html.twig", array (
				"form" => $form->createView(),
				"book" => $book
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function deleteAction(Book $book, Request $request) {
		$translator = $this->get('translator');
		$em = $this->getDoctrine()
			->getManager();
		
		// On crée un formulaire vide, qui ne contiendra que le champ CSRF
		// Cela permet de protéger la suppression de livre contre la faille de CrossDomain
		$form = $this->createFormBuilder()
			->getForm();
		
		if ($form->handleRequest($request)
			->isValid()) {
			$em->remove($book);
			$em->flush();
			
			// Message FlashBag
			$request->getSession()
				->getFlashBag()
				->add("info", $translator->trans("blutch.library.book.delete.flashbag"));
			
			// Evènement Logger
			$event = new ChangeLoggerEvent(ChangeLoggerEvent::DELETE, "book");
			$this->get("event_dispatcher")->dispatch("blutch_library.changelogger_eventname", $event);
			
			return $this->redirect($this->generateUrl("homepage"));
		}
		
		// Si la requête est en GET, on affiche une page de confirmation avant de supprimer
		return $this->render("BlutchLibraryBundle:Book:delete.html.twig", array (
				"book" => $book,
				"form" => $form->createView() 
		));
	}

}
