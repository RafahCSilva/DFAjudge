<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );


if ( isset( $_GET[ 'editarLista' ] ) ) { //  EDITAR LISTA
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

  $Ltitulo = noHTML( $lista[ 'Ltitulo' ] );

  $allQuestoes   = $profDAO->getQuestoes();
  $lquestoes     = $profDAO->getQuestoesDaLista( $Lid );
  $allQuestoesID = array();
  foreach ( $lquestoes as $a ) {
    $allQuestoesID[] = $a[ 'Qid' ];
  }

  $acao = "EDITAR";

} else if ( isset( $_GET[ 'novaLista' ] ) ) { //  NOVO LISTA
  $Lid         = "";
  $Ltitulo     = "";
  $allQuestoes = $profDAO->getQuestoes();
  $acao        = "NOVO";

} else if ( isset( $_POST[ 'BTN_salvar' ] ) ) { //  SALVAR LISTA
  $TXTtid       = $_POST[ 'txt_id' ];
  $TXTtitulo    = $_POST[ 'txt_titulo' ];
  $listQuestoes = isset( $_POST[ 'questoes' ] ) ? $_POST[ 'questoes' ] : null;

  $profDAO->alterarLista( $TXTtid, $TXTtitulo, $listQuestoes );

  header( "Location: listas.php" );
  exit();

} else if ( isset( $_POST[ 'BTN_inserir' ] ) ) { //  INSERIR LISTA
  $TXTtitulo    = $_POST[ 'txt_titulo' ];
  $listQuestoes = isset( $_POST[ 'questoes' ] ) ? $_POST[ 'questoes' ] : null;

  $profDAO->novaLista( $TXTtitulo, $listQuestoes );

  header( "Location: listas.php" );
  exit();

} else if ( isset( $_GET[ 'excluirLista' ] ) ) { /// EXCLUIR LISTA
  if ( !isset( $_GET[ 'Lid' ] ) ) {
    header( "Location: listas.php" );
  }
  $Lid = $_GET[ 'Lid' ];
  $profDAO->deletaLista( $Lid );
  header( "Location: listas.php" );

} else {
  header( "Location: questoes.php" ); // voltar pro listar usuarios pq nao tem parametro correto
}


$tituloPag = 'Lista';
if ( $acao == "NOVO" ) {
  $tituloHeader = 'Nova Lista';
} else if ( $acao == "EDITAR" ) {
  $tituloHeader = 'Editar Lista';
}
$backPag = 'listas.php';
//$addPag = 'listas.php';
$pagAtiva = 'LISTAS';
$mathjax  = true;
include '_header.php';
?>
<script type="application/javascript">
  function checkAll ( ele, target ) {
    var checkboxes = document.getElementsByName( target );
    for ( var i = 0; i < checkboxes.length; i++ ) {
      checkboxes[ i ].checked = ele.checked;
    }
  }
</script>
<form action="lista.php"
      method="post">
  <input type="hidden"
         name="txt_id"
         id="txt_id"
         value="<?= $Lid ?>" />
  <table class="HdV_form"
         style="width: 100%">
    <tr>
      <td>
        <label for="txt_titulo">Título da Lista</label>
      </td>
      <td>
        <input type="text"
               name="txt_titulo"
               id="txt_titulo"
               title="Digite o título da lista"
               placeholder="Digite o título da lista"
               maxlength="100"
               size="60"
               value="<?= htmlspecialchars( $Ltitulo ) ?>"
               autofocus
               required />
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <label for="">Listas</label> <br>
        <table class="HdV_listar">
          <tr>
            <th width="5%"
                style="padding-left:15px">
              <input type="checkbox"
                     name="checarTodos"
                     onchange="checkAll(this, 'questoes[]' )"
                     tooltip="Clique aqui para (des)selecionar todos">
            </th>
            <th width="85%">Título da Questão</th>
            <th width="10%">Usos</th>
          </tr>
          <?php if ( $allQuestoes != null ) {
            foreach ( $allQuestoes as $questao ) { ?>

              <tr>
                <td style="padding-left:15px">
                  <input type="checkbox"
                         name="questoes[]"
                         value="<?= $questao[ 'Qid' ] ?>" <?= ( ( $acao == 'EDITAR' and in_array( $questao[ 'Qid' ], $allQuestoesID ) ) ? 'checked' : '' ) ?> >
                </td>
                <td style="padding-left:10px"><?= noHTML( $questao[ 'Qtitulo' ] ) ?></td>
                <td class="tdImgCenter">
                  <?= $profDAO->contUsoQuestoes( $questao[ "Qid" ] ); ?>
                </td>
              </tr>
            <?php }
          } ?>
        </table>

      </td>
    </tr>


    <tr>
      <td></td>
      <td style="text-align: right">
        <?php if ( $acao == "EDITAR" ) { ?>
          <button type="submit"
                  name="BTN_salvar"
                  class="salvar">
            SALVAR
          </button>
        <?php } else if ( $acao == "NOVO" ) { ?>
          <button type="submit"
                  name="BTN_inserir"
                  class="inserir">
            INSERIR
          </button>
        <?php } ?>
      </td>
    </tr>

  </table>
</form>


<?php include '_footer.php'; ?>
