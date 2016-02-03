<?php

namespace Blutch\LibraryBundle\Services\Logger;

use Symfony\Component\HttpKernel\Log\LoggerInterface;

class ChangeLoggerProcessor implements IChangeLoggerProcessor {
	
	protected $logger;
	
	public function __construct(LoggerInterface $logger) {
		$this->logger = $logger;
	}
	
	public function log(ChangeLoggerEvent $event) {
		$this->logger->info($event->getType()." : ".ucfirst($event->getWhat()));
	}
	
}
