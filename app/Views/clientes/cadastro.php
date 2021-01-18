<!-- Begin Page Content -->
<div class="container">

<form action="cadastrarDB" method="POST">    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" name="nome" id="nome" aria-describedby="nomeHelp">
                        <small id="nomeHelp" class="form-text text-muted">Este é o nome do cliente</small>
                    </div>
                </div>  
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="nome">Telefone</label>
                        <input type="text" name="telefone" id="telefone" class="form-control">
                    </div>
                </div>                              
            </div>            
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </div>
    </div>
</form>                 

</div>
<!-- /.container -->