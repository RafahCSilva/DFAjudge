<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );

if ( !isset( $_GET[ 'Tid' ] ) && !isset( $_GET[ 'Lid' ] ) ) {
  //header( "Location: turmas.php" );
  //exit();
  die( 'sem TID ou LID' );
} else if ( !isset( $_GET[ 'Aid' ] ) && !isset( $_GET[ 'Qid' ] ) ) {
  //header( "Location: relatorioLista.php?Tid=" . $_GET[ 'Tid' ] . "&Lid=" . $_GET[ 'Lid' ] );
  //exit();
  die( 'sem AID ou QID' );
}
$Aid = $_GET[ 'Aid' ];
$Tid = $_GET[ 'Tid' ];
$Lid = $_GET[ 'Lid' ];
$Qid = $_GET[ 'Qid' ];

$aluno = $profDAO->getUsuario( $Aid ); // (Uid, RA, nome, email, admin)
if ( $aluno == null ) {
  die( 'aluno == null' );
  //header( "Location: relatorioLista.php?Tid=" . $Tid . "&Lid=" . $Lid );
  //exit();
}

$turma = $profDAO->getTurmaSubmissao( $Tid ); // (Tid, Tnome, quad)
if ( $turma == null ) {
  die( 'turma == null' );
  //header( "Location: relatorioLista.php?Tid=" . $Tid . "&Lid=" . $Lid );
  //exit();
}

$lista = $profDAO->getTurmaLista( $Tid, $Lid ); // (Lid, Ltitulo, dia, hora)
if ( $lista == null ) {
  die( 'lista == null' );
  //header( "Location: relatorioLista.php?Tid=" . $Tid . "&Lid=" . $Lid );
  //exit();
}

$questao = $profDAO->getQuestaoSubmissao( $Qid ); // (Qid, Qtitulo, corpo, alfabeto, gabaritoDesigner)
if ( $questao == null ) {
  die( 'questao == null' );
  //header( "Location: relatorioLista.php?Tid=" . $Tid . "&Lid=" . $Lid );
  //exit();
}
$submissao = $profDAO->getSubmissaoAluno( $Aid, $Tid, $Lid, $Qid ); // (respostaDesigner, status, palavra, horaJuiz)
if ( $submissao == null ) {
  die( 'submissao == null' );
  //header( "Location: relatorioLista.php?Tid=" . $Tid . "&Lid=" . $Lid );
  //exit();
}

//echo '<pre>';
//print_r($aluno);
//print_r($turma);
//print_r($lista);
//print_r($questao);
//echo '</pre>';

$RA        = noHTML( $aluno[ 'RA' ] );
$nome      = noHTML( $aluno[ 'nome' ] );
$Tnome     = noHTML( $turma[ 'Tnome' ] );
$quad      = noHTML( $turma[ 'quad' ] );
$Ltitulo   = noHTML( $lista[ 'Ltitulo' ] );
$TLdia     = noHTML( $lista[ 'dia' ] );
$TLhora    = noHTML( $lista[ 'hora' ] );
$deadline  = $TLdia . 'T' . $TLhora;
$Qtitulo   = noHTML( $questao[ 'Qtitulo' ] );
$Qcorpo    = $questao[ 'corpo' ];
$Qalfabeto = $questao[ 'alfabeto' ];
$resposta  = noHTML( $submissao[ 'respostaDesigner' ] );
$status    = noHTML( $submissao[ 'status' ] );
$palavra   = $submissao[ 'palavra' ];
$horaJuiz  = noHTML( $submissao[ 'horaJuiz' ] );

date_default_timezone_set( 'America/Sao_Paulo' );
$DTdeadline = new DateTime( $deadline );
$agora      = new DateTime( 'NOW' );
$expirou    = !( $agora->format( 'Y-m-d H:i' ) <= $DTdeadline->format( 'Y-m-d H:i' ) );

