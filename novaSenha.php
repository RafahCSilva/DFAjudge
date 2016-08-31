<?php
$token = 'ERRO';
if ( isset( $_GET[ 'token' ] ) ) {
  $token = $_GET[ 'token' ];
} else if ( isset( $_POST[ 'txt_novaSenha' ] ) && isset( $_POST[ 'token' ] ) ) {
  $senha = $_POST[ 'txt_novaSenha' ];
  $token = $_POST[ 'token' ];
  include_once 'private/DBconfig.php';
  include_once 'private/class.resetsenha.php';
  $reset = new RESETSENHA( $DB_con, $baseSITE );
  $reset->_novaSenha( $senha, $token );

  header( 'Location: login.php' );
  exit();

} else { // SEM TOKEN
  header( 'Location: login.php' );
  exit();
}


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <title>DFA judge - Definir Senha</title>
  <meta charset="UTF-8" />
  <meta name="description" content="DFA judge" />
  <link rel="icon" type="image/png" href="favicon_x16.png" sizes="16x16" />
  <link rel="icon" type="image/png" href="favicon_x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="favicon_x96.png" sizes="96x96" />
  <link rel="icon" type="image/png" href="favicon_x128.png" sizes="128x128" />
  <link rel="icon" type="image/png" href="favicon_x196.png" sizes="196x196" />
  <link rel="stylesheet" type="text/css" href="css/login.css" />
  <script type='text/javascript'>
    function checarSenhas () {
      var nvSenha  = document.getElementById( 'txt_novaSenha' ).value;
      var nvSenhaC = document.getElementById( 'txt_novaSenhaConfirma' ).value;
      if ( nvSenha != nvSenhaC ) {
        document.getElementById( 'senhaError' ).innerHTML = 'As senha são diferentes!';
        return false;
      } else if ( nvSenha.length < 6 ) {
        document.getElementById( 'senhaError' ).innerHTML = 'Senha muito curta! <br/> no mínimo 6 caracteres';
        return false;
      }
      return true;
    }
  </script>
</head>
<body>
  <div id="LOGAR">
    <!--  <h1> ICrafael<br>Nova Senha </h1>-->

    <form action="novaSenha.php" method="post" autocomplete="off">
      <table align="center" width="340px">
        <tr>
          <td colspan="2" width="340px">
            <img src="logos/LOGO_x340.png"
                 title="Sistema de auxílio na  &#10;aprendizagem da disciplina  &#10;Linguagens Formais e Autômatos"
                 alt="Sistema de auxílio na  &#10;aprendizagem da disciplina  &#10;Linguagens Formais e Autômatos">
          </td>
        </tr>
        <tr>
          <td width="30px"></td>
          <td width="310px"
              align="left"
              style="padding-left: 5px">
            Defina uma nova senha:
          </td>
        </tr>
        <td>
          <div class="login_pass"></div>
        </td>
        <td>
          <input type="password"
                 class="text"
                 name="txt_novaSenha"
                 id="txt_novaSenha"
                 placeholder="Digite a Senha"
                 style="width: 292px"
                 title="Digite sua senha"
                 required
                 autocomplete="off"
                 autofocus>
        </td>
        </tr>
        <tr>
          <td>
            <div class="login_pass"></div>
          </td>
          <td>
            <input type="password"
                   class="text"
                   name="txt_novaSenhaConfirma"
                   id="txt_novaSenhaConfirma"
                   placeholder="Redigite a Senha"
                   style="width: 292px"
                   title="Redigite sua senha"
                   autocomplete="off"
                   required>
          </td>
        </tr>

        <tr>
          <td>
            <input type="hidden"
                   name="token"
                   value="<?= $token ?>">

          </td>
          <td style="text-align: right; padding-right:10px">
            <button type="submit"
                    class="BTN_enviar"
                    onClick="return checarSenhas ()"
                    title="Clique aqui para enviar o e-mail">
              ENVIAR
            </button>
          </td>
        </tr>
        <tr>
          <td colspan="2"
              style="text-align: center;">
            <div id="senhaError"
                 style="font-weight: bold"></div>
          </td>
        </tr>
      </table>
    </form>
  </div>
  <div class="faixa_amarela">
    UFABC - Universidade Federal do ABC
  </div>
</body>
</html>