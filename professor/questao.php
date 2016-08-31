<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );


if ( isset( $_GET[ 'editarQuestao' ] ) ) { //  EDITAR QUESTAO
  if ( !isset( $_GET[ 'Qid' ] ) ) {
    header( "Location: questoes.php" );
    exit();
  }
  $Qid     =  $_GET[ 'Qid' ] ;

  $questao = $profDAO->getQuestao( $Qid );
  // [(Qid, Qtitulo, corpo, alfabeto, gabaritoDesigner)]
  if ( $questao == null ) {
    header( "Location: questoes.php" );
    exit();
  }

  $Qtitulo   = noHTML( $questao[ 'Qtitulo' ] );
  $Qcorpo    = $questao[ 'corpo' ];
  $Qalfabeto = noHTML( $questao[ 'alfabeto' ] );
  $DFAdesign = noHTML( $questao[ 'gabaritoDesigner' ] );
  $DFAdados  = "";

  $acao = "EDITAR";

} else if ( isset( $_GET[ 'novaQuestao' ] ) ) { //  NOVO QUESTAO
  $Qid       = "";
  $Qtitulo   = "";
  $Qcorpo    = "";
  $Qalfabeto = "";
  $DFAdesign = "";
  $DFAdados  = "";

  $acao = "NOVO";


} else if ( isset( $_POST[ 'BTN_salvar' ] ) ) { //  SALVAR QUESTAO

  $Qid       = $_POST[ 'txt_id' ];
  $Qtitulo   = $_POST[ 'txt_titulo' ];
  $Qcorpo    = $_POST[ 'txt_corpo' ];
  $Qalfabeto = $_POST[ 'txt_alfabeto' ];
  $DFAdesign = $_POST[ 'DFAdesign' ];
  $DFAdados  = $_POST[ 'DFAdados' ];

  $profDAO->alterarQuestao( $Qid, $Qtitulo, $Qcorpo, $Qalfabeto, $DFAdesign, $DFAdados );

  header( "Location: questaoVisualizar.php?Qid=" . $Qid );
  exit();

} else if ( isset( $_POST[ 'BTN_inserir' ] ) ) { //  INSERIR QUESTAO

  $Qtitulo   = $_POST[ 'txt_titulo' ];
  $Qcorpo    = $_POST[ 'txt_corpo' ];
  $Qalfabeto = $_POST[ 'txt_alfabeto' ];
  $DFAdesign = $_POST[ 'DFAdesign' ];
  $DFAdados  = $_POST[ 'DFAdados' ];

  $profDAO->novaQuestao( $Qtitulo, $Qcorpo, $Qalfabeto, $DFAdesign, $DFAdados );

  header( "Location: questoes.php" );
  exit();


} else if ( isset( $_GET[ 'excluirQuestao' ] ) ) {   /// EXCLUIR QUESTAO
  if ( !isset( $_GET[ 'Qid' ] ) ) {
    header( "Location: questoes.php" );
    exit();
  }
  $Qid = $_GET[ 'Qid' ];
  $profDAO->deletaQuestao( $Qid );
  header( "Location: questoes.php" );
  exit();

} else {
  header( "Location: questoes.php" );
  exit();
}

$tituloPag = 'Questão';
if ( $acao == "NOVO" ) {
  $tituloHeader = 'Novo Questão';
} else if ( $acao == "EDITAR" ) {
  $tituloHeader = 'Editar Questão';
}
$backPag  = 'questoes.php';
$DFAjs    = true;
$mathjax  = true;
$pagAtiva = 'QUESTOES';
include '_header.php';
?>

