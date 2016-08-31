<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );
//echo '<pre>';
//print_r($_POST);
//echo '</pre>';
//die();

if ( isset( $_GET[ 'editarUsuario' ] ) ) { //  EDITAR USUARIO
  if ( !isset( $_GET[ 'Aid' ] ) ) {
    header( "Location: usuarios.php" );
    exit();
  }
  $Aid = $_GET[ 'Aid' ];

  $usuario = $profDAO->getUsuario( $Aid ); //  [(Uid, nome, usuario, email, admin)...]
  if ( $usuario == null ) {
    header( "Location: usuarios.php" );
    exit();
  }

  $ra    = noHTML( $usuario[ 'RA' ] );
  $nome  = noHTML( $usuario[ 'nome' ] );
  $email = noHTML( $usuario[ "email" ] );
  $admin = Array( "",
                  "" );

  $admin[ $usuario[ 'admin' ] ] = "checked";

  $acao = "EDITAR";

} else if ( isset( $_GET[ 'novoUsuario' ] ) ) {   //  NOVO USUARIO
  $Aid   = "";
  $ra    = "";
  $nome  = "";
  $email = "";
  $admin = Array( "checked",
                  "" );
  $acao  = "NOVO";

} else if ( isset( $_POST[ 'salvar' ] ) ) { //  SALVAR USUARIO
  $Aid      = $_POST[ 'txt_id' ];
  $TXTra    = $_POST[ 'txt_ra' ];
  $TXTnome  = $_POST[ 'txt_nome' ];
  $TXTemail = $_POST[ 'txt_email' ];

  if ( $_POST[ 'privilegio' ] == "admin" ) {
    $TXTadmin = 1;
    $TXTra    = 0;
  } else if ( $_POST[ 'privilegio' ] == "aluno" ) {
    $TXTadmin = 0;
  }
  $profDAO->alterarUsuario( $Aid, $TXTra, $TXTnome, $TXTemail, $TXTadmin );
  header( "Location: usuarios.php" );
  exit();

} else if ( isset( $_POST[ 'reenviar' ] ) ) { //  SALVAR USUARIO e REENVIA CONVITE
  $Aid      = $_POST[ 'txt_id' ];
  $TXTra    = $_POST[ 'txt_ra' ];
  $TXTnome  = $_POST[ 'txt_nome' ];
  $TXTemail = $_POST[ 'txt_email' ];

  if ( $_POST[ 'privilegio' ] == "admin" ) {
    $TXTadmin = 1;
    $TXTra    = 0;
  } else if ( $_POST[ 'privilegio' ] == "aluno" ) {
    $TXTadmin = 0;
  }

  $profDAO->resetarUsuario( $Aid, $TXTra, $TXTnome, $TXTemail, $TXTadmin );

  // envia email pro user de bem-vindo
  include_once '../private/class.resetsenha.php';
  $reset = new RESETSENHA( $DB_con, $baseSITE );
  $reset->_bemVindo( $TXTemail );

  header( "Location: usuarios.php" );
  exit();

} else if ( isset( $_POST[ 'inserir' ] ) ) { //  INSERIR USUARIO
  $TXTra    = $_POST[ 'txt_ra' ];
  $TXTnome  = $_POST[ 'txt_nome' ];
  $TXTemail = $_POST[ 'txt_email' ];

  if ( $_POST[ 'privilegio' ] == "admin" ) {
    $TXTadmin = 1;
    $TXTra    = 0;
  } else if ( $_POST[ 'privilegio' ] == "aluno" ) {
    $TXTadmin = 0;
  }
  $profDAO->novoUsuario( $TXTra, $TXTnome, $TXTemail, $TXTadmin );

  // envia email pro user de bem-vindo
  include_once '../private/class.resetsenha.php';
  $reset = new RESETSENHA( $DB_con, $baseSITE );
  $reset->_bemVindo( $TXTemail );

  header( "Location: usuarios.php" );
  exit();

} else if ( isset( $_GET[ 'excluirUsuario' ] ) ) {  /// EXCLUIR USUARIO
  if ( !isset( $_GET[ 'Aid' ] ) ) {
    header( "Location: usuarios.php" );
    exit();
  }
  $Aid = $_GET[ 'Aid' ];
  $profDAO->deletaUsuario( $Aid );
  header( "Location: usuarios.php" );
  exit();

} else {
  header( "Location: usuarios.php" );
  exit();
}

$tituloPag    = 'Usuário';
$tituloHeader = 'Usuário';
if ( $acao == "NOVO" ) {
  $tituloHeader = 'Novo Usuário';
} else if ( $acao == "EDITAR" ) {
  $tituloHeader = 'Editar Usuário';
}
$backPag  = 'usuarios.php';
$pagAtiva = 'USUARIOS';
include '_header.php';
?>

<form action="usuario.php"
      method="post">
  <input type="hidden"
         name="txt_id"
         id="txt_id"
         maxlength="11"
         size="11"
         readonly
         value="<?= $Aid ?>"/>
  <table class="HdV_form">
    <tr>
      <td>
        <label for="txt_ra">RA</label>
      </td>
      <td>
        <input type="text"
               name="txt_ra"
               id="txt_ra"
               maxlength="8"
               size="16"
               placeholder="Digite o RA do usuário"
               title="Digite o RA do usuário"
               required
               autofocus
               value="<?= $ra ?>"/>
      </td>
    </tr>
    <tr>
      <td>
        <label for="txt_nome">Nome</label>
      </td>
      <td>
        <input type="text"
               name="txt_nome"
               id="txt_nome"
               maxlength="255"
               size="60"
               placeholder="Digite o nome do usuário"
               title="Digite o nome do usuário"
               required
               value="<?= $nome ?>"/>
      </td>
    </tr>
    <tr>
      <td>
        <label for="txt_email">E-mail</label>
      </td>
      <td>
        <input type="text"
               name="txt_email"
               id="txt_email"
               maxlength="80"
               size="60"
               placeholder="Digite o e-mail do usuário"
               title="Digite o e-mail do usuário"
               required
               value="<?= $email ?>"/>
      </td>
    </tr>

    <tr>
      <td><label for="privilegio">Privilégio</label></td>
      <td>
        <label title="Selecione o privilégio como Administrador">

          <input type="radio"
                 name="privilegio"
                 value="admin" <?= $admin[ 1 ]; ?>
                 onclick="document.getElementById('txt_ra').value='0';"/> Administrador</label><br/>

        <label title="Selecione o privilégio como Aluno">

          <input type="radio"
                 name="privilegio"
                 value="aluno" <?= $admin[ 0 ]; ?>/> Aluno</label>
      </td>
    </tr>

    <tr>
      <td></td>
      <td style="text-align: right">
        <?php if ( $acao == "EDITAR" ) { ?>
          <button type="submit"
                  class="reenviar"
                  name="reenviar"
                  tooltip="Clique aqui para Salvar e Reenviar convite">
            REENVIAR CONVITE
          </button>
          <button type="submit"
                  class="salvar"
                  name="salvar"
                  tooltip="Clique aqui para salvar">
            SALVAR
          </button>
        <?php } else if ( $acao == "NOVO" ) { ?>
          <button type="submit"
                  class="inserir"
                  name="inserir"
                  tooltip="Clique aqui para inserir">INSERIR
          </button>
        <?php } ?>
      </td>
    </tr>

  </table>
</form>


<?php include '_footer.php'; ?>
