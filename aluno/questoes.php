<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( false );

if ( !isset( $_GET[ 'turma' ] ) ) {
  header( 'Location: turmas.php' );
  exit();
} else if ( !isset( $_GET[ 'lista' ] ) ) {
  header( 'Location: listas.php?turma=' . $_GET[ 'turma' ] );
  exit();
}

$Tid = $_GET[ 'turma' ];
$Lid = $_GET[ 'lista' ];

$turma = $alunoDAO->getTurma( $Tid );
if ( $turma == null ) { // nao achou a turma com o Tid fornecido
  header( 'Location: turmas.php' );
  exit();
}
$lista = $alunoDAO->getLista( $Lid );
if ( $lista == null ) { // nao achou a lista com o Lid fornecido
  header( 'Location: listas.php?turma=' . $Tid );
  exit();
}
$questoes = $alunoDAO->getQuestoes( $Tid, $Lid );


$expirou = $alunoDAO->estorouDeadline( $Tid, $Lid );

function status( $s ) {
  if ( $s != null ) {
    switch ( $s[ 'status' ] ) {
      case 'C':
        return ' tooltip="Resposta Correta"><img src="../img/correto_x36_b.png"';
      case 'I':
        return ' tooltip="Resposta Incorreta"><img src="../img/incorreto_x36_b.png"';
      case 'S':
        return ' tooltip="Salvo como Rascunho"><img src="../img/salvo_x36_b.png"';
    }
  }

  return ' tooltip="Não Respondida"><img src="../img/vazio.png"';
}

$pagAtiva     = 'QUESTOES';
$tituloPag    = 'Questões';
$tituloHeader = 'Questões desta Lista';
$backPag      = 'listas.php?turma=' . $Tid;
$mathjax      = true;
include '_header.php'; ?>

<table class="HdV_form" style="width: 100%; margin-bottom: 1.5em">
  <tr>
    <td width="25%"><b>Nome da Turma</b></td>
    <td><?= noHTML( $turma[ 'Tnome' ] ); ?></td>
  </tr>
  <tr>
    <td><b>Quadrimestre</b></td>
    <td><?= noHTML( $turma[ 'quad' ] ); ?></td>
  </tr>
  <tr>
    <td><b>Professor</b></td>
    <td><?= noHTML( $turma[ 'nomeProf' ] ); ?></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td><b>Lista</b></td>
    <td><?= noHTML( $lista[ 'Ltitulo' ] ); ?></td>
  </tr>
  <tr>
    <td><b>Data Limite</b></td>
    <?php if ( $expirou ) { ?>
      <td style="color: #F00;">
        <span tooltip="A data de tolerância &#10;para responder esta &#10;Lista já passou. &#10;Você não pode mais &#10;submeter uma resposta.">
          <?= date_format( date_create( $lista[ 'dia' ] . 'T' . $lista[ 'hora' ] ), "d/m/Y H:i" ); ?>
          <img src="../img/alert_x24_vermelho.png"
               style="margin-left: 15px; padding: 0;vertical-align: text-bottom;">
        </span>
      </td>
    <?php } else { ?>
      <td>
        <span tooltip="O prazo máximo para a entrega desta Lista é dia <?= date_format( date_create( $lista[ 'dia' ] . 'T' . $lista[ 'hora' ] ), "d/m/Y" ) ?> as <?= date_format( date_create( $lista[ 'dia' ] . 'T' . $lista[ 'hora' ] ), "H:i" ) ?> ">
          <?= date_format( date_create( $lista[ 'dia' ] . 'T' . $lista[ 'hora' ] ), "d/m/Y H:i" ); ?>
        </span>
      </td>
    <?php } ?>
  </tr>
  <tr>
    <td><b>Acertos</b></td>
    <td>
      <?php
      $c = $alunoDAO->getNumQuestoesAcerto( $Tid, $Lid );
      $t = $alunoDAO->getNumQuestoes( $Lid );
      echo '<span tooltip="Você acertou ' . $c . ' Questões de ' . $t . ' no Total">' . $c . ' de ' . $t . '</span>';
      ?>
    </td>
  </tr>
</table>

<p>Clique sobre uma Questão para responde-la:</p>
<table class="HdV_listar">
  <tr>
    <th width="80%"
        tooltip="Título da Questão">
      Título
    </th>
    <th width="20%"
        tooltip="Correção do Juiz da sua Resposta">
      Correção
    </th>
  </tr>
  <?php foreach ( $questoes as $row ) { ?>
    <tr>
      <td>
        <a href="resposta.php?turma=<?= $Tid; ?>&lista=<?= $Lid; ?>&questao=<?= $row[ 'Qid' ]; ?>"
           style="padding-left: 35px">
          <?= noHTML( $row[ 'Qtitulo' ] ); ?>
        </a>
      </td>
      <td style="text-align: center">
        <a style="padding: 0"
           href="resposta.php?turma=<?= $Tid; ?>&lista=<?= $Lid; ?>&questao=<?= $row[ 'Qid' ]; ?>"
        <?= status( $alunoDAO->getCorrecao( $Tid, $Lid, $row[ 'Qid' ] ) ); ?> > </a>
      </td>
    </tr>
  <?php } ?>
</table>

<?php if ( $expirou ) { ?>
  <div class="limite">
    <img src="../img/alert_b.png">
    <div>
      A data de tolerância para responder esta Lista já passou. <br /> Você não pode mais submeter uma resposta.
    </div>
  </div>
<?php } ?>


<?php include '_footer.php'; ?>
