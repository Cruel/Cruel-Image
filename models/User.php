<?php

class User extends fActiveRecord {

	protected function configure() {
		//
	}

	public function setPassword($password){
		if (!$password) return;
		$hash = fCryptography::hashPassword($password);
		parent::setPassword($hash);
	}

	public function checkPassword($password){
		$hash = $this->getPassword();
		return fCryptography::checkPasswordHash($password, $hash);
	}

}
