<?php

namespace Blutch\LibraryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Blutch\LibraryBundle\Entity\BookGenre;
use Blutch\LibraryBundle\Form\BookGenreType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Blutch\LibraryBundle\Services\Logger\ChangeLoggerEvent;

class BookGenreController extends Controller {

	public function indexAction() {
		$bookGenreRepository = $this->getDoctrine()
			->getManager()
			->getRepository("BlutchLibraryBundle:BookGenre");
		
		$bookGenres = $bookGenreRepository->getBookGenres();
		
		return $this->render("BlutchLibraryBundle:BookGenre:index.html.twig", array (
				'bookGenres' => $bookGenres 
		));
	}

	public function viewAction(BookGenre $bookGenre) {
		$translator = $this->get('translator');		
		$books = $bookGenre->getBooks();
		
		return $this->render("BlutchLibraryBundle:BookGenre:view.html.twig", array (
				"bookGenre" => $bookGenre,
				"books" => $books 
		));
	}
	
	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function addAction(Request $request) {
		$translator = $this->get('translator');
		$bookGenre = new BookGenre();
		$form = $this->createForm(new BookGenreType(), $bookGenre);
	
		if ($form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($bookGenre);
			$em->flush();
	
			// Message FlashBag
			$request->getSession()
				->getFlashBag()
				->add("notice", $translator->trans("blutch.library.bookgenre.add.flashbag"));
	
			// Evènement Logger
			$event = new ChangeLoggerEvent(ChangeLoggerEvent::ADD, "genre");
			$this->get("event_dispatcher")->dispatch("blutch_library.changelogger_eventname", $event);
			
			return $this->redirect($this->generateUrl("library_bookgenre_view", array (
					"id" => $bookGenre->getId()
			)));
		}
	
		return $this->render("BlutchLibraryBundle:BookGenre:add.html.twig", array (
				"form" => $form->createView()
		));
	}
	
	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function editAction(BookGenre $bookGenre, Request $request) {
		$translator = $this->get('translator');
		$em = $this->getDoctrine()->getManager();
	
		$form = $this->createForm(new BookGenreType(), $bookGenre);
	
		if ($form->handleRequest($request)->isValid ()) {
			$em->flush ();
	
			// Message FlashBag
			$request->getSession()
				->getFlashBag()
				->add("notice", $translator->trans("blutch.library.bookgenre.edit.flashbag"));
	
			// Evènement Logger
			$event = new ChangeLoggerEvent(ChangeLoggerEvent::EDIT, "genre");
			$this->get("event_dispatcher")->dispatch("blutch_library.changelogger_eventname", $event);
			
			return $this->redirect($this->generateUrl("library_bookgenre_view", array (
					"id" => $bookGenre->getId()
			)));
		}
	
		return $this->render("BlutchLibraryBundle:BookGenre:edit.html.twig", array (
				"form" => $form->createView(),
				"bookGenre" => $bookGenre
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function deleteAction(BookGenre $bookGenre, Request $request) {
		$translator = $this->get('translator');
		$em = $this->getDoctrine()->getManager();
		
		// On crée un formulaire vide, qui ne contiendra que le champ CSRF
		// Cela permet de protéger la suppression de livre contre la faille de CrossDomain
		$form = $this->createFormBuilder()->getForm();
		
		if ($form->handleRequest($request)->isValid()) {
			$em->remove($bookGenre);
			$em->flush();
			
			// Message FlashBag
			$request->getSession()
				->getFlashBag()
				->add("info", $translator->trans("blutch.library.bookgenre.delete.flashbag"));
			
			// Evènement Logger
			$event = new ChangeLoggerEvent(ChangeLoggerEvent::DELETE, "genre");
			$this->get("event_dispatcher")->dispatch("blutch_library.changelogger_eventname", $event);
			
			return $this->redirect($this->generateUrl("library_bookgenre_list"));
		}
		
		// Si la requête est en GET, on affiche une page de confirmation avant de supprimer
		return $this->render("BlutchLibraryBundle:BookGenre:delete.html.twig", array (
				"bookGenre" => $bookGenre,
				"form" => $form->createView() 
		));
	}

}
