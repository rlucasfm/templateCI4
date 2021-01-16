<?php namespace App\Controllers;

use App\Models\User;

class UnitTester extends BaseController
{
	public function index()
	{
        $userModel = new User();
        $userModel->authUser("123@456","123");
	}

	//--------------------------------------------------------------------

}
