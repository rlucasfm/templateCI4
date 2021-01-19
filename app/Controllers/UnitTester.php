<?php namespace App\Controllers;

use App\Models\Operacao;

class UnitTester extends BaseController
{
	public function index()
	{
		$cliente = new Operacao();
		// $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('C:\Users\richard\Downloads\Modelo_Importação EudesRO_Novo_Modelo 2099.xls');
		// $tabela = $spreadsheet->getActiveSheet()->toArray();
		$cliente->inserir_operacoes([			
			'nroperacao' 	=> '12',
			'nomeoperacao' 	=> 'Operação teste',
			'dtvencimento' 	=> '12/12/2021',
			'valoroperacao'	=> '1234,56',
			'observacoes'	=> 'O céu tá azul',
			'garantias'		=> 'Celta 2007 IPVA Pago'
		], 366);
		
	}

	//--------------------------------------------------------------------

}
