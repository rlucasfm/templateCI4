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
}