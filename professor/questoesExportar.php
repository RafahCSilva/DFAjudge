<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );
if ( isset( $_GET[ 'exportar' ] ) ) { //  EXPORTAR

  if ( isset( $_POST[ 'questoes' ] ) ) {
    $listQuestoes = $_POST[ 'questoes' ];
    $questoes     = $profDAO->questoesExport( $listQuestoes );

    $lista = array();
    foreach ( $questoes as $q ) {
      $item               = array();
      $item[ 'titulo' ]   = utf8_encode( $q[ 'Qtitulo' ] );
      $item[ 'corpo' ]    = utf8_encode( $q[ 'corpo' ] );
      $item[ 'alfabeto' ] = utf8_encode( $q[ 'alfabeto' ] );
      $item[ 'designer' ] = utf8_encode( $q[ 'gabaritoDesigner' ] );
      $item[ 'dados' ]    = utf8_encode( $q[ 'gabaritoDados' ] );

      $lista[] = $item;
    }
    $json = json_encode( $lista );

    $outputBuffer = fopen( "php://output", 'w' );
    header( "Content-Type:application/json" );
    header( "Content-Disposition:attachment;filename=exportQuestoes_" . date( 'Ymd-His' ) . ".json" );
    header( "Pragma: no-cache" );
    header( "Expires: 0" );
    fwrite( $outputBuffer, $json );
    fclose( $outputBuffer );

    exit();

  } else {
    die( "Nenhuma Questão selecionada para ser Exportada" );
  }


} else if ( isset( $_GET[ 'importar' ] ) ) { //  IMPORTAR
  if ( !isset( $_FILES ) ) {
    die( 'O arquivo não fez upload' );
  }
  $nomeOrig = $_FILES[ 'jsonImport' ][ 'name' ];
  $localTmp = $_FILES[ 'jsonImport' ][ 'tmp_name' ];

  $arquivo = fopen( $localTmp, 'r' ) or die( "Arquivo não pode ser aberto!" );
  $conteudo = fread( $arquivo, filesize( $localTmp ) );
  fclose( $arquivo );
  $questoes = json_decode( $conteudo );
  if ( $questoes == null ) {
    die( "JSON invalido" );
  }
  foreach ( $questoes as $q ) {
    $Qtitulo   = utf8_decode( $q->titulo );
    $Qcorpo    = utf8_decode( $q->corpo );
    $Qalfabeto = utf8_decode( $q->alfabeto );
    $DFAdesign = utf8_decode( $q->designer );
    $DFAdados  = utf8_decode( $q->dados );
    $profDAO->novaQuestao( $Qtitulo, $Qcorpo, $Qalfabeto, $DFAdesign, $DFAdados );
  }

  header( "Location: questoes.php" );
  exit();
}

$allQuestoes = $profDAO->getQuestoes();


$tituloPag    = 'Exportar Questões';
$tituloHeader = 'Importar/Exportar Questões';
$backPag      = 'questoes.php';

$pagAtiva = 'QUESTOES';
$mathjax  = true;
include '_header.php';

?>
<form action="questoesExportar.php?importar"
      enctype="multipart/form-data"
      method="post">
  <table class="HdV_form"
         style="width: 100%">
    <tr>
      <td colspan="2"><label for="">Escolha o arquivo JSON para ser Importado:</label></td>
    </tr>
    <tr>
      <td>
        <input type="hidden"
               name="MAX_FILE_SIZE"
               value="200000" />
        <input type="file"
               name="jsonImport"
               class="upfile"
               tooltip="Adicione o arquivo a ser importado"
               required />
      </td>
      <td style="text-align: right">
        <button type="submit"
                name="BTN_Importar"
                class="importar"
                tooltip="Importar arquivo de Questões">
          Importar
        </button>
      </td>
    </tr>
  </table>
</form><br /><br />


<script type="application/javascript">
  function checkAll ( ele, target ) {
    var checkboxes = document.getElementsByName( target );
    for ( var i = 0; i < checkboxes.length; i++ ) {
      checkboxes[ i ].checked = ele.checked;
    }
  }
</script>

<form action="questoesExportar.php?exportar"
      method="post">
  <table class="HdV_form"
         style="width: 100%">
    <td colspan="2">
      <label for="">Selecione as Questões a serem Exportadas:</label> <br>
      <table class="HdV_listar">
        <tr>
          <th width="5%"
              style="padding-left:15px">
            <input type="checkbox"
                   name="checarTodos"
                   onchange="checkAll(this, 'questoes[]' )"
                   tooltip="Clique aqui para (des)selecionar todos">
          </th>
          <th width="95%">Título da Questão</th>
        </tr>
        <?php foreach ( $allQuestoes as $questao ) { ?>

          <tr>
            <td style="padding-left:15px">
              <input type="checkbox"
                     name="questoes[]"
                     value="<?= $questao[ 'Qid' ] ?>">
            </td>
            <td style="padding-left:10px"><?= noHTML( $questao[ 'Qtitulo' ] ) ?></td>
          </tr>
        <?php } ?>
      </table>

    </td>
    </tr>
    <tr>
      <td colspan="2"
          style="text-align: right">
        <button type="submit"
                name="BTN_Exportar"
                class="exportar"
                tooltip="Exportar Questões">
          Exportar
        </button>
      </td>
    </tr>

  </table>
</form>
<?php include '_footer.php'; ?>
