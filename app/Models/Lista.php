<?php namespace App\Models;

use CodeIgniter\Model;

class Lista extends Model
{

    protected $table            = 'listas';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id', 'id_banco', 'nome', 'tipoemail', 'diasvenc', 'horasdisparo', 'mensagem'];
    protected $useTimestamps    = false;
    protected $skipValidation   = true;

    /** 
     * @method json
     * 
     * Busca uma lista de clientes da API
     */    
    public function listarListas($cod_banco){        

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
            return('Lista cadastrada com sucesso!');
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
            'horadisparo'   => "TIME(".$dados['horaDisparo'].")",
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
            return('Lista cadastrada com sucesso!');
        }
    }


    /**
     * @method string
     * 
     * Responsável unicamente por enviar JSON para a API REST DataSnap
     */
    private function enviar_api($uri, $postMethod, $json, $requestType)
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
                                    ->request($requestType, $postMethod, $reqConfig);
            }else{
                $response = $curl->request($requestType, $postMethod, $reqConfig); 
            }
            
            return $response->getBody();  
        } catch (\Exception $err) {
            throw new \Exception("Falha ao enviar para API: ".$err->getMessage());
        }    
    }

}