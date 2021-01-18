<?php namespace App\Models;

use CodeIgniter\Model;

class Cliente extends Model
{
    protected $table            = 'cliente';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id', 'nomedocliente', 'cpf/cnpj', 'endereco', 'bairro', 'cidade', 'cep', 'uf', 'telefone1', 'telefone2', 'email1', 'email2'];
    protected $useTimestamps    = false;
    protected $skipValidation   = true;

    /** 
     * @method void 
     * 
     * Irá tratar uma tabela recebida como array e a salvar na base de dados
    */
    public function importar_array($tabela)
    {
        // Identifica o nome das colunas, através da primeira linha...
        // ...e distribui em um novo array associativo.

        // Configuração de qual será a coluna chave, usada para verificar a 
        // unicidade dos registros
        $colunaChave = "cpf/cnpj";

        // Retorna a primeira linha, e a retira da variável parâmetro
        $chaves = array_shift($tabela);     
        $arr_tabela = [];

        // Usa os valores das colunas da primeira linha como nome das colunas no DB
        // Itera sobre cara nome de coluna e o atribui aos valores das linhas
        $key_id = 0;
        foreach($chaves as $chave)
        {
            // Retirar espaços vazios
            $chave = strtolower(str_replace("ç","c",str_replace(" ", "", $chave)));

            $coluna = array_column($tabela, $key_id);
            $arr_tabela[$chave] = $coluna;
            $key_id++;
        }

        // Itera cada registro, populando o array na tabela
        $reg_id = 0;
        foreach($arr_tabela[$colunaChave] as $regNome)
        {
            // Verifica a existência de registro igual
            $db_cliente = $this ->where($colunaChave, $regNome)
                                ->first();

            $temFalha = FALSE; // Para verificação do sucesso da operação no final
            if(!empty($db_cliente))
            {                
                $registro = [];
                // Se já existir, atualiza.        
                foreach($chaves as $chave)
                {
                    // Retirar espaços vazios
                    $chave = strtolower(str_replace("ç","c",str_replace(" ", "", $chave)));
                    // Verificar se a coluna não é nula
                    if(!empty($arr_tabela[$chave][$reg_id]) && $arr_tabela[$chave][$reg_id] != 'NULL')
                    {
                        $registro[$chave] = $arr_tabela[$chave][$reg_id]; 
                    }  
                }

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
                foreach($chaves as $chave)
                {
                    // Retirar espaços vazios
                    $chave = strtolower(str_replace("ç","c",str_replace(" ", "", $chave)));

                    // Verificar se a coluna não é nula
                    if(!empty($arr_tabela[$chave][$reg_id]) && $arr_tabela[$chave][$reg_id] != 'NULL')
                    {
                        $registro[$chave] = $arr_tabela[$chave][$reg_id]; 
                    }                    
                } 
                
                try 
                {
                    // Registro não existe, e a coluna não é nula, então o adicionaremos
                    $this->insert($registro);  
                } catch (\Exception $th) {
                    throw new \Exception("Falha ao salvar na base de dados: ".$th->getMessage());
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

    public function cadastrar_cliente($cliente_array)
    {
        return "OK";
    }

}