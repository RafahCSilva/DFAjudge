<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );

if ( isset( $_POST[ 'BTN_salvar' ] ) ) {

  $dados = $_POST[ 'ordem' ];
  $Lid   = $_POST[ 'Lid' ];

  $ordem = array();
  parse_str( $dados, $ordem );

  $profDAO->setListasQuestaoOrdem( $Lid, $ordem[ 'q' ] );

  header( "Location: listaQuestoesOrdem.php?Lid=" . $Lid );
  exit();
}


if ( !isset( $_GET[ 'Lid' ] ) ) {
  header( "Location: listas.php" );
  exit();
}

$Lid      = $_GET[ 'Lid' ];
$lista    = $profDAO->getLista( $Lid );
if ( $lista == null ) {
  header( "Location: listas.php" );
  exit();
}
$contQ    = $profDAO->getListaContQuestoes( $Lid );
$questoes = $profDAO->getQuestoesDaListaOrdem( $Lid );

$tituloPag    = 'Questões da Lista';
$tituloHeader = 'Ordenação das Questões da Lista';
$backPag      = 'listaQuestoes.php?Lid=' . $Lid;
$addPag       = 'lista.php?editarLista&Lid=' . $Lid;
$addTitle     = 'Editar esta Lista';
$addIcon      = '../img/editar_p.png';

$pagAtiva = 'LISTAS';
$mathjax  = true;
include '_header.php';
?>
<script src="../js/jquery-1.10.2.js"></script>
<script src="../js/jquery-ui.js"></script>
<script type="application/javascript">
  $( function () {
    $( "#sortable" ).sortable( {
      create: function ( event, ui ) {
        var data = $( this ).sortable( 'serialize' );
        $( '#ordem' ).val( data );
      },
      update: function ( event, ui ) {
        var data = $( this ).sortable( 'serialize' );
        $( '#ordem' ).val( data );
      }
    } );
    //$( "#sortable" ).disableSelection();
  } );
</script>
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
</table>Clique e arraste para ordenar as Questões na posição desejada
<form action="listaQuestoesOrdem.php"
      method="post">
  <table class="HdV_listar">
    <tr>
      <th width="5%"></th>
      <th width="90%">Título da Questão</th>
      <th width="5%"></th>
    </tr>
    <tr class="TRsortable">
      <td colspan="3">
        <ul id="sortable">
          <?php foreach ( $questoes as $row ) { ?>

            <li id="q_<?= $row[ 'LQid' ]; ?>">
              <div style="padding-left: 30px;">
                <?= ( (int) $row[ 'pos' ] ) + 1 . ') ' . noHTML( $row[ "Qtitulo" ] ); ?>

                <a href="questaoVisualizar.php?Lid=<?= $Lid; ?>&Qid=<?= noHTML( $row[ "Qid" ] ); ?>"
                   tooltip="Clique aqui para visualizar esta Questão em uma nova aba."
                   target="_blank">
                  <img src="../img/visualizar_x24_b.png">
                </a>
              </div>
            </li>
          <?php } ?>
        </ul>
      </td>
    </tr>
  </table>
  <input type="hidden"
         name="Lid"
         value="<?= $Lid ?>" />
  <input type="hidden"
         id="ordem"
         name="ordem"
         value="" />
  <div align="right">
    <button type="submit"
            name="BTN_salvar"
            tooltip="Clique aqui para salvar a ordem"
            class="salvar">
      SALVAR
    </button>
  </div>
</form>
<?php include '_footer.php'; ?>
