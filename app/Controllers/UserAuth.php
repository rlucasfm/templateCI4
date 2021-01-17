<?php namespace App\Controllers;

use App\Models\User;

class UserAuth extends BaseController
{
	public function index()
	{
        echo view('login');   
    }

    public function login()
    {
        $userModel = new User();
        // Richard @ Verifica se está sendo uma requisição GET (Acessando a URL) ou POST (Submetendo o form)
        if(!($this->request->getMethod() == "get"))
        {
            // Validando se os campos não foram enviados vazios
            if("" !== $this->request->getPost('email') && "" !== $this->request->getPost('password'))
            {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                
                // Funções de login do Modelo
                try {
                    $userModel->authUser($email, $password);
                    $loginSuccess = TRUE;
                } catch(\Exception $err) {
                    // Redireciona para a página de login com a mensagem de erro
                    $data = [
                        "error" => $err->getMessage()
                    ];
                    $loginSuccess = FALSE;                
                    echo view('login', $data);                
                } 
                if($loginSuccess)
                {
                    // Redireciona para a página principal
                    return redirect()->to('/');
                }        
            }
        }
        else
        {
            // Caso a requisição seja GET...
            // Verifica se já está logado
            if(! empty(session()->get('email')))
            {
                // Redireciona para a página principal
                return redirect()->to('/');
            }
            else
            {
                // Renderiza para a página de login sem mensagens
                echo view('login'); 
            }  
        }        
    }   
    
    public function logout()
    {
        //Destroe a sessão e retorna para a página de login
        session()->destroy();
        return redirect()->to('/login');
    }
	//--------------------------------------------------------------------

}
