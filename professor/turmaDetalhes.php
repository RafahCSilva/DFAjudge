<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );

if ( isset( $_GET[ 'removerAluno' ] ) ) { //  REMOVER ALUNO
  if ( !isset( $_GET[ 'Tid' ] ) || !isset( $_GET[ 'Aid' ] ) ) {
    header( "Location: turmas.php" );
    exit();
  }
  $Tid = $_GET[ "Tid" ];
  $Aid = $_GET[ "Aid" ];

  $profDAO->removeAlunoTurma( $Tid, $Aid );
  header( "Location:turmaDetalhes.php?Tid=" . $Tid );
  exit();

} else if ( isset( $_GET[ 'removerLista' ] ) ) { //  REMOVER LISTA
  if ( !isset( $_GET[ 'Tid' ] ) && !isset( $_GET[ 'Lid' ] ) ) {
    header( "Location: turmas.php" );
    exit();
  }
  $Tid = $_GET[ "Tid" ];
  $Lid = $_GET[ "Lid" ];

  $profDAO->removeListaTurma( $Tid, $Lid );
  header( "Location:turmaDetalhes.php?Tid=" . $Tid );
  exit();
}

if ( !isset( $_GET[ 'Tid' ] ) ) {
  header( "Location: turmas.php" );
  exit();
}
$Tid   = $_GET[ "Tid" ];
$turma = $profDAO->getTurma( $Tid );
if ( $turma == null ) {
  header( "Location: turmas.php" );
  exit();
}
$alunos = $profDAO->getTurmaAlunos( $Tid );
$listas = $profDAO->getTurmaListas( $Tid );

$tituloPag    = 'Turmas';
$tituloHeader = 'Detalhes da Turma';
$backPag      = 'turmas.php';
$addPag       = 'turma.php?editarTurma&Tid=' . $Tid;
$addTitle     = 'Editar Turma';
$addIcon      = '../img/editar_p.png';
$pagAtiva     = 'TURMAS';
include '_header.php';
?>
<table class="HdV_form"
       style="width: 100%; margin-bottom: 1.5em;">
  <tr>
    <td width="30%"><b>Nome da Turma</b></td>
    <td><?= $turma[ 'Tnome' ]; ?></td>
    <td rowspan="4" style="vertical-align: top;">
      <a style="position: relative;
         float: right;
         margin-right: 15px;"
         tooltip="Clique aqui para exibir o Relatório desta Turma"
         href="relatorioTurma.php?Tid=<?= $Tid; ?>">
        <img src="../img/relatorio_p.png" />
      </a>
    </td>
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

  function deletar1 () {
    return window.confirm( "Deseja realmente remover este Usuário da Turma?" );
  }
  function deletar2 () {
    return window.confirm( "Deseja realmente remover esta Lista da Turma?" );
  }
</script>
<style>
  div.TDshow {
    display : block;
  }

  div.TDhide {
    display : none;
  }

  input.btn_show {
    padding          : 10px 25px;
    /*margin: 0px -2px;*/
    background-color : #FC0;
    color            : black;
    font-weight      : bold;
    border           : inherit;
  }

  input.btn_hide {
    padding          : 10px 25px;
    /*margin: 0px -2px;*/
    background-color : #063;
    color            : white;
    font-weight      : bold;
    border           : inherit;
  }

  #listas {
    border  : 2px solid #063;
    padding : 20px;
  }

  #alunos {
    border  : 2px solid #063;
    padding : 20px;
  }
</style>


<input type="button"
       id="verA"
       style="margin-right: -2px"
       class="btn_show"
       onclick="verAlunos();"
       title="Clique aqui para ver somente os Alunos desta Turma"
       value="VER ALUNOS" /><input type="button"
                                   id="verL"
                                   style="margin-left: -2px"
                                   class="btn_hide"
                                   onclick="verListas();"
                                   title="Clique aqui para ver somente as Listas desta Turma"
                                   value="VER LISTAS" />


<div id="alunos"
     class="TDshow">
  <h3>Alunos pertencentes a Turma</h3>
  <table class="HdV_listar">
    <thead>
    <tr>
      <th width="15%"
          tooltip="RA do Auno">RA
      </th>
      <th width="70%"
          tooltip="Nome do Auno">Nome do Aluno
      </th>
      <th width="15%"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ( $alunos as $aluno ) { ?>
      <tr>
        <td style="padding-left:35px"><?= noHTML( $aluno[ 'RA' ] ); ?></td>
        <td style="padding-left:35px"><?= noHTML( $aluno[ 'nome' ] ); ?></td>
        <td><a href="turmaDetalhes.php?removerAluno&Tid=<?= $Tid; ?>&Aid=<?= $aluno[ 'Uid' ]; ?>"
               tooltip="Remover este Aluno desta Turma"
               onclick="return deletar1();">Remover</a></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
</div>

<div id="listas"
     class="TDhide">
  <h3>Listas aplicadas na Turma</h3>
  <table class="HdV_listar">
    <thead>
    <tr>
      <th width="51%"
          tooltip="Título da Lista">Título da lista
      </th>
      <th width="25%"
          tooltip="Data Limite para a entrega da Lista">Data Limite
      </th>
      <th width="12%"></th>
      <th width="12%"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ( $listas as $lista ) { ?>
      <tr>
        <td style="padding-left:35px"><?= noHTML( $lista[ 'Ltitulo' ] ); ?></td>
        <td><?= date_format( date_create( $lista[ 'dia' ] . 'T' . $lista[ 'hora' ] ), "d/m/Y H:i" ); ?></td>
        <td><a href="relatorioLista.php?Tid=<?= $Tid; ?>&Lid=<?= $lista[ 'Lid' ]; ?>"
               tooltip="Clique aqui para exibir o Relatório desta Turma para somente esta Lista">Relatorio</a></td>
        <td><a href="turmaDetalhes.php?removerLista&Tid=<?= $Tid; ?>&Lid=<?= $lista[ 'Lid' ]; ?>"
               tooltip="Remover esta Lista desta Turma"
               onclick="return deletar2();">Remover</a></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
</div>

<?php include '_footer.php'; ?>
