<h3>Upload File</h3>

<!-- O tipo de encoding de dados, enctype, DEVE ser especificado abaixo -->
<form action="<?php $link;?>" method="post" enctype="multipart/form-data">
    <!-- MAX_FILE_SIZE deve preceder o campo input -->
    <!-- O Nome do elemento input determina o nome da array $_FILES -->
    Enviar esse arquivo: <input name="userfile" type="file" />
    <br><br>
    <input type="submit" value="Enviar arquivo" />
    <br>
    <input type="checkbox" name="dd1">Authors
</form>

<?php echo date("d/m/Y H:i:s");?>
