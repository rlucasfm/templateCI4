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
                        <th scope="col">Tipo de Campanha</th>
                        <th scope="col">Dias do vencimento</th>
                        <th scope="col">Hora do Disparo</th>
                    </tr>
                </thead>
                <tbody>                    
                    <?php foreach($listas as $lista): ?>                    
                        <tr>                                                     
                            <td><?= esc($lista->nome) ?></td>
                            <td style="text-transform: uppercase"><?= esc($lista->tipocampanha) ?></td>
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
                                <input type="text" class="form-control" name="nomeLista" id="nomeLista" value="nome da lista" aria-describedby="nomeListaHelp">
                                <small id="nomeListaHelp" class="form-text text-muted">Aqui vai o nome da sua lista</small>
                            </div>
                        </div>    
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="diasVencimento">Dias do vencimento</label>
                                <input type="number" class="form-control" name="diasVencimento" id="diasVencimento" value="-7" aria-describedby="diasVencimentoHelp">
                                <small id="diasVencimentoHelp" class="form-text text-muted">Quantos dias antes ou depois do vencimento</small>
                            </div>
                        </div>   
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="horaDisparo">Hora do disparo</label>
                                <input type="time" class="form-control" name="horaDisparo" id="horaDisparo" value="09:00" step="600" value aria-describedby="horaDisparoHelp">
                                <small id="horaDisparoHelp" class="form-text text-muted">Que horário a mensagem será enviada</small>
                            </div>
                        </div>                                                                         
                    </div> 
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="tipoCampanha">Escolha por onde a campanha será enviada</label>
                                <select name="tipoCampanha" id="tipoCampanha" class="form-control" aria-describedby="tipoCampanhaHelp">
                                    <option value="sms">SMS</option>
                                    <option value="email">E-mail</option>
                                </select>
                                <small id="tipoCampanhaHelp" class="form-text text-muted">Você pode escolher campanhas por email ou por sms</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" id="tipoEmailGroup">
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
                        <div class="col-sm-12">
                            <div class="form-group" id="mensagemListaGroup">
                                <textarea name="mensagemLista" class="form-control" id="mensagemLista" cols="30" rows="5">Escreva aqui o texto do SMS que será enviado para o cliente</textarea>
                            </div>
                        </div>                    
                    </div>
                <!-- </form> -->                                           
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-dark mr-auto" data-toggle="modal" data-target="#modalConfirmação">Apagar lista</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <input type="hidden" name="idLista" id="idLista" value="0">
                <button type="submit" class="btn btn-primary">Salvar lista</button>
            </div>
                </form>
        </div>
    </div>
</div>

<!-- MODAL CONFIRMAÇÃO -->
<div class="modal fade" id="modalConfirmação" tabindex="-2" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Você tem certeza?</h5>
        <button type="button" class="close" data-dismiss="#modalConfirmação" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Tem certeza que pretende apagar esta lista?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="apagarLista">Confirmar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
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
            $('#tipoCampanha').val('<?= esc($lista->tipocampanha); ?>').change();
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
            const tipoCampanha      =  $('#tipoCampanha').val();

            $.ajax({
                type: "post",
                url: "/Listas/cadastrar",
                data: {id: id, nomeLista: nomeLista, tipoEmail: tipoEmail, diasVencimento: diasVencimento, horaDisparo: horaDisparo, mensagemLista: mensagemLista, tipoCampanha: tipoCampanha},                
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

        $('#apagarLista').on("click", () => {
            $('#modalGerenciarLista').modal('hide');
            $('#modalConfirmação').modal('hide');
            $('.s-pre-con').show();
            
            const id = $('#idLista').val();

            $.ajax({
                type: "post",
                url: "/Listas/apagar",
                data: {id: id},
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
        
        let tipoCampanhaPre = $('#tipoCampanha').val();
        if(tipoCampanhaPre == 'email'){
                $('#tipoEmailGroup').show();
                $('#mensagemListaGroup').hide();
            }else{
                $('#tipoEmailGroup').hide();
                $('#mensagemListaGroup').show();
            }
        
        $('#tipoCampanha').on("change", () => {
            const tipoCampanha = $('#tipoCampanha').val();

            if(tipoCampanha == 'email'){
                $('#tipoEmailGroup').show();
                $('#mensagemListaGroup').hide();
            }else{
                $('#tipoEmailGroup').hide();
                $('#mensagemListaGroup').show();
            }
        })
    });
</script>
