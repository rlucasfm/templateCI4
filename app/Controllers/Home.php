<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$data = [
			"title" => "Template - CI4",
		];

		echo view('welcome_message', $data);
	}

	//--------------------------------------------------------------------

}
