<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );


if ( isset( $_GET[ 'duplicarQuestao' ] ) ) {
  //  q.Qid, q.Qtitulo, q.corpo, q.alfabeto, q.gabaritoDados, q.gabaritoDesigner
  if ( !isset( $_GET[ 'Qid' ] ) ) {
    header( "Location: questoes.php" );
    exit();
  }
  $Qid = $_GET[ 'Qid' ];

  $questao = $profDAO->getQuestao( $Qid ); // return [(Qid, Qtitulo, corpo, alfabeto, gabaritoDesigner)]
  if ( !isset( $_GET[ 'Qid' ] ) ) {
    header( "Location: questaoVisualizar.php?Qid=" . $Qid );
    exit();
  }

  $Qtitulo   = $questao[ 'Qtitulo' ] . ' - cópia';
  $Qcorpo    = $questao[ 'corpo' ];
  $Qalfabeto = $questao[ 'alfabeto' ];
  $DFAdesign = $questao[ 'gabaritoDesigner' ];
  $DFAdados  = $questao[ 'gabaritoDados' ];

  $profDAO->novaQuestao( $Qtitulo, $Qcorpo, $Qalfabeto, $DFAdesign, $DFAdados );

  $novoQid = $DB_con->lastInsertId();
  header( 'Location: questao.php?editarQuestao&Qid=' . $novoQid );
  exit();
}

if ( !isset( $_GET[ 'Qid' ] ) ) {
  header( "Location: questoes.php" );
  exit();
}
$Qid     = $_GET[ 'Qid' ];
$questao = $profDAO->getQuestao( $Qid ); // return [(Qid, Qtitulo, corpo, alfabeto, gabaritoDesigner)]

if ( $questao == null ) {
  header( "Location: questoes.php" );
  exit();
}

$Qtitulo   = noHTML( $questao[ 'Qtitulo' ] );
$Qcorpo    = $questao[ 'corpo' ];
$Qalfabeto = noHTML( $questao[ 'alfabeto' ] );
$DFAdesign = noHTML( $questao[ 'gabaritoDesigner' ] );


$tituloPag    = 'Visualizar Questão';
$tituloHeader = 'Visualizar Questão';
//$backPag      = ( isset( $_GET[ 'Lid' ] ) ? 'listaQuestoes.php?Lid=' . $_GET[ 'Lid' ] : 'questoes.php' );
if ( isset( $_GET[ 'Lid' ] ) ) {
  $backPag  = 'listaQuestoes.php?Lid=' . $_GET[ 'Lid' ];
  $pagAtiva = 'LISTAS';
} else {
  $backPag  = 'questoes.php';
  $pagAtiva = 'QUESTOES';
}

$addPag    = 'questao.php?editarQuestao&Qid=' . $Qid;
$addTitle  = 'Editar Questão';
$addIcon   = '../img/editar_p.png';
$morePag   = 'questaoVisualizar.php?duplicarQuestao&Qid=' . $Qid;
$moreTitle = 'Duplicar esta Questão';
$moreIcon  = '../img/duplicate_p.png';
$DFAjs     = true;
$mathjax   = true;
include '_header.php';
?>
<div id="HdV_DFA">
  <div class="pergunta">
    <h2><?= $Qtitulo ?></h2>

    <div id="Qcorpo">
      <?= $Qcorpo ?>
    </div>

    <input type="hidden"
           name="txt_alfabeto"
           id="txt_alfabeto"
           value="<?= $Qalfabeto ?>" />
    <div id="sigma"><b>&Sigma;</b> = { }</div>
  </div>
  <!--  Gabarito<br>-->
  <canvas id="canvas"
          class="DFA"
          style="background-color:#f2f2f2;"
          width="915px"
          height="680">
    <span class="error">Seu Navegador não suporta <br> o elemento &lt;canvas&gt; do HTML5</span>
  </canvas>
  <input type="hidden"
         name="DFAdesign"
         id="DFAdesign"
         value="<?= $DFAdesign ?>" />

</div>
<script type="application/javascript">
  window.onload = function () {
    printAlfabeto();
    DFA_init_readonly();
  };
</script>
<?php include '_footer.php'; ?>
