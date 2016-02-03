<?php

namespace Blutch\LibraryBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Blutch\LibraryBundle\Entity\Author;
use Blutch\LibraryBundle\Entity\Book;
use Blutch\LibraryBundle\Entity\BookGenre;
use Blutch\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadBasicsDatas implements FixtureInterface, ContainerAwareInterface {

	public function load(ObjectManager $manager) {
		// Création des genres
		$bookGenresList = array (
				"Science-Fiction",
				"Fantasy",
				"Aventure",
				"Autobiographie" 
		);
		
		$bookGenres = array ();
		
		foreach ($bookGenresList as $bookGenreDescription) {
			$bookGenre = new BookGenre();
			$bookGenre->setDescription($bookGenreDescription);
			
			$bookGenres[] = $bookGenre;
			$manager->persist($bookGenre);
		}
		
		// Création des auteurs
		$author1 = new Author();
		$author1->setFirstName("Frank");
		$author1->setLastName("Herbert");
		$author1->setBirthDateManual(1920, 10, 8);
		$author1->setBiography("Cet auteur a écrit plusieurs romans dont la célèbre saga des dunes.");
		
		$author2 = new Author();
		$author2->setFirstName("J.R.R");
		$author2->setLastName("Tolkien");
		$author2->setBirthDateManual(1892, 1, 3);
		$author2->setBiography("Cet auteur a écrit les fameux livres sur la Terre du Milieu.");
		
		$author3 = new Author();
		$author3->setFirstName("Jon");
		$author3->setLastName("Krakauer");
		$author3->setBirthDateManual(1954, 4, 12);
		$author3->setBiography("Il est l'auteur de la célèbre autpbiographie de Christopher McCandless et de Tragédie à l'everest.");
		
		// Création des livres
		$book1 = new Book();
		$book1->setTitle("Dune");
		$book1->setPageNumber(600);
		$book1->setPublisher("Chilton Books");
		$book1->setSummary("L’histoire débute en l’an 10191 après la création de la Guilde spatiale. L’empereur Shaddam IV exerce son pouvoir féodal3 sur tout l’univers connu. L’humanité a conquis une grande étendue de l’univers, notamment grâce à une mystérieuse substance dénommée « Épice » ou « Mélange ». L’Épice constitue un puissant stimulant cérébral et permet à certains humains de décupler leurs capacités psychiques. De plus, elle accroît considérablement la durée de vie et immunise le corps contre de nombreuses maladies. Son origine précise est un mystère et les quantités disponibles sont rarissimes ; elle est par ailleurs impossible à synthétiser. L'ensemble de ces paramètres lui confère une valeur monétaire particulièrement élevée.");
		
		$book2 = new Book();
		$book2->setTitle("Le Messie de Dune");
		$book2->setPageNumber(300);
		$book2->setPublisher("New English Library");
		$book2->setSummary("Paul Atréides a triomphé de ses ennemis. En douze ans de guerre sainte, ses Fremen ont conquis l’univers. Partout flotte la bannière verte du Jihad des Atréides. Il est devenu l’Empereur Muad'Dib, presque un Dieu, et il voit l’avenir. Ses ennemis, il les connait : la Guilde spatiale, le Bene Gesserit, l’ancienne Maison Impériale Corrino et évidemment le Bene Tleilax. Il sait quand et comment ils frapperont. Ils vont essayer de lui reprendre l’Épice qui donne la prescience, et peut-être percer le secret de son pouvoir.");
		
		$book3 = new Book();
		$book3->setTitle("Le Seigneur des Anneaux, La communeauté de l'anneau.");
		$book3->setPageNumber(1000);
		$book3->setPublisher("Allen & Unwin");
		$book3->setSummary("Aux temps reculés qu'évoque le récit, la Terre est peuplée d'innombrables créatures étranges.
Les Hobbits, apparentés à l'Homme, mais proches également des Elfes et des Nains, vivent en paix au nord-ouest de l'Ancien Monde, dans la Comté. Paix précaire et menacée, cependant, depuis que Bilbon Sacquet a dérobé au monstre Gollum l'Anneau de Puissance jadis forgé par Sauron de Mordor.");
		
		$book4 = new Book();
		$book4->setTitle("Voyage au bout de la solitude");
		$book4->setPageNumber(224);
		$book4->setPublisher("Random House");
		$book4->setSummary("Voyage au bout de la solitude (Into the Wild) est un roman biographique de Christopher McCandless écrit par Jon Krakauer, publié en 1996. Il retrace l'histoire véridique de ce jeune homme qui avait troqué la civilisation pour un retour à la vie sauvage, et y avait trouvé la mort. Il s'agit d'une version longue d'un article de Krakauer, intitulé Death of an Innocent et paru dans le numéro de janvier 1993 de Outside.");
		
		// Création de l'utilisateur
		$userManager = $this->container->get('fos_user.user_manager');
		$user = $userManager->createUser();
		$user->setUsername("thomas");
		$user->setEmail("thomas@thomas.ch");
		$user->setPlainPassword("1234");
		$user->setEnabled(true);
		$user->setRoles(array('ROLE_ADMIN'));
		$userManager->updateUser($user, true);
		
		// Liaisons entre les objets
		$author1->addBook($book1);
		$author1->addBook($book2);
		$author2->addBook($book3);
		$author3->addBook($book4);
		
		$book1->addBookGenre($bookGenres[0]);
		$book1->addBookGenre($bookGenres[2]);
		$book2->addBookGenre($bookGenres[0]);
		$book2->addBookGenre($bookGenres[2]);
		$book3->addBookGenre($bookGenres[1]);
		$book3->addBookGenre($bookGenres[2]);
		$book4->addBookGenre($bookGenres[3]);
		
		// Persist des objets
		$manager->persist($author1);
		$manager->persist($author2);
		$manager->persist($author3);
		$manager->persist($book1);
		$manager->persist($book2);
		$manager->persist($book3);
		$manager->persist($book4);
		
		// Sauvegarde en base de donn�es
		$manager->flush();
	}
	
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
	}
}