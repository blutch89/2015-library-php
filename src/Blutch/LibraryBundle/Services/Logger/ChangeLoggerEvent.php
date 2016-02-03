<?php

namespace Blutch\LibraryBundle\Services\Logger;

use Symfony\Component\EventDispatcher\Event;

class ChangeLoggerEvent extends Event {

	const ADD = "Ajout";
	const EDIT = "Modification";
	const DELETE = "Suppression";
	
	protected $type;
	protected $what;

	public function __construct($type, $what) {
		$this->type = $type;
		$this->what = $what;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getWhat() {
		return $this->what;
	}
	
}