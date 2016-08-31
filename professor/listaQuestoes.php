<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );
if ( !isset( $_GET[ 'Lid' ] ) ) {
  header( "Location: listas.php" );
  exit();
}

$Lid   = $_GET[ 'Lid' ];
$lista = $profDAO->getLista( $Lid );
if ( $lista == null ) {
  header( "Location: listas.php" );
  exit();
}
$contQ    = $profDAO->getListaContQuestoes( $Lid );
$questoes = $profDAO->getQuestoesDaListaOrdem( $Lid );

$tituloPag    = 'Questões da Lista';
$tituloHeader = 'Questões da Lista';
$backPag      = 'listas.php';
$addPag       = 'lista.php?editarLista&Lid=' . $Lid;
$addTitle     = 'Editar esta Lista';
$addIcon      = '../img/editar_p.png';
$morePag      = 'listaQuestoesOrdem.php?Lid=' . $Lid;
$moreTitle    = 'Ordenar Questões';
$moreIcon     = '../img/sort_p.png';

$pagAtiva = 'LISTAS';
$mathjax  = true;
include '_header.php';
?>
<table class="HdV_form"
       style="width: 100%; margin-bottom: 1.5em">
  <tr>
    <td width="30%"><b>Titulo da Lista</b></td>
    <td><?= noHTML( $lista[ 'Ltitulo' ] ); ?></td>
  </tr>
  <tr>
    <td><b>Número de Questões</b></td>
    <td><?= $contQ; ?></td>
  </tr>
</table>

Escolha a questão para visualizar
<table class="HdV_listar">
  <tr>
    <th>Título da Questão</th>
  </tr>
  <?php foreach ( $questoes as $row ) { ?>
    <tr>
      <td style="padding-left: 35px">
        <a href="questaoVisualizar.php?Lid=<?= $Lid; ?>&Qid=<?= noHTML( $row[ "Qid" ] ); ?>">
          <?= noHTML( $row[ "Qtitulo" ] ); ?>

        </a>
      </td>
    </tr>
  <?php } ?>
</table>


<?php include '_footer.php'; ?>
