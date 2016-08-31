<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );

if ( !isset( $_GET[ 'Tid' ] ) ) {
  header( "Location: turmas.php" );
  exit();
}
$Tid = $_GET[ 'Tid' ];

$turma = $profDAO->getTurma( $Tid );
if ( $turma == null ) {
  header( "Location: turmaDetalhes.php?Tid=" . $Tid );
  exit();
}

$listas = $profDAO->getTurmaListas( $Tid );
$alunos = $profDAO->getTurmaAlunos( $Tid );

if ( isset( $_GET[ 'exportar' ] ) ) {
  $dados = array();

  // RA NOME [listas.contQuestoes] TOTAL PORCENTAGEM
  $row   = array();
  $row[] = 'RA';
  $row[] = 'Nome';
  $contL = 0;
  $cont  = 1;
  foreach ( $listas as $lista ) {
    $contQ = $profDAO->getListaContQuestoes( $lista[ 'Lid' ] );
    $contL += $contQ;
    $row[] = $cont++ . '(' . $contQ . ')';
  }
  $row[] = 'Total (' . $contL . ')';
  $row[] = 'Porcentagem';

  $dados[] = $row;

  foreach ( $alunos as $aluno ) {
    $row   = array();
    $Aid   = $aluno[ 'Uid' ];
    $row[] = noHTML( $aluno[ 'RA' ] );
    $row[] = noHTML( $aluno[ 'nome' ] );

    $contagemAcertos = 0;
    foreach ( $listas as $lista ) {
      $numAcertos = $profDAO->getSubmissaoContCorretosPorLista( $Tid, $lista[ 'Lid' ], $Aid );
      $contagemAcertos += $numAcertos;
      $row[] = $numAcertos;
    }
    $row[] = $contagemAcertos;
    $row[] = number_format( $contagemAcertos / $contL, 4, ',', '' );

    $dados[] = $row;
  }
  $outputBuffer = fopen( "php://output", 'w' );
  header( "Content-Type:application/csv" );
  header( "Content-Disposition:attachment;filename=RelatorioTurma_" . $turma[ 'Tnome' ] . "_" . $turma[ 'quad' ] . ".csv" );
  header( "Pragma: no-cache" );
  header( "Expires: 0" );
  foreach ( $dados as $val ) {
    fputcsv( $outputBuffer, $val, $delimiter = ";" );
  }
  fclose( $outputBuffer );

  exit();
}

$tituloPag    = 'Relatório da Turma';
$tituloHeader = 'Relatório da Turma';
$backPag      = 'turmaDetalhes.php?Tid=' . $Tid;
$addPag       = 'relatorioTurma.php?Tid=' . $Tid . '&exportar';
$addTitle     = 'Clique aqui para Exportar este Relatório';
$addIcon      = '../img/download_p.png';
$pagAtiva     = 'TURMAS';
include '_header.php';
?>

<table class="HdV_form" style="width: 100%; margin-bottom: 1.5em">
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
    <td><b>Total de Listas</b></td>
    <td><?= $profDAO->getTurmaContListas( $Tid ); ?></td>
  </tr>
</table>

<table class="HdV_listar">
  <thead>
  <tr style="border: none">
    <th colspan="2" width="45%" style="border-right: 2px solid white;padding-bottom: 0">
      Alunos
    </th>
    <th colspan="<?= count( $listas ) + 1; ?>" width="55%" style="padding-bottom: 0;">
      Contagem de Acertos
    </th>
  </tr>

  <tr style="border: none">
    <th width="10%" style="padding-top: 0;">
      RA
    </th>
    <th width="35%" style="border-right: 2px solid white;padding-top: 0;">
      Nome
    </th>
    <?php
    $contL = 1;
    $contQ = 0;
    foreach ( $listas as $lista ) {
      $numQ = $profDAO->getListaContQuestoes( $lista[ 'Lid' ] );
      $contQ += $numQ; ?>

      <th style="padding: 0 1px 0 1px;font-weight: normal">
        <a href="relatorioLista.php?Tid=<?= $Tid ?>&Lid=<?= $lista[ 'Lid' ] ?>&RelTurma"
           tooltip="Ver Relatório da Lista: &#10;<?= noHTML( $lista[ 'Ltitulo' ] ) ?>">
          <?= $contL++ ?> (<?= $numQ ?>) </a>
      </th>
    <?php } ?>
    <th style="border-left: 2px solid white;padding: 0 1px 0 1px;"
        tooltip="Porcentagem e total de acertos">Total (<?= $contQ ?>)
    </th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ( $alunos as $aluno ) {
    $Aid   = $aluno[ 'Uid' ];
    $ra    = noHTML( $aluno[ 'RA' ] );
    $Anome = noHTML( $aluno[ 'nome' ] ); ?>
    <tr>
      <td style="padding: 5px 0 5px 10px;"><?= $ra; ?></td>
      <td style="padding: 5px 2px 5px 10px;border-right: 2px solid white;"><?= $Anome; ?></td>
      <?php
      $contagem = 0;
      foreach ( $listas as $l ) { ?>

        <td style="text-align: center;padding: 5px 0;">
          <?php
          $numAcertos = $profDAO->getSubmissaoContCorretosPorLista( $Tid, $l[ 'Lid' ], $Aid );
          $contagem += $numAcertos;
          echo $numAcertos;
          ?>
        </td>
      <?php } ?>
      <td style="text-align: center;border-left: 2px solid white;"><?php
        $razao       = $contagem * 100 / $contQ;
        $porcentagem = floor( $razao * 10 ) / 10;
        $porcentagem = number_format( $porcentagem, ( floor( $porcentagem ) != $porcentagem ? 1 : 0 ) );
        echo $porcentagem . '% (' . $contagem . ')'; ?>
      </td>
    </tr>
  <?php } ?>

  </tbody>
</table>
<?php include '_footer.php'; ?>

