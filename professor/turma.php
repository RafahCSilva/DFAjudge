<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );
include_once '../private/class.resetsenha.php';

//function DUMP3( $var ) {
//  echo "\n<pre>\n" . print_r( $var, true ) . "\n</pre>\n";
//}

if ( isset( $_GET[ 'editarTurma' ] ) ) { // EDITAR TURMA
  if ( !isset( $_GET[ 'Tid' ] ) ) {
    header( "Location: turmas.php" );
    exit();
  }

  $Tid = $_GET[ 'Tid' ];

  $turma = $profDAO->getTurma( $Tid ); // [(Tid, Tnome, quad)...]
  if ( $turma == null ) {
    header( "Location: turmas.php" );
    exit();
  }
  $nomeTurma = noHTML( $turma[ 'Tnome' ] );
  $quadTurma = noHTML( $turma[ 'quad' ] );

  $alunos      = $profDAO->getUsuariosAluno();
  $listAlunosR = $profDAO->getTurmaAlunos( $Tid );
  $listAlunos  = array();
  foreach ( $listAlunosR as $a ) {
    $listAlunos[] = $a[ 'Uid' ];
  }

  $listas      = $profDAO->getListas();
  $listListasR = $profDAO->getListasDaTurma( $Tid ); //[Lid, Ltitulo, dia, hora)]
  $listListas  = array();
  $deadline    = array();
  foreach ( $listListasR as $l ) {
    $listListas[]            = $l[ 'Lid' ];
    $deadline[ $l[ 'Lid' ] ] = array( date_format( date_create( $l[ 'dia' ] ), "d/m/Y" ),
                                      date_format( date_create( $l[ 'hora' ] ), "H:i" ) );
  }

  $acao = 'EDITAR';

} else if ( isset( $_GET[ 'novaTurma' ] ) ) { // NOVA TURMA
  $Tid       = "";
  $nomeTurma = "";
  $quadTurma = "";

  $alunos = $profDAO->getUsuariosAluno();
  $listas = $profDAO->getListas();

  $acao = "NOVO";

} else if ( isset( $_POST[ 'salvar' ] ) ) { // SALVAR TURMA

  $TXTtid     = $_POST[ 'txt_id' ];
  $TXTnome    = $_POST[ 'txt_nome' ];
  $TXTquad    = $_POST[ 'txt_quad' ];
  $TXTlote    = $_POST[ 'txt_lote' ];
  $listAlunos = isset( $_POST[ 'alunos' ] ) ? $_POST[ 'alunos' ] : null;
  $listListas = isset( $_POST[ 'listas' ] ) ? $_POST[ 'listas' ] : null;

  $deadline = array();
  foreach ( $listListas as $l ) {
    $dia = $_POST[ 'dia_' . $l ];
    $dia = date_format( date_create_from_format( "d/m/Y", $dia ), "Y-m-d" );

    $deadline[ $l ] = array( $dia,
                             $_POST[ 'hora_' . $l ] . ':00' );
  }

  // ADD EM LOTE
  if ( $TXTlote != '' ) {
    // === ler o csv
    $csvAlunos = str_getcsv( $TXTlote, "\n" );
    foreach ( $csvAlunos as &$row ) {
      $row      = str_getcsv( $row, ";" ); // RA;NOME COMPLETO;EMAIL\n
      $row[ 0 ] = str_replace( ' ', '', $row[ 0 ] ); // RA
      $row[ 2 ] = str_replace( ' ', '', $row[ 2 ] ); // EMAIL
    }

    // === add todos
    foreach ( $csvAlunos as &$row ) {
      $ra    = $row[ 0 ];
      $nome  = $row[ 1 ];
      $email = $row[ 2 ];

      // verificar se ele existe
      $id = $profDAO->getUsuarioIdByEmail( $email );

      if ( $id == -1 ) { // user nao existe
        //add o aluno ao BD
        $profDAO->novoUsuario( $ra, $nome, $email, 0 );
        // get ultimo id adicionado
        $id = $DB_con->lastInsertId();
        // envia email de Bem-vindo
        $reset = new RESETSENHA( $DB_con, $baseSITE );
        $reset->_bemVindo( $email );
      }
      $listAlunos[] = $id;
    }
  }

  $profDAO->alterarTurma( $TXTtid, $TXTnome, $TXTquad, $listListas, $listAlunos, $deadline );

  header( "Location: turmas.php" );
  exit();


} else if ( isset( $_POST[ 'inserir' ] ) ) { // INSERIR TURMA

  $TXTnome    = $_POST[ 'txt_nome' ];
  $TXTquad    = $_POST[ 'txt_quad' ];
  $TXTlote    = $_POST[ 'txt_lote' ];
  $listAlunos = isset( $_POST[ 'alunos' ] ) ? $_POST[ 'alunos' ] : array();
  $listListas = isset( $_POST[ 'listas' ] ) ? $_POST[ 'listas' ] : array();

  $deadline = array();
  foreach ( $listListas as $l ) {
    $dia = $_POST[ 'dia_' . $l ];
    $dia = date_format( date_create_from_format( "d/m/Y", $dia ), "Y-m-d" );

    $deadline[ $l ] = array( $dia,
                             $_POST[ 'hora_' . $l ] . ':00' );
  }

  // ADD EM LOTE
  if ( $TXTlote != '' ) {
    // === ler o csv
    $csvAlunos = str_getcsv( $TXTlote, "\n" );
    foreach ( $csvAlunos as &$row ) {
      $row      = str_getcsv( $row, ";" ); // RA;NOME COMPLETO;EMAIL\n
      $row[ 0 ] = str_replace( ' ', '', $row[ 0 ] ); // RA
      $row[ 2 ] = str_replace( ' ', '', $row[ 2 ] ); // EMAIL
    }

    // === add todos
    foreach ( $csvAlunos as &$row ) {
      $ra    = $row[ 0 ];
      $nome  = $row[ 1 ];
      $email = $row[ 2 ];

      // verificar se ele existe
      $id = $profDAO->getUsuarioIdByEmail( $email );
      if ( $id == -1 ) { // user nao existe
        //add o aluno ao BD
        $profDAO->novoUsuario( $ra, $nome, $email, 0 );
        // get ultimo id adicionado
        $id = $DB_con->lastInsertId();
        // envia email de Bem-vindo
        $reset = new RESETSENHA( $DB_con, $baseSITE );
        $reset->_bemVindo( $email );
      }
      $listAlunos[] = $id;
    }
  }

  $profDAO->novaTurma( $TXTnome, $TXTquad, $listListas, $listAlunos, $deadline );

  header( "Location: turmas.php" );
  exit();

} else if ( isset( $_GET[ 'excluirTurma' ] ) ) {  /// EXCLUIR TURMA
  if ( !isset( $_GET[ 'Tid' ] ) ) {
    header( "Location: turmas.php" );
    exit();
  }
  $Tid = $_GET[ 'Tid' ];
  $profDAO->deletaTurma( $Tid );
  header( "Location: turmas.php" );
  exit();

} else {
  // voltar pro listar turmas pq nao tem parametro correto
  header( "Location: turmas.php" );
  exit();
}

