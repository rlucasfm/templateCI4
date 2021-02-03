<?php namespace App\Controllers; 

use App\Models\Cliente;
use App\Models\Operacao;

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

    // Página para visualização/edição de operações
    public function listarClientes()
    {
        $cliente = new Cliente();
        $cod_banco = session()->get('cod_banco');

        try {
            $operacoes = $cliente->listarAPI($cod_banco);
        } catch (\Exception $err) {
            session()->setFlashdata('errorMsg', $err->getMessage());
            return redirect()->to('/');
        }
    

        $data = [
            "title" => "Listar clientes - EudesRo",
            "name" => session()->get('name'),
            "menuActiveID" => "clientes",
            "errorMsg" => session()->get('errorMsg'),
            "successMsg" => session()->get('successMsg'),
            "operacoes" => $operacoes
		];

		echo view('templates/header', $data);
        echo view('clientes/listar', $data);
		echo view('templates/footer', $data);
    }

    // Receberá a tabela de importação e levará ao model
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

    // Receberá as informações do formulário e levará ao model
    public function cadastrarDB()
    {
        $cliente = new Cliente();

        $array_cliente = [];
        // Primeira linha com o nome das colunas conforme o DB
        $array_cliente[0] = [
            'nomedocliente','cpf/cnpj','endereco','bairro','cidade','cep','uf',
            'telefone1','telefone2', 'telefone3', 'telefone4', 'telefone5', 
            'telefone6','email','email2', 'nroperacao', 'nomeoperacao', 'dtvencimento',
            'valoroperacao', 'observacoes', 'garantias'
        ];

        // Carrega as informações enviadas no POST para o array
        $post = $this->request->getPost();
        if(!empty($post))
        {
            // Info Cliente
            $nomedocliente  = isset($post['nome'])      ? $post['nome'] : "null";
            $cpfcnpj        = isset($post['cpf'])       ? $post['cpf'] : "null";
            $endereco       = isset($post['endereco'])  ? $post['endereco'] : "null";
            $bairro         = isset($post['bairro'])    ? $post['bairro'] : "null";
            $cidade         = isset($post['cidade'])    ? $post['cidade'] : "null";
            $cep            = isset($post['cep'])       ? $post['cep'] : "null";
            $uf             = isset($post['uf'])        ? $post['uf'] : "null";
            $telefone1      = isset($post['telefone1']) ? $post['telefone1'] : "null";
            $telefone2      = isset($post['telefone2']) ? $post['telefone2'] : "null";
            $telefone3      = isset($post['telefone3']) ? $post['telefone3'] : "null";
            $telefone4      = isset($post['telefone4']) ? $post['telefone4'] : "null";
            $telefone5      = isset($post['telefone5']) ? $post['telefone5'] : "null";
            $telefone6      = isset($post['telefone6']) ? $post['telefone6'] : "null";
            $email1         = isset($post['email1'])    ? $post['email1'] : "null";
            $email2         = isset($post['email2'])    ? $post['email2'] : "null";

            // Info da Operação
            $numeroop       = isset($post['nrop'])              ? $post['nrop'] : "null";
            $tipoop         = isset($post['tipoop'])            ? $post['tipoop'] : "null";
            $datavenc       = isset($post['datavenc'])          ? $post['datavenc'] : "null";
            $valorop        = isset($post['valorop'])           ? $post['valorop'] : "null";
            $condnegociais  = isset($post['condnegociais'])     ? $post['condnegociais'] : "null";
            $garantiasreais = isset($post['garantiasreais'])    ? $post['garantiasreais'] : "null";

            $array_cliente[1] = [
                $nomedocliente, $cpfcnpj, $endereco, $bairro, $cidade, $cep, $uf, 
                $telefone1, $telefone2, $telefone3, $telefone4, $telefone5, 
                $telefone6, $email1, $email2, $numeroop, $tipoop, $datavenc,
                $valorop, $condnegociais, $garantiasreais
            ];

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

    // Receberá informações de atualização de operações e levará ao model
    public function atualizarOperacoes()
    {
        $operacao = new Operacao();

        $array_post = $this->request->getPost();
        $cod_banco = session()->get('cod_banco');
        
        try {            
            $operacao->alterar_operacoes($cod_banco, $array_post);    
            $sucesso = TRUE;            
        } catch (\Exception $err) {
            session()->setFlashdata('errorMsg', $err->getMessage());
            $sucesso = FALSE;
        }

        if($sucesso){
            session()->setFlashdata('successMsg', 'Cadastro enviado com sucesso');  
        }
    }
	//--------------------------------------------------------------------

}
