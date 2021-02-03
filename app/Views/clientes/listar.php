<div class="container-fluid">
    <div class='s-pre-con'>
        <img src="/static/img/loader.gif" alt="Animação bacana de carregamento">
    </div>

    <div class="card">
        <div class="card-body">
        <div class="row">
            <div class="col">
                <h5 class="card-title">Lista de Operações</h5>
            </div>
            <div class="col text-right">
                <button type="button" class="btn btn-success mb-3 mt-0" onclick="editBtn()">Editar selecionados</button>
            </div>                        
        </div>        
        <table class="table table-striped table-responsive w-100 d-block d-md-table">
            <thead>
                <tr>      
                    <th scope="col"><input type="checkbox" id="allBoxes"></th>                  
                    <th scope="col">Operação</th>
                    <th scope="col">Data de Vencimento</th>
                    <th scope="col">Valor nominal</th>
                    <th scope="col">Nome do Cliente</th>
                    <th scope="col">CPF do Cliente</th>
                </tr>
            </thead>
            <tbody id="table-body"></tbody>
        </table> 
        <div class="container text-center">
            <div id="pagination-wrapper"></div>
        </div> 
        </div>
    </div>

</div>

<div class="toast" role="alert" id="toastEditOp" aria-live="assertive" aria-atomic="true" data-autohide="false" style="position: absolute; top: 15px; left:50%; z-index:10">
    <div class="toast-header">
        <strong class="mr-auto">Nenhuma operação selecionada</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        Por favor selecione uma operação para continuar
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalEditOp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar operações</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <small class='text-muted'><div class="numberSelect"></div></small>
                <hr>
                <div class="row">
                    <div class="col">
                        <label for="tipoopNova">Tipo da operação</label>
                        <input type="text" name="tipoopNova" id="tipoopNova" class="form-control">                        
                        <small class="text-muted">Qual o novo tipo destas operações?</small>              
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="datavencNova">Data de vencimento</label>
                        <input type="date" name="datavencNova" id="datavencNova" class="form-control">                        
                        <small class="text-muted">Qual a nova data de vencimento para estas operações?</small>              
                    </div>
                    <div class="col-6">
                        <label for="valornominalNova">Valor nominal</label>
                        <input type="number" min="0" step="0.01" name="valornominalNova" id="valornominalNova" class="form-control">                        
                        <small class="text-muted">Qual o novo valor nominal?</small>              
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmationSave">Salvar alterações</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmationSave" tabindex="-1" aria-labelledby="confirmationSaveLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationSaveLabel">Tem certeza?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">      
        <p>Tem certeza que quer alterar a(s) <b class="numberSelect"></b>? </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnSalvarVenc" data-dismiss="modal">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<script>
// Informações do Back-end para o front-end
let tableData = [
    <?php foreach($operacoes as $operacao): ?>
        {
            'id': '<?= esc($operacao->nroperacao) ?>nrc<?= esc($operacao->remessa) ?>nrc<?= esc($operacao->cliente) ?>',
            'check': '',
            'operacao': '<?= esc($operacao->tipooperacao) ?>',
            'datavencto': '<?= esc($operacao->datavencto) ?>',
            'valornominal': '<?= esc($operacao->valornominal) ?>',
            'nome': '<?= esc($operacao->nome) ?>', 
            'cpf': '<?= esc($operacao->cpf) ?>'
        },
    <?php endforeach ?>
]

var state = {
    'querySet': tableData,
    'page': 1,
    'rows': 10,
    'window': 3,
    'checked': false
}

buildTable();

// Funcionamento da paginação
function pagination(querySet, page, rows){
    let trimStart = (page-1)*rows;
    let trimEnd = trimStart+rows;

    let trimmedData = querySet.slice(trimStart, trimEnd);
    let pages = Math.ceil(querySet.length / rows);

    return {
        'querySet': trimmedData,
        'pages': pages
    }
}

