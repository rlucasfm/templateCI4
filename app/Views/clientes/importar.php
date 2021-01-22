<!-- Begin Page Content -->
<div class="container">
<div class='s-pre-con'>
    <img src="/static/img/loader.gif" alt="Loading cool animation">
</div>

<form action="upload" method="POST" enctype="multipart/form-data">    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-8">
                    <!-- <label for="tabela" class="form-label">Tabela para importação</label>                    
                    <input type="file" name="tabela" id="tabela" class="form-control-file">  -->
                    <input type="file" name="tabela" id="tabela" style="display: none;" />
                    <input type="button" value="Escolher arquivo..." onclick="document.getElementById('tabela').click();" class="mt-3"/>
                    <label for="tabela" class="form-label" id="tabela_label"></label>
                </div>
                <div class="col-sm-4 mt-2">
                    <button type="submit" class="btn btn-primary btn-lg" id="btnSubmit">Enviar</button>
                </div>
            </div>            
        </div>
    </div>
</form>                 

<script>
    $('#btnSubmit').on('click', (event) => {
        $('.s-pre-con').show();
    })
</script>
<script>
    const input = document.querySelector('#tabela');
    const excelimg = '<img src="/static/img/excel.png" alt="Excel icon for the tables" style="height:20px;width=20px;">';
    input.addEventListener('change', () => {
        $('#tabela_label').html(excelimg+' Arquivo: '+input.files[0].name);
    });
</script>

</div>
<!-- /.container -->