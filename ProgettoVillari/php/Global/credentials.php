<?php

class Credentials
{
	public static function isLoggedIn()
	{
		if(Session::isNull(SKeys::$loggedIn)) 
		{
			Debug::lo("Not logged in");
			return false;
		}


		$value = Session::get(SKeys::$loggedIn);

		return $value == true;

	}
	public static function tryLogin($mUser, $mPass)
	{
		$userID = Tables::$user->findIDByCredentials($mUser, $mPass);

		if($userID == -1)
		{
			Debug::lo("Invalid credentials: $mUser, $mPass");
		}
		else
		{
			Debug::lo("Login successful: $mUser, $mPass");
			Session::set(SKeys::$loggedIn, true);
			Session::set(SKeys::$userID, $userID);
		}
	}
}

?>