$tituloPag = 'Turma';
if ( $acao == "NOVO" ) {
  $tituloHeader = "Novo Turma";
} else if ( $acao == "EDITAR" ) {
  $tituloHeader = "Editar Turma";
}
$backPag  = 'turmas.php';
$pagAtiva = 'TURMAS';
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
<form action="turma.php" method="post">
  <input type="hidden" name="txt_id" value="<?= $Tid ?>">
  <table class="HdV_form" style="width: 100%">
    <tr>
      <td>
        <label for="txt_nome">Nome da Turma</label>
      </td>
      <td>
        <input type="text"
               name="txt_nome"
               title="Digite o nome da turma"
               placeholder="Digite o nome da turma"
               maxlength="100"
               size="60"
               required
               autofocus
               value="<?= $nomeTurma ?>">
      </td>
    </tr>
    <tr>
      <td>
        <label for="txt_quad">Quadrimestre</label>
      </td>
      <td>
        <input type="text"
               name="txt_quad"
               title="Digite o quadrimestre"
               placeholder="Digite o quadrimestre"
               maxlength="15"
               size="15"
               required
               value="<?= $quadTurma ?>">
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <script>
          function verAlunos () {
            document.getElementById( "verA" ).className = 'btn_show';
            document.getElementById( "verL" ).className = 'btn_hide';

            document.getElementById( "verA" ).setAttribute( "disabled", "" );
            document.getElementById( "verL" ).removeAttribute( "disabled" );

            document.getElementById( "alunos" ).className = 'TDshow';
            document.getElementById( "listas" ).className = 'TDhide';
          }
          function verListas () {
            document.getElementById( "verA" ).className = 'btn_hide';
            document.getElementById( "verL" ).className = 'btn_show';

            document.getElementById( "verA" ).removeAttribute( "disabled" );
            document.getElementById( "verL" ).setAttribute( "disabled", "" );

            document.getElementById( "alunos" ).className = "TDhide";
            document.getElementById( "listas" ).className = 'TDshow';
          }
        </script>
        <input type="button"
               id="verA"
               style="margin-right: -2px"
               class="btn_show"
               onclick="verAlunos();"
               value="VER ALUNOS" />

        <input type="button"
               id="verL"
               style="margin-left: -2px"
               class="btn_hide"
               onclick="verListas();"
               value="VER LISTAS" />

        <div id="alunos" class="TDshow">
          <h3 style="margin: 0">Selecione os Alunos para esta Turma:</h3> <br>

          <script>
            function toggle () {
              if ( document.getElementById( 'lote' ).style.display == 'none' ) {
                document.getElementById( 'lote' ).style.display        = 'block';
                document.getElementById( 'listaAlunos' ).style.display = 'none';
              } else {
                document.getElementById( 'lote' ).style.display        = 'none';
                document.getElementById( 'listaAlunos' ).style.display = 'block';
              }
            }
          </script>
          <label>
            <input type="checkbox"
                   name="quer_lote"
                   class="cb"
                   onclick="toggle()">
            Deseja adicionar usuários nesta turma em lote?
          </label><br /><br />
          <div id="lote"
               style="display:none;">
            <label>Cole aqui o CSV:<br>
            <textarea name="txt_lote"
                      id="txt_lote"
                      rows="10"
                      cols="80"
                      placeholder="RA;NOME COMPLETO;EMAIL\n"
                      style="width: 840px"></textarea></label>
          </div>

          <div id="listaAlunos"
               style="display:block;">
            <table class="HdV_listar">
              <tr>
                <th width="5%"
                    style="padding-left:15px">
                  <input type="checkbox"
                         class="cb"
                         name="checarTodos"
                         onchange="checkAll(this, 'alunos[]' )"
                         tooltip="Clique aqui para (des)selecionar todos">
                </th>
                <th width="95%">Nome do Aluno</th>
              </tr>
              <?php foreach ( $alunos as $aluno ) { ?>
                <tr>
                  <td style="padding-left:15px">
                    <input type="checkbox"
                           name="alunos[]"
                           class="cb"
                           value="<?= $aluno[ 'Uid' ] ?>" <?= ( ( $acao == 'EDITAR' and in_array( $aluno[ 'Uid' ], $listAlunos ) ) ? 'checked' : '' ) ?> >
                  </td>
                  <td style="padding-left:10px">
                    <?= noHTML( $aluno[ 'nome' ] ) ?>
                  </td>
                </tr>
              <?php } ?>
            </table>
          </div>

        </div>

        <div id="listas"
             class="TDhide">

          <script type="application/javascript">
            function checkDeadLine () {
              var checkboxes = document.getElementsByName( 'listas[]' );
              for ( var i = 0; i < checkboxes.length; i++ ) {
                var check      = checkboxes[ i ];
                var datap      = document.getElementById( 'dia_' + check.value );
                datap.required = check.checked;
                datap.readOnly = !check.checked;
                var timep      = document.getElementById( 'hora_' + check.value );
                timep.required = check.checked;
                timep.readOnly = !check.checked;
              }
            }
          </script>

          <h3 style="margin: 0">Selecione as Listas para esta Turma:</h3> <br>
          <table class="HdV_listar">
            <tr>
              <th width="5%"
                  style="padding-left:15px">
                <input type="checkbox"
                       name="checarTodos"
                       onchange="checkAll(this, 'listas[]' );checkDeadLine()"
                       tooltip="Clique aqui para (des)selecionar todos">
              </th>
              <th width="65%">Título da Lista</th>
              <th width="30%"
                  colspan="2">Deadline
              </th>
            </tr>
            <?php foreach ( $listas as $lista ) { ?>

              <tr>
                <td style="padding-left:15px">
                  <input type="checkbox"
                         name="listas[]"
                         class="cb"
                         onchange="checkDeadLine()"
                         value="<?= $lista[ 'Lid' ] ?>" <?= ( ( $acao == 'EDITAR' and in_array( $lista[ 'Lid' ], $listListas ) ) ? 'checked' : '' ) ?> />
                </td>
                <td style="padding-left:10px"><?= noHTML( $lista[ 'Ltitulo' ] ) ?></td>
                <td>
                  <input type="datepicker"
                         name="dia_<?= $lista[ 'Lid' ] ?>"
                         id="dia_<?= $lista[ 'Lid' ] ?>"
                         title="Defina a data limite"
                         maxlength="10"
                         size="10"
                         placeholder="dd/mm/aaaa"
                         value="<?= ( ( $acao == 'EDITAR' and in_array( $lista[ 'Lid' ], $listListas ) ) ? $deadline[ $lista[ 'Lid' ] ][ 0 ] : '' ) ?>"
                  <?= ( ( $acao == 'EDITAR' and in_array( $lista[ 'Lid' ], $listListas ) ) ? 'required' : 'readonly' ) ?> />
                </td>
                <td>
                  <input type="timepicker"
                         name="hora_<?= $lista[ 'Lid' ] ?>"
                         id="hora_<?= $lista[ 'Lid' ] ?>"
                         title="Defina a hora limite"
                         maxlength="5"
                         size="5"
                         placeholder="hh:mm"
                         value="<?= ( ( $acao == 'EDITAR' and in_array( $lista[ 'Lid' ], $listListas ) ) ? $deadline[ $lista[ 'Lid' ] ][ 1 ] : '' ) ?>"
                  <?= ( ( $acao == 'EDITAR' and in_array( $lista[ 'Lid' ], $listListas ) ) ? 'required' : 'readonly' ) ?> />
                </td>
              </tr>

            <?php } ?>
          </table>
        </div>
      </td>
    </tr>

    <tr>
      <td></td>
      <td style="text-align: right">
        <?php if ( $acao == "EDITAR" ) { ?>
          <button type="submit"
                  class="salvar"
                  name="salvar">SALVAR
          </button>
        <?php } else if ( $acao == "NOVO" ) { ?>
          <button type="submit"
                  class="inserir"
                  name="inserir">INSERIR
          </button>
        <?php } ?>
      </td>
    </tr>

  </table>
