<?php

namespace Blutch\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BlutchUserBundle extends Bundle {

	public function getParent() {
		return "FOSUserBundle";
	}
}