<form action="questao.php"
      method="post">
  <input type="hidden"
         name="txt_id"
         id="txt_id"
         value="<?= $Qid ?>" />
  <table class="HdV_form">
    <tr>
      <td>
        <label for="txt_titulo">Título</label>
      </td>
      <td>
        <input type="text"
               name="txt_titulo"
               id="txt_titulo"
               maxlength="200"
               style="width: 800px;
                      -webkit-box-sizing : border-box;
                      -moz-box-sizing : border-box;
                      box-sizing : border-box;"
               title="Digite o titulo da questão"
               placeholder="Digite o titulo da questão"
               value="<?= $Qtitulo ?>"
               spellcheck="true"
               autofocus
               required />
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;padding-top: 15px">
        <label for="txt_corpo"
               tooltip="Para inserir comandos em LATEX, utilize como delimitadores \( e \) para conteúdo na linha, e \[ e \] para conteúdo em bloco. Elementos HTML são permitidos">
          Corpo
          <img src="../img/menu_ajuda_p.png" />
        </label>
      </td>
      <td style="text-align: left">
        <textarea name="txt_corpo"
                  id="txt_corpo"
                  rows="5"
                  style="width: 800px;
                  -webkit-box-sizing : border-box;
                  -moz-box-sizing : border-box;
                  box-sizing : border-box;"
                  onchange="visualiza()"
                  onload="visualiza()"
                  title="Digite o corpo da questão"
                  placeholder="Digite o corpo da questão"
                  spellcheck="true"
                  required><?= $Qcorpo ?></textarea> <br>

      </td>
    </tr>
    <tr>
      <td>
        <script type="application/javascript">
          function visualiza () {
            var v       = document.getElementById( 'corpoView' );
            var c       = document.getElementById( 'txt_corpo' );
            v.innerHTML = c.value.toString();
            MathJax.Hub.Queue( [ "Typeset", MathJax.Hub, 'corpoView' ] );
          }
          window.onload = function () {
            visualiza();
            printAlfabeto();
            DFA_init();
          };
        </script>
      </td>
      <td>
        <div id="corpoView"
             tooltip="Visualização resultante do Corpo da Questão"
             style="width: 800px;
                    -webkit-box-sizing : border-box;
                    -moz-box-sizing : border-box;
                    box-sizing : border-box;
                    border: 2px solid #FC0;
                    padding: 15px;"></div>
      </td>
    </tr>

    <tr>
      <td style="vertical-align: top;">
        <label for="txt_alfabeto">Alfabeto<label>
      </td>
      <td style="text-align: left">
        <input type="text"
               name="txt_alfabeto"
               id="txt_alfabeto"
               value="<?= $Qalfabeto ?>"
               maxlength=200
               size=60
               style=" width: 800px;
                       -webkit-box-sizing : border-box;
                       -moz-box-sizing : border-box;
                       box-sizing : border-box;"
               required
               title="Digite o alfabeto (símbolos separados por espaço)"
               placeholder="Digite o alfabeto (símbolos separados por espaço)"
               onblur="printAlfabeto()" /><br />
        <div id="sigma"
             style="padding: 10px"><b>&Sigma;</b> = { }
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <label for="canvas">Gabarito</label>
        <a style="position: relative;float: right; margin-right: 15px"
        tooltip="Para aprender a utilizar o Construtor de Autômatos Finitos Determinísticos, visite a página de AJUDA no menu Opções"
        href="ajuda.php"
        target="_blank">
          <img src="../img/menu_ajuda_p.png" />
        </a>

        <canvas id="canvas" class="DFA" width="915px" height="680">
          <span class="error">Seu Navegador não suporta <br> o elemento &lt;canvas&gt; do HTML5</span>
        </canvas>
        <input type="hidden" name="DFAdesign" id="DFAdesign" value="<?= $DFAdesign ?>" />
        <input type="hidden" name="DFAdados" id="DFAdados" value="<?= $DFAdados ?>" />
        <input type="hidden" name="tipoDeSubmissao" id="tipoDeSubmissao" value="VAZIO" />
      </td>
    </tr>

    <tr>
      <td colspan="2">
        <div id="erros" class="hidden"></div>
      </td>
    </tr>
    <tr>
      <td></td>
      <td style="text-align: right">
        <?php if ( $acao == "EDITAR" ) { ?>
          <button type="submit"
                  name="BTN_salvar"
                  id="BTN_salvar"
                  class="salvar"
                  tooltip="Clique aqui para salvar"
                  onclick="return DFA_SUBMIT()">
            SALVAR
          </button>
        <?php } else if ( $acao == "NOVO" ) { ?>
          <button type="submit"
                  name="BTN_inserir"
                  id="BTN_inserir"
                  class="inserir"
                  tooltip="Clique aqui para inserir"
                  onclick="return DFA_SUBMIT()">
            INSERIR
          </button>
        <?php } ?>
      </td>
    </tr>
  </table>
</form>

<?php include '_footer.php'; ?>
