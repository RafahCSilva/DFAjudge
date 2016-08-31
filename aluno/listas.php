<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( false );

if ( !isset( $_GET[ 'turma' ] ) ) {
  header( 'Location: turmas.php' );
  exit();
}
$Tid   = $_GET[ 'turma' ];
$turma = $alunoDAO->getTurma( $Tid );

if ( $turma == null ) { // nao encontrou as listas com o Tid fornecido
  header( 'Location: turmas.php' );
  exit();
}

$listas = $alunoDAO->getListas( $Tid );

$pagAtiva     = 'LISTAS';
$tituloPag    = 'Listas';
$tituloHeader = 'Listas desta Turma';
$backPag      = 'turmas.php';
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
  </table>

  <p>
    Clique sobre uma Lista para visualizar suas Questões: </p>
  <table class="HdV_listar">
    <tr>
      <th width="50%"
          tooltip="Título da Lista">
        Título
      </th>
      <th width="30%"
          tooltip="Data Limite para a entrega da Lista">
        Data Limite
      </th>
      <th width="20%"
          tooltip="Número de acertos e total de Questões">
        Acertos
      </th>
    </tr>
    <?php foreach ( $listas as $row ) { ?>
      <tr>
        <td>
          <a href="questoes.php?turma=<?= $Tid ?>&lista=<?= $row[ "Lid" ]; ?>"
             style="padding-left: 35px">
            <?= noHTML( $row[ "Ltitulo" ] ); ?>
          </a>
        </td>
        <td style="text-align: center;">
          <?php if ( $alunoDAO->estorouDeadline( $Tid, $row[ "Lid" ] ) ) { ?>
            <a href="questoes.php?turma=<?= $Tid ?>&lista=<?= $row[ "Lid" ]; ?>"
               style="color: #ffc2b3;"
               tooltip="A data de tolerância &#10;para responder esta &#10;Lista já passou. &#10;Você não pode mais &#10;submeter uma resposta.">
              <?= date_format( date_create( $row[ 'dia' ] . 'T' . $row[ 'hora' ] ), "d/m/Y H:i" ); ?>
              <img src="../img/alert_x24_vermelhoclaro.png"
                   style="margin-left: 15px; padding: 0;vertical-align: text-bottom; position:absolute;float:right;"> </a>
          <?php } else { ?>
            <a href="questoes.php?turma=<?= $Tid ?>&lista=<?= $row[ "Lid" ]; ?>">
              <?= date_format( date_create( $row[ 'dia' ] . 'T' . $row[ 'hora' ] ), "d/m/Y H:i" ); ?>
            </a>
          <?php } ?>
        </td>
        <td style="text-align: center">
          <a href="questoes.php?turma=<?= $Tid ?>&lista=<?= $row[ "Lid" ]; ?>">
            <?= $alunoDAO->getNumQuestoesAcerto( $Tid, $row[ "Lid" ] ) . ' / ' . $alunoDAO->getNumQuestoes( $row[ "Lid" ] ); ?>
          </a>
        </td>
      </tr>
    <?php } ?>
  </table>
<?php include '_footer.php'; ?>