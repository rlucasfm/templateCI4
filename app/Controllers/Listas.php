<?php namespace App\Controllers;

use App\Models\Lista;

class Listas extends BaseController
{
	public function index()
	{

	}

    public function novaLista(){
		$lista = new Lista();
		$cod_banco = session()->get('cod_banco');
		
        $data = [
			"title" 		=> "Gerencial - EudesRo",
			"name" 			=> session()->get('name'),
			"menuActiveID" 	=> "listas",
			"errorMsg" 		=> session()->get('errorMsg'),
			"successMsg" 	=> session()->get('successMsg'),
			"listas"		=> $lista->listarListas($cod_banco)						
		];

		echo view('templates/header', $data);
		echo view('Listas/novaLista', $data);
		echo view('templates/footer', $data);	
	}
	
	public function cadastrar()
	{
		$lista = new Lista();
		$cod_banco = session()->get('cod_banco');
		$lista_dados = $this->request->getPost();

		$lista_dados['id_banco'] = $cod_banco;		

		if($lista_dados['id'] == '0')
		{
			try {
				$response = $lista->cadastrar($lista_dados);
				session()->setFlashdata('successMsg', 'Lista cadastrada com sucesso');
			} catch (\Exception $err) {
				session()->setFlashdata('errorMsg', 'Houve um erro... '.$err->getMessage());
			}				
		}
		else
		{
			try {
				$response = $lista->atualizar($lista_dados);
				session()->setFlashdata('successMsg', 'Lista atualizada com sucesso');
			} catch (\Exception $err) {
				session()->setFlashdata('errorMsg', 'Houve um erro... '.$err->getMessage());
			}	
		}		

		
	}
	//--------------------------------------------------------------------

}
