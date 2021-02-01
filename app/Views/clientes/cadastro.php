<!-- Begin Page Content -->
<div class="container">
<div class='s-pre-con'>
    <img src="/static/img/loader.gif" alt="Loading cool animation">
</div>

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
                        <label for="nome">CPF / CNPJ</label>
                        <input type="text" name="cpf" id="cpf" class="form-control">
                        <small>Apenas números</small>
                    </div>
                </div>                              
            </div> 
            <hr>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="endereco">Endereço</label>
                        <input type="text" name="endereco" id="endereco" class="form-control">
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="bairro">Bairro</label>
                    <input type="text" name="bairro" id="bairro" class="form-control">
                </div>
                <div class="col-sm-3">
                    <label for="cidade">Cidade</label>
                    <input type="text" name="cidade" id="cidade" class="form-control">
                </div>
                <div class="col-sm-2">
                    <label for="cep">CEP</label>
                    <input type="text" name="cep" id="cep" class="form-control" placeholder="00000-000">
                </div>
            </div>  
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="uf">UF</label>
                        <input type="text" name="uf" id="uf" class="form-control">
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="telefone1">Telefone 1</label>
                        <input type="text" name="telefone1" id="telefone1" class="form-control" placeholder="(99) 99999-9999">
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <label for="telefone2">Telefone 2</label>
                        <input type="text" name="telefone2" id="telefone2" class="form-control" placeholder="(99) 99999-9999">
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="telefone3">Telefone 3</label>
                        <input type="text" name="telefone3" id="telefone3" class="form-control" placeholder="(99) 99999-9999">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="telefone4">Telefone 4</label>
                        <input type="text" name="telefone4" id="telefone4" class="form-control" placeholder="(99) 99999-9999">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="telefone5">Telefone 5</label>
                        <input type="text" name="telefone5" id="telefone5" class="form-control" placeholder="(99) 99999-9999">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="telefone6">Telefone 6</label>
                        <input type="text" name="telefone6" id="telefone6" class="form-control" placeholder="(99) 99999-9999">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="email1">Email 1</label>
                        <input type="email" name="email1" id="email1" class="form-control" placeholder="email@dominio.com">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="email2">Email 2</label>
                        <input type="email" name="email2" id="email2" class="form-control" placeholder="email@dominio.com">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="nrop">Número da Operação</label>
                        <input type="text" name="nrop" id="nrop" class="form-control" placeholder="00000">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="tipoop">Tipo da Operação</label>
                        <input type="text" name="tipoop" id="tipoop" class="form-control" placeholder="MENSALIDADE">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="datavenc">Data de Vencimento</label>
                        <input type="date" name="datavenc" id="datavenc" class="form-control">
                    </div>
                </div>
            </div>   
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="valorop">Valor Nominal</label>
                        <input type="number" name="valorop" id="valorop" class="form-control" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="condnegociais">Nome do aluno</label>
                        <input type="text" name="condnegociais" id="condnegociais" class="form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="garantiasreais">Ano/Série</label>
                        <input type="text" name="garantiasreais" id="garantiasreais" class="form-control">
                    </div>
                </div>
            </div>     
            <button type="submit" class="btn btn-primary mt-4" id="btnSubmit">Cadastrar</button>
        </div>
    </div>
</form>                 

</div>
<script>
    $('#btnSubmit').on('click', (event) => {
        $('.s-pre-con').show();
    })
</script>
<script>
    $('#cpf').attr("onkeypress", "mascara(this, aplicarCpf2)");
    $('#cpf').attr("maxlength", "11");
</script>
<!-- /.container -->