</form>
<script src="../js/jquery-1.10.2.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.maskedinput.min.js"></script>
<link rel="stylesheet"
      href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<style type="text/css">
  .ui-widget {
    font-family : Verdana, Arial, sans-serif;
    font-size   : 0.8em;
  }
</style>
<script type="application/javascript">
  $( function () {
    $( "input[type=datepicker]" ).datepicker( {
      dateFormat        : 'dd/mm/yy',
      altFormat         : 'dd/mm/yy',
      dayNames          : [ 'Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo' ],
      dayNamesMin       : [ 'D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D' ],
      dayNamesShort     : [ 'Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom' ],
      monthNames        : [ 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro' ],
      monthNamesShort   : [ 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez' ],
      gotoCurrent       : true,
      autoSize          : true,
      minDate           : new Date( 2016, 0, 1 ),
      changeMonth       : true,
      changeYear        : true,
      currentText       : 'Hoje',
      weekHeader        : 'Sm',
      firstDay          : 0,
      isRTL             : false,
      prevText          : '&lt; Anterior',
      prevStatus        : 'Mostra o mês anterior',
      prevJumpText      : '&lt;&lt;',
      prevJumpStatus    : 'Mostra o ano anterior',
      nextText          : 'Próximo &gt;',
      nextStatus        : 'Mostra o próximo mês',
      nextJumpText      : '&gt;&gt;',
      nextJumpStatus    : 'Mostra o próximo ano',
      currentStatus     : 'Mostra o mês atual',
      todayText         : 'Hoje',
      todayStatus       : 'Vai para hoje',
      clearText         : 'Limpar',
      clearStatus       : 'Limpar data',
      closeText         : 'Fechar',
      closeStatus       : 'Fechar o calendário',
      yearStatus        : 'Selecionar ano',
      monthStatus       : 'Selecionar mês',
      weekText          : 's',
      weekStatus        : 'Semana do ano',
      dayStatus         : 'DD, d \'de\' M \'de\' yyyy', defaultStatus: 'Selecione um dia',
      showAnim          : 'slideDown',
      showMonthAfterYear: false
    } );//.attr('type','text');
    $( "input[type=datepicker" ).mask( "99/99/9999" );
    $( "input[type=timepicker" ).mask( "99:99" );
  } );
</script>

<?php include '_footer.php'; ?>

