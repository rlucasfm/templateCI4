<?php namespace App\Models;

use CodeIgniter\Model;

class Operacao extends Model
{
    protected $table            = 'operacoes';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id', 'id_cliente', 'nroperacao', 'nomeoperacao', 'dtvencimento', 'valoroperacao', 'observacoes', 'garantias'];
    protected $useTimestamps    = false;
    protected $skipValidation   = true;

    /**
     * @method void
     * 
     * Insere operações no banco de dados local (MySQL)
     */
    public function inserir_operacoes($registro, $id_cliente, $key='nroperacao')
    {        
        $registro['id_cliente'] = $id_cliente;
        // Verifica a existência da mesma operação já no banco de dados
        $operacao_ant = $this->where($key,$registro[$key])->first();

        if(!empty($operacao_ant))
        {
            $this->update($operacao_ant->id, $registro);            
        }
        else
        {
            $this->insert($registro);        
        }
    }

    /**
     * @method string
     * 
     * Recebe operações do controller e envia para a API
     */
    public function alterar_operacoes($cod_banco, $array_op)
    {
        try {
            $response = $this->enviar_api('http://localhost:8077/datasnap/rest/TSM/', 'Operacao/'.$cod_banco, 'POST', json_encode($array_op));                
            return $response;
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