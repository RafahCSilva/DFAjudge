<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );
$usuarios = $profDAO->getUsuarios();

$tituloPag    = 'Usuários';
$tituloHeader = 'Todos os Usuários';
$backPag      = 'home.php';
$addPag       = 'usuario.php?novoUsuario';
$addTitle     = 'Novo Usuário';
$addIcon      = '../img/addUser_p.png';
$pagAtiva     = 'USUARIOS';
include '_header.php';
?>

<script>
  function deletar () {
    return window.confirm( "Deseja realmente excluir este Usuário?" );
  }
</script>
<table class="HdV_listar">
  <tr>
    <th width="12%" tooltip="RA do Usuário">
      RA
    </th>
    <th width="34%" tooltip="Nome do Usuário">
      Nome
    </th>
    <th width="34%" tooltip="E-mail do Usuário">
      E-mail
    </th>
    <th width="10%" tooltip="Privilégio do Usuário">
      Privilégio
    </th>
    <th width="5%"></th>
    <th width="5%"></th>
  </tr>
  <?php foreach ( $usuarios as $row ) { ?>
    <tr>
      <td style="padding-left:15px"><?= $row[ "RA" ] ? noHTML( $row[ "RA" ] ) : ''; ?></td>
      <td><?= noHTML( $row[ "nome" ] ); ?></td>
      <td><?= noHTML( $row[ "email" ] ); ?></td>
      <td style="text-align: center"><?= $row[ "admin" ] == 1 ? 'admin' : 'aluno'; ?></td>
      <td class="tdImgCenter">
        <a href="usuario.php?editarUsuario&Aid=<?= $row[ "Uid" ]; ?>"
           tooltip="Editar este Usuário">
          <img src="../img/editar_b.png">
        </a>
      </td>
      <td class="tdImgCenter">
        <a href="usuario.php?excluirUsuario&Aid=<?= $row[ "Uid" ]; ?>"
           tooltip="Excluir este Usuário"
           onclick="return deletar();">
          <img src="../img/deletar_b.png">
        </a>
      </td>
    </tr>
  <?php } ?>
</table>


<?php include '_footer.php'; ?>
