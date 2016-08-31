<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );

if ( !isset( $_GET[ 'Tid' ] ) || !isset( $_GET[ 'Lid' ] ) ) {
  header( "Location: turmas.php" );
  exit();
}

$Tid = $_GET[ 'Tid' ];
$Lid = $_GET[ 'Lid' ];

$turma = $profDAO->getTurma( $Tid );
if ( $turma == null ) {
  header( "Location: turmaDetalhes.php?Tid=" . $Tid );
  exit();
}

$lista = $profDAO->getListaDaTurma( $Tid, $Lid );
if ( $lista == null ) {
  header( "Location: turmaDetalhes.php?Tid=" . $Tid );
  exit();
}

$contQ = $profDAO->getListaContQuestoes( $Lid );

$questoes    = $profDAO->getQuestoesDaListaOrdem( $Lid );
$alunos      = $profDAO->getTurmaAlunos( $Tid );
$numQuestoes = count( $questoes );


function status( $r, $Aid, $Tid, $Lid, $Qid ) {
  return ( $r == null ) ? '-' : '<a href="relatorioListaSubmissao.php?Aid=' . $Aid . '&Tid=' . $Tid . '&Lid=' . $Lid . '&Qid=' . $Qid . '" target="_blank">' . $r[ 'status' ] . '</a>';
}

$tituloPag    = 'Relatório da Lista';
$tituloHeader = 'Relatório da Lista';

if ( isset( $_GET[ 'RelTurma' ] ) ) {
  $backPag = 'relatorioTurma.php?Tid=' . $Tid;
} else {
  $backPag = 'turmaDetalhes.php?Tid=' . $Tid;
}
//$mathjax = true;
$pagAtiva = 'TURMAS';
include '_header.php';
?>

<h3>Alunos X Questões daquela lista</h3>
<table class="HdV_form"
       style="width: 100%; margin-bottom: 1.5em">
  <tr>
    <td width="30%"><b>Nome da Turma</b></td>
    <td><?= noHTML( $turma[ 'Tnome' ] ); ?></td>
  </tr>
  <tr>
    <td><b>Quadrimestre</b></td>
    <td><?= noHTML( $turma[ 'quad' ] ); ?></td>
  </tr>
  <tr>
    <td><b>Total de Alunos</b></td>
    <td><?= $profDAO->getTurmaContAlunos( $Tid ); ?></td>
  </tr>
  <tr>
    <td width="30%"><b>Titulo da Lista</b></td>
    <td><?= noHTML( $lista[ 'Ltitulo' ] ); ?></td>
  </tr>
  <tr>
    <td><b>Número de Questões</b></td>
    <td><?= $contQ; ?></td>
  </tr>
</table>

<table class="HdV_listar">
  <thead>
  <tr style="border: none">
    <th colspan="2"
        width="45%"
        style="border-right: 2px solid white;padding-bottom: 0">Alunos
    </th>
    <th colspan="<?= $numQuestoes + 1; ?>"
        width="55%"
        style="padding-bottom: 0;">Status das Respostas
    </th>
  </tr>

  <tr style="border: none">
    <th width="10%"
        style="padding-top: 0;">RA
    </th>
    <th width="35%"
        style="border-right: 2px solid white;padding-top: 0;">
      Nome
    </th>
    <?php
    $cont = 1;
    foreach ( $questoes as $questao ) { ?>
      <th style="padding: 0 1px 0 1px;font-weight: normal;">
        <a href="questaoVisualizar.php?Qid=<?= $questao[ 'Qid' ] ?>"
           tooltip="Título da Questão:  &#10;'<?= noHTML( $questao[ 'Qtitulo' ] ) ?>'"
           target="_blank">
          <?= $cont++ ?>
        </a>
      </th>
    <?php } ?>
    <th style="border-left: 2px solid white;padding-top: 0;"
        tooltip="Porcentagem e total de acertos">Total (<?= $numQuestoes ?>)
    </th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ( $alunos as $aluno ) {
    $Aid   = noHTML( $aluno[ 'Uid' ] );
    $ra    = noHTML( $aluno[ 'RA' ] );
    $Anome = noHTML( $aluno[ 'nome' ] ); ?>
    <tr>
      <td style="padding: 5px 0 5px 10px;"><?= $ra; ?></td>
      <td style="padding: 5px 2px 5px 10px;border-right: 2px solid white;"><?= $Anome; ?></td>
      <?php foreach ( $questoes as $q ) { ?>
        <td style="text-align: center;">
          <?php $result = $profDAO->getStatusSubmissao( $Tid, $Lid, $q[ 'Qid' ], $Aid );
          echo status( $result, $Aid, $Tid, $Lid, $q[ 'Qid' ] ); ?>
        </td>

      <?php } ?>
      <td style="text-align: center;border-left: 2px solid white;">
        <?php
        $numAcertos  = $profDAO->getSubmissaoContCorretosPorLista( $Tid, $Lid, $Aid );
        $razao       = $numAcertos * 100 / $numQuestoes;
        $porcentagem = floor( $razao * 10 ) / 10;
        $porcentagem = number_format( $porcentagem, ( floor( $porcentagem ) != $porcentagem ? 1 : 0 ) );
        echo $porcentagem . '% (' . $numAcertos . ')';
        ?>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>

<table class="HdV_form"
       style="width: 100%; margin-top: 2.5em; text-align: left">
  <thead>
  <tr>
    <th colspan="2">LEGENDA:</th>
  </tr>
  </thead>
  <tr>
    <td width="5%"><b>C</b></td>
    <td width="95%">Correto</td>
  </tr>
  <tr>
    <td><b>I</b></td>
    <td>Incorreto</td>
  </tr>
  <tr>
    <td><b>S</b></td>
    <td>Salvo como Rascunho</td>
  </tr>
  <tr>
    <td><b>A</b></td>
    <td>Aguardando Correção</td>
  </tr>
  <tr>
    <td><b>-</b></td>
    <td>Em Branco</td>
  </tr>
</table>

<?php include '_footer.php'; ?>

