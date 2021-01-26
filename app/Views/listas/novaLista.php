<div class="container-fluid">

    <div class='s-pre-con'>
        <img src="/static/img/loader.gif" alt="Animação bacana de carregamento">
    </div>

    <div class="card">        
        <div class="card-body">
            <h5 class="card-title">Listas de SMS/EMAIL</h5>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalGerenciarLista" onclick="javascript:$('#idLista').val('0');">Criar nova lista</button> 
            <hr>                     
            <table class="table table-striped table-responsive w-100 d-block d-md-table">
                <thead>
                    <tr>                        
                        <th scope="col">Nome da lista</th>
                        <th scope="col">Tipo Email</th>
                        <th scope="col">Dias do vencimento</th>
                        <th scope="col">Hora do Disparo</th>
                    </tr>
                </thead>
                <tbody>                    
                    <?php foreach($listas as $lista): ?>                    
                        <tr>                                                     
                            <td><?= esc($lista->nome) ?></td>
                            <td><?= esc($lista->tipoemail) ?></td>
                            <td><?= esc($lista->diasvenc) ?></td>
                            <td><?= esc($lista->horadisparo) ?></td>
                            <td><button type="button" class="btn btn-success" id="btnEditar<?= esc($lista->id); ?>">Editar</button></td>
                        </tr>                        
                    <?php endforeach ?>
                </tbody>
            </table>              
        </div>
    </div>            
</div>

<!-- Modal GERENCIAR LISTA -->
<div class="modal fade" id="modalGerenciarLista" tabindex="-1" role="dialog" aria-labelledby="modalGerenciarListaHelp" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGerenciarListaHelp">Gerenciar lista</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="cadastroLista">    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nomeLista">Nome da lista</label>
                                <input type="text" class="form-control" name="nomeLista" id="nomeLista" aria-describedby="nomeListaHelp">
                                <small id="nomeListaHelp" class="form-text text-muted">Aqui vai o nome da sua lista</small>
                            </div>
                        </div>    
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="tipoEmail">Tipo de email</label>
                                <select class="form-control" name="tipoEmail" id="tipoEmail" aria-describedby="tipoEmailHelp">
                                    <option value="1">ÚLTIMA CHAMADA</option>
                                    <option value="2">Promoção de Natal</option>
                                </select>
                                <small id="tipoEmailHelp" class="form-text text-muted">Selecione o padrão de email que será enviado</small>
                            </div>
                        </div>                                                 
                    </div>  
                    <div class="row">
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="diasVencimento">Dias do vencimento</label>
                                    <input type="number" class="form-control" name="diasVencimento" id="diasVencimento" aria-describedby="diasVencimentoHelp">
                                    <small id="diasVencimentoHelp" class="form-text text-muted">Quantos dias antes ou depois do vencimento</small>
                                </div>
                            </div>   
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="horaDisparo">Hora do disparo</label>
                                    <input type="time" class="form-control" name="horaDisparo" id="horaDisparo" aria-describedby="horaDisparoHelp">
                                    <small id="horaDisparoHelp" class="form-text text-muted">Que horário a mensagem será enviada</small>
                                </div>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <textarea name="mensagemLista" class="form-control" id="mensagemLista" cols="30" rows="5">Escreva aqui o texto que será enviado para o cliente</textarea>
                            </div>
                        </div>                    
                    </div>
                <!-- </form> -->                                           
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <input type="hidden" name="idLista" id="idLista" value="0">
                <button type="submit" class="btn btn-primary">Salvar lista</button>
            </div>
                </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {

        <?php foreach($listas as $lista): ?>
        $("#btnEditar<?= esc($lista->id); ?>").on('click', () => {
            $('#modalGerenciarLista').modal('show');
            $('#nomeLista').val('<?= esc($lista->nome); ?>');
            $('#tipoEmail').val('<?= esc($lista->tipoemail); ?>').change();
            $('#diasVencimento').val(<?= esc($lista->diasvenc); ?>);
            $('#horaDisparo').val('<?= esc($lista->horadisparo); ?>');
            $('#mensagemLista').val('<?= esc($lista->mensagem); ?>');
            $('#idLista').val('<?= esc($lista->id); ?>');
        })
        <?php endforeach ?>

        $('#cadastroLista').submit((event) => {
            event.preventDefault();
            $('#modalGerenciarLista').modal('hide');
            $('.s-pre-con').show();
            
            const id                =  $('#idLista').val();
            const nomeLista         =  $('#nomeLista').val();
            const tipoEmail         =  $('#tipoEmail').val();
            const diasVencimento    =  $('#diasVencimento').val();
            const horaDisparo       =  $('#horaDisparo').val();
            const mensagemLista     =  $('#mensagemLista').val();

            $.ajax({
                type: "post",
                url: "/Listas/cadastrar",
                data: {id: id, nomeLista: nomeLista, tipoEmail: tipoEmail, diasVencimento: diasVencimento, horaDisparo: horaDisparo, mensagemLista: mensagemLista},                
                success: function(data){
                    $('.s-pre-con').hide(); 
                    location.reload(true);                   
                },
                error: function(){
                    $('.s-pre-con').hide();                      
                },
                timeout: 5000
            })
        })
    });
</script>
