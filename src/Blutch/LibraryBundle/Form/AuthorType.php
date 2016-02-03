<?php

namespace Blutch\LibraryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AuthorType extends AbstractType {

	/**
	 *
	 * @param FormBuilderInterface $builder        	
	 * @param array $options        	
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add ("firstName", "text")
			->add ("lastName", "text")
			->add ("birthDate", "birthday")
			->add ("biography", "textarea")
			->add ("save", "submit");
	}

	/**
	 *
	 * @param OptionsResolverInterface $resolver        	
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults (array (
				"data_class" => "Blutch\LibraryBundle\Entity\Author" 
		));
	}

	/**
	 *
	 * @return string
	 */
	public function getName() {
		return "blutch_librarybundle_author";
	}
}
