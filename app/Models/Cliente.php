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
                // Retirar espaços vazios
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

    public function cadastrar_cliente($cliente_array)
    {
        return "OK";
    }

}