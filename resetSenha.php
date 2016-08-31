<?php
$recebeu  = false;
$emailTxt = '';
if ( isset( $_GET[ 'email' ] ) ) {
  $emailTxt = $_GET[ 'email' ];
  $recebeu  = true;
  include_once 'private/DBconfig.php';
  include_once 'private/class.resetsenha.php';
  $reset = new RESETSENHA( $DB_con, $baseSITE );
  $reset->_resetSenha( $emailTxt );

  header( 'Location: login.php' );
  exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <title>DFA judge - Recuperar Senha</title>
  <meta charset="UTF-8" />
  <meta name="description" content="DFA judge" />
  <link rel="icon" type="image/png" href="favicon_x16.png" sizes="16x16" />
  <link rel="icon" type="image/png" href="favicon_x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="favicon_x96.png" sizes="96x96" />
  <link rel="icon" type="image/png" href="favicon_x128.png" sizes="128x128" />
  <link rel="icon" type="image/png" href="favicon_x196.png" sizes="196x196" />
  <link rel="stylesheet" type="text/css" href="css/login.css" />
</head>

<body>

  <div id="LOGAR">
    <div style="position: absolute;
           float: left;
           left: 0;
           top: 0;
           width: 48px;
           height: 48px;
           z-index: 100; ">
      <a href="login.php">
        <img src="img/voltar_b.png" />
      </a>
    </div>
    <form action="resetSenha.php" method="get">
      <table align="center" width="340px">
        <tr>
          <td colspan="2" width="340px">
            <img src="logos/LOGO_x340.png"
            title="Sistema de auxílio na  &#10;aprendizagem da disciplina  &#10;Linguagens Formais e Autômatos"
            alt="Sistema de auxílio na  &#10;aprendizagem da disciplina  &#10;Linguagens Formais e Autômatos">
          </td>
        </tr>
        <tr>
          <td colspan="2" align="left" width="340px" style="padding-left: 5px">
            Enviar requisição para redefinir senha
          </td>
        </tr>
        <tr>
          <td width="30px">
            <div class="login_email"></div>
          </td>
          <td width="310px">
            <input type="email"
                   class="text"
                   title="Digite o seu e-mail para ser enviado requisição para a redefinição da sua senha."
                   placeholder="Digite o seu e-mail"
                   name="email"
                   style="width: 292px"
                   value="<?= $emailTxt; ?>"
                   required
                   autofocus></td>
        </tr>

        <tr>
          <td></td>
          <td style="text-align: right; padding-right:10px">
            <button type="submit" class="BTN_enviar" title="Clique aqui para enviar o e-mail">ENVIAR
            </button>
          </td>
        </tr>
        <?php if ( $recebeu ) { ?>
          <tr>
            <td colspan="2" style="text-align: center">
              <div id="resetOK">E-mail enviado com sucesso! <br /> Cheque seu e-mail</div>
            </td>
          </tr>
        <?php } ?>
      </table>
    </form>
  </div>
  <div class="faixa_amarela">
    UFABC - Universidade Federal do ABC
  </div>
</body>
</html>