// Tratamento dinâmico dos botões de paginação
function pageButtons(pages){
    let wrapper = document.getElementById('pagination-wrapper');
    wrapper.innerHTML = '';

    let maxLeft = (state.page - Math.floor(state.window /2));
    let maxRight = (state.page + Math.floor(state.window /2));

    if(maxLeft < 1){
        maxLeft = 1;
        maxRight = state.window
    }

    if(maxRight > pages){
        maxLeft = pages - (state.window - 1);
        maxRight = pages

        if(maxLeft < 1){
            maxLeft = 1
        }
    }

    for(var page = maxLeft; page <= maxRight; page++){
        wrapper.innerHTML += '<button value='+page+' class="page btn btn-sm btn-info mr-1">'+page+'</button>'; 
    }

    if(state.page != 1){
        wrapper.innerHTML = '<button value=1 class="page btn btn-sm btn-info mr-1">&#171; Primeira</button>' + wrapper.innerHTML; 
    }

    if(state.page != pages){
        wrapper.innerHTML += '<button value='+pages+' class="page btn btn-sm btn-info mr-1">Última &#187;</button>'; 
    }

    $('.page').on('click', function() {        
        $('#table-body').empty();
        
        state.page = Number($(this).val());
        buildTable();
    })
}

// Passar as informações para a tabela
function buildTable(){
    let table = $('#table-body');
    let data = pagination(state.querySet, state.page, state.rows);

    tabList = data.querySet;

    for (var i = 1 in tabList) {
        //Keep in mind we are using "Template Litterals to create rows"
        var row =  `<tr>
                    <td><input type="checkbox" id="${tabList[i].id}" class="checkSelect" ${tabList[i].check} onclick="checkHandler('${tabList[i].id}')"></td>  
                    <td>${tabList[i].operacao}</td>
                    <td>${tabList[i].datavencto}</td>
                    <td>${tabList[i].valornominal}</td>
                    <td>${tabList[i].nome}</td>
                    <td>${tabList[i].cpf}</td>
                    `
        table.append(row)
    }

    pageButtons(data.pages);
}

// Atualização em tempo real de checkboxes marcadas
function checkHandler(checkId){
    let checkStatus = tableData.find(x => x.id == checkId).check;    

    if(checkStatus == "checked"){
        tableData.find(x => x.id == checkId).check = '';
    }else{
        tableData.find(x => x.id == checkId).check = 'checked';
    }
}

// Checkbox para marcar todas
$('#allBoxes').on('click', function() {         
    if(state.check){
        tableData.forEach((obj) => {
            obj.check = '';
        })
        state.check = !state.check;
    }else{
        tableData.forEach((obj) => {
            obj.check = 'checked';
        })
        state.check = !state.check;
    }
    $('.checkSelect').prop('checked', state.check);
})

// Botão de editar
function editBtn(){
    let toedit_op = [];

    // Resetar inputs
    $('input[class="form-control"]').val('');

    // Verificar os registros selecionados
    tableData.forEach((operation) => {
        if(operation.check == 'checked'){
            toedit_op.push(operation);
        }
    });

    // Nenhum registro selecionado
    if(toedit_op.length < 1){
        $('#toastEditOp').toast('show');
    }else{
        $('#modalEditOp').modal('show');
        if(toedit_op.length == 1) $('.numberSelect').html(`${toedit_op.length} operação selecionada`);
        else $('.numberSelect').html(`${toedit_op.length} operações selecionadas`);
    }

    // Preparação do objeto para salvar
    $('#btnSalvarVenc').unbind('click').on('click', function(){
        toedit_op.forEach((opedit) => {            
            let data = formatDate(opedit.datavencto);
            opedit.datavencto   = $('#datavencNova').val()      != '' ? $('#datavencNova').val()    : data;
            opedit.operacao     = $('#tipoopNova').val()        != '' ? $('#tipoopNova').val()      : opedit.operacao;
            opedit.valornominal = $('#valornominalNova').val()  != '' ? $('#valornominalNova').val(): opedit.valornominal;                    
        })   
        // Envia as informações para o controller 
        $.ajax({
            type: 'post',
            url: '/clientes/atualizarOperacoes',
            data: {ops_json: toedit_op},
            success: function(data){
                document.location.reload();
            }
        })
    })
}

function formatDate(data_in){
    if(data_in.match(/\d{2}\/\d{2}\/\d{4}/)){
        let dia  = data_in.split("/")[0];
        let mes  = data_in.split("/")[1];
        let ano  = data_in.split("/")[2];

        return ano + '-' + ("0"+mes).slice(-2) + '-' + ("0"+dia).slice(-2);
    }else{
        return data_in;
    }

}
</script>
