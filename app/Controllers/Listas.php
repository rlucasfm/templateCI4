<?php namespace App\Controllers;

use App\Models\Lista;

class Listas extends BaseController
{
	public function index()
	{

	}

	/**
	 * @method void
	 * 
	 * Controller responsável por manipular a página (view) de gerenciamento de listas
	 */
    public function novaLista(){
		$lista = new Lista();
		$cod_banco = session()->get('cod_banco');
		
        $data = [
			"title" 		=> "Gerencial - EudesRo",
			"name" 			=> session()->get('name'),
			"menuActiveID" 	=> "listas",
			"errorMsg" 		=> session()->get('errorMsg'),
			"successMsg" 	=> session()->get('successMsg'),
			"listas"		=> $lista->listarAPI($cod_banco)						
		];

		echo view('templates/header', $data);
		echo view('Listas/novaLista', $data);
		echo view('templates/footer', $data);	
	}
	
	/**
	 * @method void
	 * 
	 * Controller responsável por receber as informações do formulário e enviar para o model (Atualizar e Registrar)
	 */
	public function cadastrar()
	{
		$lista = new Lista();
		$cod_banco = session()->get('cod_banco');
		$lista_dados = $this->request->getPost();

		$lista_dados['id_banco'] = $cod_banco;		

		if($lista_dados['id'] == '0')
		{
			try {
				$response = $lista->cadastrarAPI($lista_dados);
				session()->setFlashdata('successMsg', $response);
			} catch (\Exception $err) {
				session()->setFlashdata('errorMsg', 'Houve um erro... '.$err->getMessage());
			}				
		}
		else
		{
			try {
				$response = $lista->atualizarAPI($lista_dados);
				session()->setFlashdata('successMsg', $response);
			} catch (\Exception $err) {
				session()->setFlashdata('errorMsg', 'Houve um erro... '.$err->getMessage());
			}	
		}		

		
	}

	/**
	 * @method void
	 * 
	 * Controller responsável por enviar para o model pedidos de exclusão
	 */
	public function apagar()
	{
		$lista 		= new Lista();		
		$id_lista 	= $this->request->getPost('id');

		try {
			$response = $lista->apagarAPI($id_lista);
			session()->setFlashdata('successMsg', $response);
		} catch (\Exception $err) {
			session()->setFlashdata('errorMsg', 'Houve um erro... '.$err->getMessage());
		}
	}
	//--------------------------------------------------------------------

}
