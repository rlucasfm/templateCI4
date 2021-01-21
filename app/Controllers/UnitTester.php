<?php namespace App\Controllers;

use App\Models\Cliente;

class UnitTester extends BaseController
{
	public function index()
	{
		$cliente = new Cliente();
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('C:\Users\richard\Downloads\Modelo_Importação EudesRO_Novo_Modelo 2099.xls');
		$tabela = $spreadsheet->getActiveSheet()->toArray();
		
		echo "<pre>";
		var_dump($cliente->importar_api($tabela));
		echo "</pre>";		
		
	}

	//--------------------------------------------------------------------

}
