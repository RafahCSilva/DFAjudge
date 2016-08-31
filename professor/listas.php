<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );

$listas = $profDAO->getListas();

$tituloPag    = 'Listas';
$tituloHeader = 'Minhas Listas';
$backPag      = 'home.php';
$addPag       = 'lista.php?novaLista';
$addTitle     = 'Nova Lista';
$addIcon      = '../img/add_p.png';
$pagAtiva     = 'LISTAS';
include '_header.php';
?>

<script>
  function deletar () {
    return window.confirm( "Deseja realmente excluir esta Lista?" );
  }
</script>

<table class="HdV_listar">
  <tr>
    <th width="70%"
        tooltip="Título da Lista">Título
    </th>
    <th width="15%"
        tooltip="Número de Questões">#Questões
    </th>
    <th width="5%"></th>
    <th width="5%"></th>
    <th width="5%"></th>
  </tr>
  <?php foreach ( $listas as $row ) { ?>
    <tr>
      <td style="padding-left:35px">
        <?= noHTML( $row[ "Ltitulo" ] ); ?>
      </td>
      <td style="text-align: center">
        <?= noHTML( $profDAO->getListaContQuestoes( $row[ "Lid" ] ) ); ?>
      </td>
      <td class="tdImgCenter">
        <a href="listaQuestoes.php?Lid=<?= $row[ "Lid" ]; ?>"
           tooltip="Visualizar esta Lista">
          <img src="../img/visualizar_b.png">
        </a>
      </td>
      <td class="tdImgCenter">
        <a href="lista.php?editarLista&Lid=<?= $row[ "Lid" ]; ?>"
           tooltip="Editar esta Lista">
          <img src="../img/editar_b.png">
        </a>
      </td>
      <td class="tdImgCenter">
        <a href="lista.php?excluirLista&Lid=<?= $row[ "Lid" ]; ?>"
           tooltip="Remover esta Lista"
           onclick="return deletar();">
          <img src="../img/deletar_b.png">
        </a>
      </td>
    </tr>
  <?php } ?>
</table>


<?php include '_footer.php'; ?>
