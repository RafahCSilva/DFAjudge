<?php

if ( isset( $_POST[ 'logar' ] ) ) { // viu q eh um submit
  $email = $_POST[ "txt_email" ];
  $senha = $_POST[ "txt_senha" ];

  // conectabo ao MySQL
  include_once 'private/DBconfig.php';

  // VERIFICAR LOGIN
  try {
    if ( $userManager->logar( $email, $senha ) ) {
      if ( $_SESSION[ "Uadmin" ] ) {
        header( 'Location: professor/home.php' );
      } else {
        header( 'Location: aluno/home.php' );
      }
    } else {
      header( 'Location: login.php?loginError' );
      exit();
    }
  } catch ( PDOException $e ) {
    echo "<br/>ERROR: " . $e->getMessage() . "<br/>";
    die();
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <title>DFA judge - Login</title>
  <meta charset="UTF-8" />
  <meta name="description" content="DFA judge" />
  <link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="icon" type="image/png" href="favicon_x16.png" sizes="16x16" />
  <link rel="icon" type="image/png" href="favicon_x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="favicon_x96.png" sizes="96x96" />
  <link rel="icon" type="image/png" href="favicon_x128.png" sizes="128x128" />
  <link rel="icon" type="image/png" href="favicon_x196.png" sizes="196x196" />
  <link rel="stylesheet" type="text/css" href="css/login.css" />
</head>
<body>
  <div id="LOGAR">
    <form action="login.php"
          method="post">
      <table align="center"
             width="340px">
        <tr>
          <td colspan="2"
              width="340px">
            <img src="logos/LOGO_x340.png"
                 title="Sistema de auxílio na  &#10;aprendizagem da disciplina  &#10;Linguagens Formais e Autômatos"
                 alt="Sistema de auxílio na  &#10;aprendizagem da disciplina  &#10;Linguagens Formais e Autômatos">
          </td>
        </tr>
        <tr>
          <td width="30px">
            <div class="login_email"></div>
          </td>
          <td width="310px">
            <input type="email"
                   class="text"
                   name="txt_email"
                   placeholder="E-mail"
                   title="Digite seu e-mail"
                   style="width: 292px"
                   maxlength="80"
                   required
                   autofocus>
          </td>
        </tr>
        <tr>
          <td>
            <div class="login_pass"></div>
          </td>
          <td><input type="password"
                     class="text"
                     name="txt_senha"
                     placeholder="Senha"
                     title="Digite sua senha"
                     style="width: 292px"
                     maxlength="20"
                     required>
          </td>
        </tr>
        <tr>
          <td colspan="2"
              style="text-align: right; padding-right:10px">
            <a href="resetSenha.php"
               class="login_esqueci"
               title="Clique aqui caso esqueceu sua senha">Esqueci a senha</a>
            <button type="submit"
                    name="logar"
                    class="BTN_logar"
                    title="Clique aqui para logar">LOGAR
            </button>
          </td>
        </tr>
        <?php if ( isset( $_GET[ 'loginError' ] ) ) { ?>
          <tr>
            <td colspan="2"
                style="text-align: center">
              <div id="loginError"> E-mail ou Senha Inválida!</div>
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