<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$data = [
			"title" => "Gerencial - EudesRo"
		];

		echo view('templates/header', $data);
		echo view('dashboard', $data);
		echo view('templates/footer', $data);
	}

	//--------------------------------------------------------------------

}
