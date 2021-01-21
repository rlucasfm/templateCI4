<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\Operacao;

class Cliente extends Model
{
    protected $table            = 'cliente';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [ 'id', 'nomedocliente', 'cpf/cnpj', 'endereco', 'bairro', 'cidade', 'cep', 'uf', 
                                    'telefone', 'telefone1', 'telefone2', 'telefone3', 'telefone4', 'telefone5', 'telefone6', 
                                    'email', 'email1', 'email2', 'email3', 'email4', 'email5', 'email6',];
    protected $useTimestamps    = false;
    protected $skipValidation   = true;

    /** 
     * @method void 
     * 
     * Irá tratar uma tabela recebida como array e a salvar na base de dados
    */
    public function importar_array($tabela)
    {
        $operacao = new Operacao();
        // Identifica o nome das colunas, através da primeira linha...
        // ...e distribui em um novo array associativo.

        // Configuração de qual será a coluna chave, usada para verificar a 
        // unicidade dos registros
        $colunaChave = "cpf/cnpj";

        // Retorna a primeira linha, e a retira da variável parâmetro
        $chaves = array_shift($tabela);     
        $arr_tabela = [];

        // Array limpeza
        $from_str   = [' ', '&Ccedil;', '-', '&Atilde;', '&Otilde;', '.'];
        $to_str     = ['', 'c', '', 'a', 'o', ''];

        // Usa os valores das colunas da primeira linha como nome das colunas no DB
        // Itera sobre cara nome de coluna e o atribui aos valores das linhas
        $key_id = 0;
        foreach($chaves as $chave)
        {
            // Retirar espaços vazios e trocar ç por c
            $chave = strtolower(str_replace($from_str, $to_str, htmlentities($chave)));            

            $coluna = array_column($tabela, $key_id);
            $arr_tabela[$chave] = $coluna;            
            $key_id++;
        }

        // Itera cada registro, populando o array na tabela
        $reg_id = 0;
        foreach($arr_tabela[$colunaChave] as $regNome)
        {            
            $registro = [];
            foreach($chaves as $chave)
            {
                // Retirar espaços vazios e acentos
                $chave = strtolower(str_replace($from_str, $to_str, htmlentities($chave)));
                // Verificar se a coluna não é nula
                if(!empty($arr_tabela[$chave][$reg_id]) && $arr_tabela[$chave][$reg_id] != 'NULL')
                {
                    $registro[$chave] = $arr_tabela[$chave][$reg_id]; 
                }  
            }

            // Verifica a existência de registro igual
            $db_cliente = $this ->where($colunaChave, $regNome)
                                ->first();

            $temFalha = FALSE; // Para verificação do sucesso da operação no final
            if(!empty($db_cliente))
            {                
                // Se já existir, atualiza.                        
                try 
                {
                    // Registro já existe, e a coluna não é nula, então a atualizaremos
                    $this->update($db_cliente->id, $registro);                     
                } catch (\Exception $th) {
                    throw new \Exception("Falha ao salvar na base de dados: ".$th->getMessage());
                    $temFalha = TRUE;
                }
            }
            else
            {
                // Se não existir nome igual, cadastra no banco.                
                try 
                {
                    // Registro não existe, e a coluna não é nula, então o adicionaremos
                    $this->insert($registro);                     
                } catch (\Exception $th) {
                    throw new \Exception("Falha ao salvar na base de dados: ".$th->getMessage());
                    $temFalha = TRUE;
                }
            } 

            // Se o registro de cliente estiver atrelado a uma operação...
            $key_operacao = 'nroperacao';
            if(!empty($registro[$key_operacao]))
            {
                // Recolher o ID do registro feito
                $db_cliente = $this ->where($colunaChave, $regNome)
                                    ->first();
                try {
                    $operacao->inserir_operacoes($registro, $db_cliente->id, $key_operacao); 
                } catch (\Exception $th) {
                    throw new \Exception("Falha ao salvar as operações: ".$th->getMessage());
                    $temFalha = TRUE;
                }            
            }            

            $reg_id++;
        }



        // Se houve sucesso...
        if(!$temFalha)
        {
            session()->setFlashdata('successMsg', 'Cadastrado com sucesso');
        }
    }

    /**
     * @method string
     * 
     * Trata os valores de uma tabela importada do excel/form de cadastro para API
     */
    public function importar_api($array_cliente)
    {              
        $cod_banco = session()->get('cod_banco');

        // Retorna a primeira linha, e a retira da variável parâmetro
        $array_chaves = array_shift($array_cliente);
        $array_tabela = [];

        // Array limpeza
        $from_str   = [' ', '&Ccedil;', '-', '&Atilde;', '&Otilde;', '.'];
        $to_str     = ['', 'c', '', 'a', 'o', ''];

        
        // Transforma o array em um array associativo (Dicionário)
        for($i = 0; $i < count($array_cliente); $i++){
            $index = 0;
            foreach($array_chaves as $chave){
                $chave = strtolower(str_replace($from_str, $to_str, htmlentities($chave)));

                if(!empty($array_cliente[$i][$index]) && $array_cliente[$i][$index] != 'NULL')
                {
                    $array_tabela[$i][$chave] = $array_cliente[$i][$index];
                }
                else
                {
                    if($chave == 'uf'){
                        $array_tabela[$i][$chave] = "MA";
                    }
                    else{
                        $array_tabela[$i][$chave] = "NULL";
                    }                    
                } 
                
                $index++;
            }            
        } 

        try {
            $response = $this->enviar_api('http://localhost:8077/datasnap/rest/TSM/', 'Cliente/'.$cod_banco, json_encode($array_tabela));     
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
    private function enviar_api($uri, $postMethod, $json)
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
            $response = $curl   ->setBody($json)
                                ->request('POST', $postMethod, $reqConfig);
            return $response->getBody();  
        } catch (\Exception $err) {
            throw new \Exception("Falha ao enviar para API: ".$err->getMessage());
        }    
    }

}