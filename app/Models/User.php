<?php namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [];
    protected $useTimestamps    = false;
    protected $skipValidation   = true;

    public function authUser($email, $password)
    {
        // Obter o usuário com o email dado
        $user = $this->where(['email' => $email])
                     ->first();
        // Se o usuário existir, verifica a senha
        if(!empty($user)){
            if(password_verify($password, $user->password_hash))
            {
                // Armazena as informações importantes na sessão
                $sessionData = [
                    "email" => $email,
                    "name" => $user->name,
                ];
    
                $session = session();
                $session->set($sessionData);
            }
            else
            {
                throw new \Exception('Senha incorretos');
            }
        }else{
            throw new \Exception('Email não cadastrado');
        }       
    }
}