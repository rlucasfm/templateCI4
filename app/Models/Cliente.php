<?php namespace App\Models;

use CodeIgniter\Model;

class Cliente extends Model
{
    protected $table            = 'cliente';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id', 'nome', 'telefone'];
    protected $useTimestamps    = false;
    protected $skipValidation   = true;

    public function importar_array($tabela)
    {

        // Organiza o Array para ser levado à base de dados
        $ids = array_column($tabela, 0);
        $nomes = array_column($tabela, 1);
        $telefones = array_column($tabela, 2);

        // Itera entre as células do array 
        $indice = 0;        
        foreach($ids as $id){
            $cliente = [
                'id' => $id,
                'nome' => $nomes[$indice],
                'telefone' => $telefones[$indice]
            ];

            $indice++;
            // Verifica a existência de registro igual
            $db_cliente = $this ->where('nome', $nomes[$indice-1])
                                ->findAll();

            $temFalha = FALSE;
            if(!empty($db_cliente))
            {                
                // Se já existir, atualiza.        
                try {
                    $this->update($id, array_slice($cliente, 1, 2)); 
                } catch (\Exception $th) {
                    throw new \Exception("Falha ao salvar na base de dados: ".$th->getMessage());
                    $temFalha = TRUE;
                }                   
            }
            else
            {
                // Se não existir nome igual, cadastra no banco.
                try {
                    $this->insert($cliente);  
                } catch (\Exception $th) {
                    throw new \Exception("Falha ao salvar na base de dados: ".$th->getMessage());
                    $temFalha = TRUE;
                }                            
            }            
        } // fim do Laço
        
        // Se houve sucesso...
        if(!$temFalha)
        {
            session()->setFlashdata('successMsg', 'Planilha importada com sucesso');
        }

    }

}