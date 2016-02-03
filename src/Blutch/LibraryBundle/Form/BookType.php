<?php

namespace Blutch\LibraryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class BookType extends AbstractType {

	/**
	 *
	 * @param FormBuilderInterface $builder        	
	 * @param array $options        	
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add("title", "text")
			->add("pageNumber", "text")
			->add("publisher", "text")
			->add("summary", "textarea")
			->add("save", "submit");
		
		$builder->add("author", "entity", array (
				"class" => "BlutchLibraryBundle:Author",
				"property" => "getFullName",
				"multiple" => false,
				"query_builder" => function (EntityRepository $repository) {
					return $repository->createQueryBuilder("a")
						->orderBy("a.lastName", "ASC")
						->orderBy("a.firstName", "ASC");
				}
		));
		
		$builder->add("bookGenres", "entity", array (
				"class" => "BlutchLibraryBundle:BookGenre",
				"property" => "description",
				"multiple" => true ,
				"query_builder" => function (EntityRepository $repository) {
					return $repository->createQueryBuilder("g")
						->orderBy("g.description", "ASC");
				}
		));
	}

	/**
	 *
	 * @param OptionsResolverInterface $resolver        	
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array (
				"data_class" => "Blutch\LibraryBundle\Entity\Book" 
		));
	}

	/**
	 *
	 * @return string
	 */
	public function getName() {
		return "blutch_librarybundle_book";
	}
}
