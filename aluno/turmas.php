<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( false );

$turmas = $alunoDAO->getTurmas();

$pagAtiva     = 'TURMAS';
$tituloPag    = 'Turmas';
$tituloHeader = 'Minhas Turmas';
$backPag      = 'home.php';
include '_header.php';
?>
<p>
  Clique sobre uma Turma para visualizar suas Listas: </p>
<table class="HdV_listar">
  <tr>
    <th width="45%"
        tooltip="Nome da Turma">
      Nome
    </th>
    <th width="25%"
        tooltip="Quadrimestre da Turma">
      Quadrimestre
    </th>
    <th width="30%"
        tooltip="Professor da Turma">
      Professor
    </th>
  </tr>
  <?php foreach ( $turmas as $row ) { ?>
    <tr>
      <td>
        <a href="listas.php?turma=<?= $row[ "Tid" ]; ?>"
           style="padding-left: 35px">
          <?= noHTML( $row[ "Tnome" ] ); ?>
        </a>
      </td>
      <td style="text-align: center">
        <a href="listas.php?turma=<?= $row[ "Tid" ]; ?>">
          <?= noHTML( $row[ "quad" ] ); ?>
        </a>
      </td>
      <td>
        <a href="listas.php?turma=<?= $row[ "Tid" ]; ?>">
          <?= noHTML( $row[ "nomeProf" ] ); ?>
        </a>
      </td>
    </tr> <?php } ?>
</table>

<?php include '_footer.php'; ?>
