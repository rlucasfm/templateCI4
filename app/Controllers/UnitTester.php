<?php namespace App\Controllers;

use App\Models\Cliente;

class UnitTester extends BaseController
{
	public function index()
	{
		$cliente = new Cliente();
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('C:\Users\richard\Documents\Pasta1.xlsx');
		$tabela = $spreadsheet->getActiveSheet()->toArray();
        $cliente->array_tratar($tabela);
	}

	//--------------------------------------------------------------------

}
