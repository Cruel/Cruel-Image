<?php

class User extends fActiveRecord {

	protected function configure() {
		//
	}

	public function hashedPassword($password){
		if (!$password) return;
		return fCryptography::hashPassword($password);
	}

	public function checkPassword($password){
		$hash = $this->getPassword();
		return fCryptography::checkPasswordHash($password, $hash);
	}

}