$tituloPag    = 'Visualizar Resposta do Aluno';
$tituloHeader = 'Visualizar Resposta do Aluno';
$backPag      = 'relatorioLista.php?Tid=' . $Tid . '&Lid=' . $Lid;
$pagAtiva     = 'TURMAS';
$addPag       = 'questaoVisualizar.php?Qid=' . $Qid;
$addTitle     = 'Visualizar o gabarito desta Questão';
$addIcon      = '../img/visualizar_p.png';
$DFAjs        = true;
$mathjax      = true;
include '_header.php';
?>

<table class="HdV_form">
  <tr>
    <td width="20%"><b>RA do Aluno</b></td>
    <td width="80%"><?= $RA; ?> </td>
  </tr>
  <tr>
    <td><b>Nome do Aluno</b></td>
    <td><?= $nome; ?></td>
  </tr>
  <tr>
    <td><b>Turma</b></td>
    <td><?= $Tnome; ?></td>
  </tr>
  <tr>
    <td><b>Quadrimestre</b></td>
    <td><?= $quad; ?></td>
  </tr>
  <tr>
    <td><b>Título da Lista</b></td>
    <td><?= $Ltitulo; ?></td>
  </tr>
  <tr>
    <td><b>Data Limite</b></td>
    <?php if ( $expirou ) { ?>
      <td style="color: #F00;">
        <span tooltip="A data de tolerância &#10;para responder esta &#10;Lista já passou. &#10;Você não pode mais &#10;submeter uma resposta.">
          <?= date_format( date_create( $deadline ), "d/m/Y H:i" ); ?>
          <img src="../img/alert_x24_vermelho.png" style="margin-left: 15px; padding: 0;vertical-align: text-bottom;">
        </span>
      </td>
    <?php } else { ?>
      <td>
        <span tooltip="O prazo máximo para a &#10;entrega desta Lista é dia &#10;<?= date_format( date_create( $deadline ), "d/m/Y" ) ?> as <?= date_format( date_create( $deadline ), "H:i" ) ?> ">
          <?= date_format( date_create( $deadline ), "d/m/Y H:i" ); ?>
        </span>
      </td>
    <?php } ?>

  </tr>
  <tr>
    <td colspan="2"><b>Questão</b><br>
      <div id="HdV_DFA">
        <div class="pergunta">

          <h3><?= $Qtitulo ?></h3>

          <div id="Qcorpo">
            <?= $Qcorpo ?>
          </div>

          <input type="hidden" name="txt_alfabeto" id="txt_alfabeto" value="<?= $Qalfabeto ?>" />
          <div id="sigma"><b>&Sigma;</b> = { }</div>

        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td colspan="2"><b>Resposta</b><br>
      <canvas id="canvas" class="DFA" style="background-color:#f2f2f2;" width="915px" height="680">
        <span class="error">Seu Navegador não suporta <br> o elemento &lt;canvas&gt; do HTML5</span>
      </canvas>
      <input type="hidden" name="DFAdesign" id="DFAdesign" value="<?= $resposta ?>" />
    </td>
  </tr>
  <tr>
    <td colspan="2"><b>Status</b>
      <div id="correcao">
        <?php if ( $status == $profDAO->status_CORRIGIDO_CORRETO ) { ?>
          <div id="resultado" class="acertou">
            <img src="../img/correto_b.png">
            <div>CORRETO !!!</div>
          </div>
          <?php
        } else if ( $status == $profDAO->status_CORRIGIDO_INCORRETO ) { ?>

          <div id="resultado" class="errou">
            <img src="../img/incorreto_b.png">
            <div>
              INCORRETO<br> palavra dica: "<?= $palavra; ?>"
            </div>
          </div>
          <?php
        } else if ( $status == $profDAO->status_SALVO_RASCUNHO ) { ?>

          <div id="resultado" class="salvo">
            <img src="../img/salvo_b.png">
            <div>Salvo como Rascunho</div>
          </div>
        <?php } ?>

    </td>
  </tr>
</table>

<script type="application/javascript">
  window.onload = function () {
    printAlfabeto();
    DFA_init_readonly();
  };
</script>
<?php include '_footer.php'; ?>
