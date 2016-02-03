<?php

namespace Blutch\LibraryBundle\Services\Logger;

class ChangeLoggerListener {
	
	protected $processor;
	
	public function __construct(IChangeLoggerProcessor $processor) {
		$this->processor = $processor;
	}
	
	public function doLog(ChangeLoggerEvent $event) {
		$this->processor->log($event);
	}
	
}
