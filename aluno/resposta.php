<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( false );

// tratamento de parametros
if ( !isset( $_GET[ 'turma' ] ) ) {
  header( 'Location: turmas.php' );
  exit();
}
$Tid = $_GET[ 'turma' ];

if ( !isset( $_GET[ 'lista' ] ) ) {
  header( 'Location: listas.php?turma=' . $Tid );
  exit();
}
$Lid = $_GET[ 'lista' ];

if ( !isset( $_GET[ 'questao' ] ) ) {
  header( 'Location: questoes.php?turma=' . $Tid . '&lista=' . $Lid );
  exit();
}
$Qid = $_GET[ 'questao' ];

$questao = $alunoDAO->getQuestao( $Tid, $Lid, $Qid );
if ( $questao == null ) { // nao achou a questao com o Tid, Lid e Qid fornecido
  header( 'Location: questoes.php?turma=' . $Tid . '&lista=' . $Lid );
  exit();
}

$resposta = $alunoDAO->getSubmetida( $Tid, $Lid, $Qid );
//$correcao = $alunoDAO->getCorrecao( $Tid, $Lid, $Qid );


$Qtitulo   = noHTML( $questao[ 'Qtitulo' ] );
$Qcorpo    = $questao[ 'corpo' ];
$Qalfabeto = noHTML( $questao[ 'alfabeto' ] );
$TLid      = $questao[ 'TLid' ];

if ( $resposta == null ) {
  $DFAdesign = '';
} else {
  $DFAdesign = noHTML( $resposta[ 'respostaDesigner' ] );
}
$expirou = $alunoDAO->estorouDeadline( $Tid, $Lid );

$pagAtiva     = 'RESPOSTA';
$tituloPag    = 'Resolvendo Questão';
$tituloHeader = 'Resolva esta Questão';
$backPag      = 'questoes.php?turma=' . $Tid . '&lista=' . $Lid;
$DFAjs        = true;
$mathjax      = true;
include '_header.php'; ?>
  <div id="HdV_DFA">
    <div class="pergunta">
      <h2><?= $Qtitulo ?></h2>
      <div id="Qcorpo">
        <?= $Qcorpo ?>
      </div>
      <input type="hidden" name="txt_alfabeto" id="txt_alfabeto" value="<?= $Qalfabeto ?>" />
      <div id="sigma"><b>&Sigma;</b> = { }</div>
      <div>
        Responda abaixo:
        <a style="position: relative;float: right;"
         tooltip="Para aprender a utilizar o Construtor de Autômatos Finitos Determinísticos, visite a página de AJUDA no menu Opções"
         href="ajuda.php"
         target="_blank">
          <img src="../img/menu_ajuda_p.png" />
        </a>
      </div>
    </div>
    <form action="submissao.php" method="post">
      <input type="hidden" name="Qid" value="<?= $Qid ?>" />
      <input type="hidden" name="Tid" value="<?= $Tid ?>" />
      <input type="hidden" name="Lid" value="<?= $Lid ?>" />
      <input type="hidden" name="TLid" value="<?= $TLid ?>" />

      <canvas id="canvas" class="DFA" width="915px" height="680">
        <span style="display: block;color: red;font-size: 28px;line-height: 30px;padding: 30px;">
          Seu Navegador não suporta <br> o elemento &lt;canvas&gt; do HTML5
        </span>
      </canvas>
      <input type="hidden" name="DFAdesign" id="DFAdesign" value="<?= $DFAdesign ?>" />
      <input type="hidden" name="DFAdados" id="DFAdados" value="" />
      <input type="hidden" name="tipoDeSubmissao" id="tipoDeSubmissao" value="" />

      <div id="erros" class="hidden"></div>

      <div id="correcao">
        <?php if ( $resposta == null ) {
        } else if ( $resposta[ 'status' ] == $alunoDAO->status_CORRIGIDO_CORRETO ) { ?>

          <div id="resultado" class="acertou">
            <img src="../img/correto_b.png">
            <div>CORRETO !!!</div>
          </div>
          <?php
        } else if ( $resposta[ 'status' ] == $alunoDAO->status_CORRIGIDO_INCORRETO ) { ?>

          <div id="resultado" class="errou">
            <img src="../img/incorreto_b.png">
            <div>
              INCORRETO<br /> palavra dica: "<?= $resposta[ 'palavra' ]; ?>"
            </div>
          </div>
          <?php
        } else if ( $resposta[ 'status' ] == $alunoDAO->status_SALVO_RASCUNHO ) { ?>

          <div id="resultado" class="salvo">
            <img src="../img/salvo_b.png">
            <div>Salvo como Rascunho</div>
          </div>
        <?php } ?>

      </div>
      <?php
      if ( $expirou ) { ?>
        <div class="limite">
          <img src="../img/alert_b.png">
          <div>
            A data de tolerância para responder esta Questão já passou. <br /> Você não pode mais submeter uma resposta.
          </div>
        </div>
      <?php } else { ?>
        <div class="botoes">

          <button type="submit"
                  name="BTN_salvar"
                  id="BTN_salvar"
                  tooltip="Clique aqui para SALVAR sua resposta como rascunho"
                  onclick="return DFA_SAVE()">
            SALVAR
          </button>

          <button type="submit"
                  name="BTN_submeter"
                  id="BTN_submeter"
                  tooltip="Clique aqui para CORRIGIR sua Resposta"
                  onclick="return DFA_SUBMIT()">
            SUBMETER
          </button>
        </div>
      <?php } ?>
    </form>
  </div>
  <script type="application/javascript">
    window.onload = function () {
      printAlfabeto();
      DFA_init();
    };
  </script>

<?php include '_footer.php'; ?>