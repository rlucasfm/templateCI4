<?php namespace App\Models;

use CodeIgniter\Model;

class Lista extends Model
{

    protected $table            = 'listas';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id', 'id_banco', 'nome', 'tipoemail', 'diasvenc', 'horadisparo', 'mensagem'];
    protected $useTimestamps    = false;
    protected $skipValidation   = true;


    /// ************** MÉTODOS PARA O BANCO DE DADOS LOCAL ************** ///
    /** 
     * @method json
     * 
     * Busca uma lista de clientes da API
     */    
    public function listarListas($cod_banco)
    {        
        $listas = $this ->where('id_banco', $cod_banco)
                        ->orderBy('id', 'asc')
                        ->findAll();

        return($listas);
    }

    /**
     * @method string
     * 
     * Adiciona uma lista no Banco de dados, retorna um status de operação, indicando o sucesso ou não
     */
    public function cadastrar($dados)
    {
        $data_todb = [
            'id_banco'      => $dados['id_banco'],
            'nome'          => $dados['nomeLista'],
            'tipoemail'     => $dados['tipoEmail'],
            'diasvenc'      => $dados['diasVencimento'],
            'horadisparo'   => $dados['horaDisparo'],
            'mensagem'      => $dados['mensagemLista']
        ];
        
        try {
            $this->insert($data_todb);
            $sucesso = true;
        } catch (\Exception $err) {
            $sucesso = false;
            throw new \Exception($err->getMessage());
        }     

        if($sucesso){
            //return('Lista cadastrada com sucesso!');
            return("Lista cadastrada com sucesso");
        }
    }

    /**
     * @method string
     * 
     * Atualiza uma lista no Banco de dados, retorna um status de operação, indicando o sucesso ou não
     */
    public function atualizar($dados)
    {
        $id = $dados['id'];
        $data_todb = [
            'id_banco'      => $dados['id_banco'],
            'nome'          => $dados['nomeLista'],
            'tipoemail'     => $dados['tipoEmail'],
            'diasvenc'      => $dados['diasVencimento'],
            'horadisparo'   => $dados['horaDisparo'],
            'mensagem'      => $dados['mensagemLista']
        ];

        try {
            $this->update($id, $data_todb);
            $sucesso = true;
        } catch (\Exception $err) {
            $sucesso = false;
            throw new \Exception($err->getMessage());
        }

        if($sucesso){
            return("Lista atualizada com sucesso");
        }
    }

    /**
     * @method string
     * 
     * Exclui uma lista no Banco de dados, retorna um status de operação, indicando o sucesso ou não
     */
    public function apagar($id)
    {
        try {
            $this->delete($id);
            $sucesso = true;
        } catch (\Exception $err) {
            $sucesso = false;
            throw new \Exception($err->getMessage());
        }

        if($sucesso){
            return("Lista apagada com sucesso");
        }
    }

    /// ************** MÉTODOS PARA A API ************** ///
    /** 
     * @method json
     * 
     * Busca uma lista de clientes da API
     */    
    public function listarAPI($cod_banco)
    {     
        try {
            $response = $this->enviar_api('http://localhost:8077/datasnap/rest/TSM/', 'Lista/'.$cod_banco, 'GET');
            $lista_array = json_decode($response)->result;
            return($lista_array[0]);
        } catch (\Exception $err) {
            throw new \Exception($err->getMessage());
        }
                
    }

    /** 
     * @method json
     * 
     * Envia um pedido de cadastro de novas listas para a API
     */
    public function cadastrarAPI($dados)
    {
        $cod_banco = $dados['id_banco'];
        try {
            $response = $this->enviar_api('http://localhost:8077/datasnap/rest/TSM/', 'Lista/'.$cod_banco, 'POST', json_encode($dados));
            return json_decode($response)->result[0]->Response;
        } catch (\Exception $err) {
            throw new \Exception($err->getMessage());
        }         
        
    }

    /** 
     * @method json
     * 
     * Envia um pedido de atualização listas para a API
     */
    public function atualizarAPI($dados)
    {
        $id_lista = $dados['id'];
        try {
            $response = $this->enviar_api('http://localhost:8077/datasnap/rest/TSM/', 'Lista/'.$id_lista, 'PUT', json_encode($dados));
            return json_decode($response)->result[0]->Response;
        } catch (\Exception $err) {
            throw new \Exception($err->getMessage());
        } 
    }

    /** 
     * @method json
     * 
     * Envia um pedido de apagamento de listas para a API
     */
    public function apagarAPI($id_lista)
    {
        try {
            $response = $this->enviar_api('http://localhost:8077/datasnap/rest/TSM/', 'Lista/'.$id_lista, 'DELETE');            
            return json_decode($response)->result[0]->Response;
        } catch (\Exception $err) {
            throw new \Exception($err->getMessage());
        } 
    }

    /** 
     * @method json
     * 
     * Busca o relatório de envios da API
     */    
    public function relatorios($cod_banco)
    {     
        try {
            $response = $this->enviar_api('http://localhost:8077/datasnap/rest/TSM/', 'Relatorio/'.$cod_banco, 'GET');
            $lista_array = json_decode($response)->result;
            return($lista_array[0]);
        } catch (\Exception $err) {
            throw new \Exception($err->getMessage());
        }
                
    }

    /**
     * @method string
     * 
     * Responsável unicamente por enviar JSON para a API REST DataSnap
     */
    private function enviar_api($uri, $serverMethod, $requestType, $json = NULL)
    {
        // Instanciar cURL para comunicar com a API
        $options = [
            'baseURI' => $uri,
            'timeout'  => 200
        ];        
        $curl = \Config\Services::curlrequest($options); 

        // Configurações de cabeçalho para a API Delphi
        $reqConfig = [            
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]            
        ];        
        
        // Envia um POST para a API e retorna o resultado
        try {
            if(!empty($json)){
                $response = $curl   ->setBody($json)
                                    ->request($requestType, $serverMethod, $reqConfig);
            }else{
                $response = $curl->request($requestType, $serverMethod, $reqConfig); 
            }
            
            return $response->getBody();  
        } catch (\Exception $err) {
            throw new \Exception("Falha ao enviar para API: ".$err->getMessage());
        }    
    }

}