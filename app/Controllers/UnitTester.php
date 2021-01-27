<?php namespace App\Controllers;

use App\Models\Lista;

class UnitTester extends BaseController
{
	public function index()
	{
		$lista = new Lista();
		// $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('C:\Users\richard\Downloads\Modelo_Importação EudesRO_Novo_Modelo 2099.xls');
		// $tabela = $spreadsheet->getActiveSheet()->toArray();

		echo "<pre>";
		echo($lista->apagarAPI(2));
		echo "</pre>";		
		
	}

	//--------------------------------------------------------------------

}
