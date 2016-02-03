<?php

namespace Blutch\LibraryBundle\Services\Logger;

interface IChangeLoggerProcessor {
	
	public function log(ChangeLoggerEvent $event);
	
}

