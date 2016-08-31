<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );
$questoes = $profDAO->getQuestoes();

$tituloPag    = 'Questões';
$tituloHeader = 'Minhas Questões';
$backPag      = 'home.php';
$addPag       = 'questao.php?novaQuestao';
$addTitle     = 'Nova Questão';
$addIcon      = '../img/add_p.png';
$morePag      = 'questoesExportar.php';
$moreTitle    = 'Importar e Exportar Questões';
$moreIcon     = '../img/downup_p.png';
$pagAtiva     = 'QUESTOES';
$mathjax      = true;
include '_header.php';
?>
<script>
  function deletar () {
    return window.confirm( "Deseja realmente excluir esta Questão?" );
  }
</script>
<table class="HdV_listar">
  <tr>
    <th width="70%" tooltip="Título da Questão">Título</th>
    <th width="10%" tooltip="Número de utilizações desta Questão">Usos</th>
    <th width="5%"></th>
    <th width="5%"></th>
    <th width="5%"></th>
    <th width="5%"></th>
  </tr>
  <?php foreach ( $questoes as $row ) { ?>
    <tr>
      <td style="padding-left:35px">
        <?= noHTML( $row[ "Qtitulo" ] ); ?>
      </td>
      <td class="tdImgCenter">
        <?= $profDAO->contUsoQuestoes( $row[ "Qid" ] ); ?>
      </td>
      <td class="tdImgCenter">
        <a href="questaoVisualizar.php?Qid=<?= $row[ "Qid" ]; ?>" tooltip="Visualizar esta Questão"> <img src="../img/visualizar_b.png"> </a>
      </td>
      <td class="tdImgCenter">
        <a href="questao.php?editarQuestao&Qid=<?= $row[ "Qid" ]; ?>" tooltip="Editar esta Questão"> <img src="../img/editar_b.png"> </a>
      </td>
      <td class="tdImgCenter">
        <a href="questaoVisualizar.php?duplicarQuestao&Qid=<?= $row[ "Qid" ]; ?>" tooltip="Duplicar esta Questão"> <img src="../img/duplicate_b.png">
        </a>
      </td>
      <td class="tdImgCenter">
        <a href="questao.php?excluirQuestao&Qid=<?= $row[ "Qid" ]; ?>" tooltip="Excluir esta Questão" onclick="return deletar();">
          <img src="../img/deletar_b.png"> </a>
      </td>
    </tr>
  <?php } ?>
</table>

<?php include '_footer.php'; ?>
