<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( false );

include_once '../private/class.DFAjudge.php';
try {
  $tSubmissao = $_POST[ 'tipoDeSubmissao' ];
  $Qid        = $_POST[ 'Qid' ];
  $TLid       = $_POST[ 'TLid' ];
  $Tid        = $_POST[ 'Tid' ];
  $Lid        = $_POST[ 'Lid' ];
  $DFAdesign  = $_POST[ 'DFAdesign' ];
  $DFAdados   = $_POST[ 'DFAdados' ];

  if ( !$alunoDAO->estorouDeadline( $Tid, $Lid ) ) { // esta dentro do deadline ainda
    $alunoDAO->submissao( $tSubmissao, $Qid, $TLid, $DFAdesign, $DFAdados );

    if ( $tSubmissao == 'submeter' ) {
      $dados = $alunoDAO->getRespostaParaOJuiz( $TLid, $Qid );
      $Sid   = $dados[ 'Sid' ];
      $alf   = $dados[ 'alfabeto' ];
      $gab   = $dados[ 'gabaritoDados' ];
      $res   = $dados[ 'respostaDados' ];
      $dfa   = new DFA( $alf, $gab, $res );

      $hke    = new HKE();
      $result = $hke->corrigir( $dfa );

      if ( $result[ 'resultado' ] == 1 ) {
        $alunoDAO->alterarStatusSubmissao( $Sid, $alunoDAO->status_CORRIGIDO_CORRETO, null );
      } else {
        $alunoDAO->alterarStatusSubmissao( $Sid, $alunoDAO->status_CORRIGIDO_INCORRETO, $result[ 'palavra' ] );
      }
    }
  }

  header( 'Location: resposta.php?turma=' . $Tid . '&lista=' . $Lid . '&questao=' . $Qid . '#correcao' );
  exit();

} catch ( Exception $e ) {
  die( "Erro: <code>" . $e->getMessage() . "</code>" );
}
?>
