<?php

if ( isset( $_POST[ 'redefinir' ] ) ) {
  $antSenha = $_POST[ 'senha_antiga' ];
  $nvSenha  = $_POST[ 'senha_nova' ];
  $nvSenhaC = $_POST[ 'senha_novaConfirma' ];
  if ( $antSenha != '' && $nvSenha != '' && $nvSenhaC != '' && $nvSenha == $nvSenhaC ) {
    if ( $userManager->verificaSenha( $antSenha ) ) {
      $userManager->setSenhaMeuUsuario( password_hash( $nvSenha, PASSWORD_DEFAULT ) );
      header( 'Location: minhaConta.php?senhaOK' );
      exit();
    }
  }
  header( 'Location: minhaConta.php?senhaIncorreta' );
  exit();
}

$tituloPag    = 'Minha Conta';
$tituloHeader = 'Configurações da Minha Conta';
$backPag      = 'home.php';

$usuario = $userManager->getMeuUsuario();
//echo '<pre>'.print_r($usuario, true).'</pre>';

$pagAtiva = 'OPCOES';
include '_header.php';
?>

<table class="HdV_form" style="width:600px; text-align:left;">
  <tr>
    <td colspan="2"><h2>Usuário <?= $usuario[ 'admin' ] ? 'Administrador' : 'Aluno'; ?></h2></td>
  </tr>
  <tr>
    <td width="30%"><label>Nome</label></td>
    <td width="70%"><?= noHTML( $usuario[ 'nome' ] ) ?></td>
  </tr>
  <tr>
    <td><label>E-mail</label></td>
    <td><?= noHTML( $usuario[ 'email' ] ) ?></td>
  </tr>

  <?php if ( $usuario[ 'RA' ] != 0 ) { ?>

    <tr>
      <td><label>RA</label></td>
      <td><?= noHTML( $usuario[ 'RA' ] ) ?></td>
    </tr>
  <?php } ?>

  <tr>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2"
        style="padding: 0">
      <form method="post"
            action="minhaConta.php">
        <table style="display: block; margin: 0 auto;width:360px; box-sizing: border-box">
          <tr style="width:360px;">
            <td colspan="2">
              <h3 style="margin: 25px 0 0 0">Alterar senha de acesso:</h3>
            </td>
          </tr>
          <tr>
            <td>
              <div class="conta_senha"></div>
            </td>
            <td>
              <input type="password"
                     class="text_pass"
                     name="senha_antiga"
                     id="senha_antiga"
                     placeholder="Digite a Senha Atual"
                     style="width: 300px"
                     title="Digite sua senha atual"
                     required />
            </td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td>
              <div class="conta_senhanova"></div>
            </td>
            <td>
              <input type="password"
                     class="text_pass"
                     name="senha_nova"
                     id="senha_nova"
                     placeholder="Digite a Nova Senha"
                     style="width: 300px"
                     title="Digite sua senha atual"
                     required />
            </td>
          </tr>
          <tr>
            <td>
              <div class="conta_senhanova"></div>
            </td>
            <td>
              <input type="password"
                     class="text_pass"
                     name="senha_novaConfirma"
                     id="senha_novaConfirma"
                     placeholder="Redigite a Nova Senha"
                     style="width: 300px"
                     title="Digite sua senha atual"
                     required />
            </td>
          </tr>
          <tr>
            <td colspan="2"
                style="text-align: right">
              <button type="submit"
                      id="BTN_redefinir"
                      name="redefinir"
                      onClick="return checarSenhas ()"
                      tooltip="Clique aqui para Redefinir uma Nova Senha">
                Redefinir Senha
              </button>
            </td>
          </tr>

          <tr>
            <td colspan="2"
                style="text-align: center">
              <div id="senhaError"
                   style="font-weight: bold; color: #F00">
                <?php if ( isset( $_GET[ 'senhaIncorreta' ] ) ) { ?>
                  Senha Atual Inválida!
                <?php } else if ( isset( $_GET[ 'senhaOK' ] ) ) {
                  echo '<span style="color:green;font-size:1.2em">Sua senha foi alterada com sucesso!</span>';
                } ?>
              </div>
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>

<script language='javascript'
        type='text/javascript'>
  function checarSenhas () {
    var antSenha = document.getElementById( 'senha_antiga' ).value;
    var nvSenha  = document.getElementById( 'senha_nova' ).value;
    var nvSenhaC = document.getElementById( 'senha_novaConfirma' ).value;
    var erro     = document.getElementById( 'senhaError' );
    if ( antSenha == '' ) {
      erro.innerHTML = 'É necessario digitar a <br/> sua senha atual!';
    } else if ( nvSenha == '' ) {
      erro.innerHTML = 'É necessario digitar a <br/> sua nova senha!';
    } else if ( nvSenhaC == '' ) {
      erro.innerHTML = 'É necessario redigitar a sua <br/> nova senha para confirmar!';
    } else if ( nvSenha != nvSenhaC ) {
      erro.innerHTML = 'As novas senhas são diferentes!';
      return false;
    } else if ( nvSenha.length < 6 ) {
      erro.innerHTML = 'Senha muito curta! <br/> (no mínimo 6 caracteres)';
      return false;
    }
    return true;
  }
</script>
<?php include '_footer.php'; ?>
