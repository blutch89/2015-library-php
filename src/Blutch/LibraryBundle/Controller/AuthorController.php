<?php

namespace Blutch\LibraryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Blutch\LibraryBundle\Entity\Author;
use Blutch\LibraryBundle\Form\AuthorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Blutch\LibraryBundle\Services\Logger\ChangeLoggerEvent;

class AuthorController extends Controller {

	public function indexAction() {
		$authorRepository = $this->getDoctrine()
			->getManager()
			->getRepository("BlutchLibraryBundle:Author");
		
		$authors = $authorRepository->getAuthors();
		
		return $this->render("BlutchLibraryBundle:Author:index.html.twig", array (
				"authors" => $authors 
		));
	}

	public function viewAction(Author $author) {		
		$books = $author->getBooks();
		
		return $this->render("BlutchLibraryBundle:Author:view.html.twig", array (
				"author" => $author,
				"books" => $books 
		));
	}
	
	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function addAction(Request $request) {
		$translator = $this->get('translator');
		$author = new Author();
		$form = $this->createForm(new AuthorType(), $author);
	
		if ($form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($author);
			$em->flush();
				
			// Message FlashBag
			$request->getSession()
				->getFlashBag()
				->add("notice", $translator->trans("blutch.library.author.add.flashbag"));
			
			// Evènement Logger
			$event = new ChangeLoggerEvent(ChangeLoggerEvent::ADD, "author");
			$this->get("event_dispatcher")->dispatch("blutch_library.changelogger_eventname", $event);
			
			return $this->redirect($this->generateUrl("library_author_view", array (
					"id" => $author->getId()
			)));
		}
	
		return $this->render("BlutchLibraryBundle:Author:add.html.twig", array (
				"form" => $form->createView()
		));
	}
	
	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function editAction(Author $author, Request $request) {
		$translator = $this->get('translator');
		$em = $this->getDoctrine()->getManager();
		$form = $this->createForm(new AuthorType(), $author);
	
		if ($form->handleRequest($request)->isValid ()) {
			$em->flush ();
	
			// Message FlashBag
			$request->getSession()
				->getFlashBag()
				->add("notice", $translator->trans("blutch.library.author.edit.flashbag"));
	
			// Evènement Logger
			$event = new ChangeLoggerEvent(ChangeLoggerEvent::EDIT, "author");
			$this->get("event_dispatcher")->dispatch("blutch_library.changelogger_eventname", $event);
			
			return $this->redirect($this->generateUrl("library_author_view", array (
					"id" => $author->getId()
			)));
		}
	
		return $this->render("BlutchLibraryBundle:Author:edit.html.twig", array (
				"form" => $form->createView(),
				"author" => $author
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function deleteAction(Author $author, Request $request) {
		$translator = $this->get('translator');
		$em = $this->getDoctrine()->getManager();
		
		// On crée un formulaire vide, qui ne contiendra que le champ CSRF
		// Cela permet de protéger la suppression de livre contre la faille de CrossDomain
		$form = $this->createFormBuilder()->getForm();
		
		if ($form->handleRequest($request)->isValid()) {
			$em->remove($author);
			$em->flush();
			
			// Message FlashBag
			$request->getSession()
				->getFlashBag()
				->add("info", $translator->trans("blutch.library.author.delete.flashbag"));
			
			// Evènement Logger
			$event = new ChangeLoggerEvent(ChangeLoggerEvent::DELETE, "author");
			$this->get("event_dispatcher")->dispatch("blutch_library.changelogger_eventname", $event);
			
			return $this->redirect($this->generateUrl("library_author_list"));
		}
		
		// Si la requête est en GET, on affiche une page de confirmation avant de supprimer
		return $this->render("BlutchLibraryBundle:Author:delete.html.twig", array (
				"author" => $author,
				"form" => $form->createView() 
		));
	}

}
