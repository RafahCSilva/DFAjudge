<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );
$turmas = $profDAO->getTurmas();

$tituloPag    = 'Turmas';
$tituloHeader = 'Minhas Turmas';
$backPag      = 'home.php';
$addPag       = 'turma.php?novaTurma';
$addTitle     = 'Nova Turma';
$addIcon      = '../img/addTurma_p.png';
$pagAtiva     = 'TURMAS';
include '_header.php';
?>
<script>
  function deletar () {
    return window.confirm( "Deseja realmente excluir esta Turma?" );
  }
</script>
<table class="HdV_listar">
  <tr>
    <th width="41%" tooltip="Nome da Turmas">
      Nome da Turma
    </th>
    <th width="20%" tooltip="Quadrimestre">
      Quadrimestre
    </th>
    <th width="12%" tooltip="Número de Alunos">
      #Alunos
    </th>
    <th width="12%" tooltip="Número de Listas">
      #Listas
    </th>
    <th width="5%"></th>
    <th width="5%"></th>
    <th width="5%"></th>
  </tr>
  <?php foreach ( $turmas as $row ) { ?>
    <tr>
      <td style="padding-left: 35px"><?= noHTML( $row[ "Tnome" ] ); ?></td>
      <td style="text-align: center"><?= noHTML( $row[ "quad" ] ); ?></td>
      <td style="text-align: center"><?= $profDAO->getTurmaContAlunos( $row[ "Tid" ] ); ?></td>
      <td style="text-align: center"><?= $profDAO->getTurmaContListas( $row[ "Tid" ] ); ?></td>
      <td class="tdImgCenter">
        <a href="turmaDetalhes.php?Tid=<?= $row[ "Tid" ]; ?>"
           tooltip="Visualizar esta turma">
          <img src="../img/visualizar_b.png">
        </a>
      </td>
      <td class="tdImgCenter">
        <a href="turma.php?editarTurma&Tid=<?= $row[ "Tid" ]; ?>"
           tooltip="Editar esta turma">
          <img src="../img/editar_b.png">
        </a>
      </td>
      <td class="tdImgCenter">
        <a href="turma.php?excluirTurma&Tid=<?= $row[ "Tid" ]; ?>"
           onclick="return deletar();"
           tooltip="Excluir esta turma">
          <img src="../img/deletar_b.png">
        </a>
      </td>
    </tr>
  <?php } ?>
</table>

<?php include '_footer.php'; ?>

