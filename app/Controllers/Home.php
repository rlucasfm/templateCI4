<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$data = [
			"title" => "Gerencial - EudesRo",
			"name" => session()->get('name'),
			"menuActiveID" => "dash",
			"errorMsg" => session()->get('errorMsg'),
			"successMsg" => session()->get('successMsg')
		];

		echo view('templates/header', $data);
		echo view('dashboard', $data);
		echo view('templates/footer', $data);
	}

	//--------------------------------------------------------------------

}
