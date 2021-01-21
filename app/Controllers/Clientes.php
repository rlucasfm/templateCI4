<?php namespace App\Controllers; 

use App\Models\Cliente;

class Clientes extends BaseController
{
	public function index()
	{
        return redirect()->to('/');
    }
    
    // Página para importação de tabelas
    public function importar()
    {
        $data = [
            "title" => "Importação de Tabelas - EudesRo",
            "name" => session()->get('name'),
            "menuActiveID" => "clientes",
            "errorMsg" => session()->get('errorMsg'),
            "successMsg" => session()->get('successMsg')
		];

		echo view('templates/header', $data);
        echo view('clientes/importar', $data);
		echo view('templates/footer', $data);
    }

    // Receberá a tabela e levará ao model
    public function upload()
    {        
        
        $cliente = new Cliente();

        // Carrega a tabela enviada no POST
        $tabela = $this->request->getFile('tabela');
        
        // Verifica se não houveram falhas no upload
        if(! $tabela->isValid())
        {
            if(!empty($file))
            {
                session()->setFlashdata('errorMsg','Falha ao carregar: '.$file->getErrorString().'('.$file->getError().')');
            }
            else
            {
                session()->setFlashdata('errorMsg',"Você não carregou nenhuma tabela");
            }
            
        }
        else
        {
            // Carrega a tabela para um array
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tabela);
                $array = $spreadsheet->getActiveSheet()->toArray();
            } catch (\Exception $err) {
                session()->setFlashdata('errorMsg',(($err->getCode() == 0) ? 'Este formato não é suportado, tente XLXS ou CSV' : 'Código: '.$err->getCode()));
            }            

            // Envia a tabela para o modelo, que irá levá-la ao DB
            if(isset($array)){
                try {
                    $cliente->importar_api($array);
                    session()->setFlashdata('successMsg', 'Tabela importado com sucesso');
                } catch (\Exception $err) {
                    session()->setFlashdata('errorMsg', $err->getMessage());
                }                
            }
            
        }

        return redirect()->to('/clientes/importar');
    }

    // Página para o cadastro manual
    public function cadastro()
    {
        $data = [
            "title" => "Cadastro manual de clientes - EudesRo",
            "name" => session()->get('name'),
            "menuActiveID" => "clientes",
            "errorMsg" => session()->get('errorMsg'),
            "successMsg" => session()->get('successMsg')
		];

		echo view('templates/header', $data);
        echo view('clientes/cadastro', $data);
		echo view('templates/footer', $data);  
    }

    // Receberá as informações do formulário e levará ao model
    public function cadastrarDB()
    {
        $cliente = new Cliente();

        $array_cliente = [];
        // Primeira linha com o nome das colunas conforme o DB
        $array_cliente[0] = ['nomedocliente','cpf/cnpj','endereco','bairro','cidade','cep','uf','telefone1','telefone2','email','email2'];

        // Carrega as informações enviadas no POST para o array
        $post = $this->request->getPost();
        if(!empty($post))
        {
            $nomedocliente  = $post['nome'];
            $cpfcnpj        = $post['cpf'];
            $endereco       = $post['endereco'];
            $bairro         = $post['bairro'];
            $cidade         = $post['cidade'];
            $cep            = $post['cep'];
            $uf             = $post['uf'];
            $telefone1      = $post['telefone1'];
            $telefone2      = $post['telefone2'];
            $email1         = $post['email1'];
            $email2         = $post['email2'];

            $array_cliente[1] = [$nomedocliente, $cpfcnpj, $endereco, $bairro, $cidade, $cep, $uf, $telefone1, $telefone2, $email1, $email2];

            try {
                //$resposta = $cliente->importar_array($array_cliente);
                $cliente->importar_api($array_cliente);    
                $sucesso = TRUE;            
            } catch (\Exception $err) {
                session()->setFlashdata('errorMsg', $err->getMessage());
                $sucesso = FALSE;
            }

            if($sucesso){
                session()->setFlashdata('successMsg', 'Cadastro enviado com sucesso');  
            }

            return redirect()->to('/clientes/cadastro');
        }            
    }
	//--------------------------------------------------------------------

}
