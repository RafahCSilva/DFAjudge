<?php

class AlunoDAO {

  public $status_SALVO_RASCUNHO      = 'S';
  public $status_AGUARDANDO_CORRECAO = 'A';
  public $status_CORRIGIDO_CORRETO   = 'C';
  public $status_CORRIGIDO_INCORRETO = 'I';
  public $status_ERROR               = 'E';

  private $db;

  public function __construct( \PDO $conn ) {
    $this->db = $conn;
  }

  function getTurmas() {
    // return [ (Tid, Tnome, quad, nomeProf) ... ]
    try {
      $stmt = $this->db->prepare( 'SELECT t.Tid, t.Tnome, t.quad, up.nome AS nomeProf
                                    FROM turmas t
                                    JOIN userturma ut ON ut.Tid=t.Tid
                                    JOIN usuarios up ON t.UidCriador=up.Uid
                                    WHERE ut.Uid=:idUser
                                    ORDER BY t.Tnome;' );
      $stmt->bindValue( ':idUser', $_SESSION[ 'Uid' ] );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getTurma( $Tid ) {
    // return ( Tnome, quad, NomeProf )
    try {
      $stmt = $this->db->prepare( 'SELECT t.Tnome, t.quad, u.nome AS nomeProf
                                    FROM turmas t
                                    JOIN userturma ut ON ut.Tid=t.Tid
                                    JOIN usuarios u ON u.Uid=t.UidCriador
                                    WHERE ut.Tid=:Tid AND ut.Uid=:idUser
                                    ORDER BY t.Tnome;' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':idUser', $_SESSION[ 'Uid' ] );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getListas( $Tid ) {
    // return [ (Lid, Ltitulo, dia, hora)... ]
    try {
      $stmt = $this->db->prepare( 'SELECT l.Lid, l.Ltitulo, tl.dia, tl.hora
                                    FROM listas l
                                    JOIN turmalista tl ON tl.Lid=l.Lid
                                    JOIN turmas t ON t.Tid=tl.Tid
                                    JOIN userturma ut ON ut.Tid=t.Tid
                                    WHERE ut.Uid=:idUser AND t.Tid=:Tid
                                    ORDER BY tl.dia, tl.hora, l.Ltitulo' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':idUser', $_SESSION[ 'Uid' ] );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getLista( $Lid ) {
    // return (Lid, Ltitulo, dia, hora)
    try {
      $stmt = $this->db->prepare( 'SELECT l.Lid, l.Ltitulo, tl.dia, tl.hora
                                    FROM listas l
                                    JOIN turmalista tl ON tl.Lid=l.Lid
                                    JOIN turmas t ON t.Tid=tl.Tid
                                    JOIN userturma ut ON ut.Tid=t.Tid
                                    WHERE ut.Uid=:idUser AND l.Lid=:Lid
                                    ORDER BY l.Ltitulo' );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':idUser', $_SESSION[ 'Uid' ] );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getQuestoes( $Tid, $Lid ) {
    // return [ ( Qid, Qtitulo )... ]
    try {
      $stmt = $this->db->prepare( 'SELECT q.Qid, q.Qtitulo
                                    FROM listas l
                                    JOIN listaquestao lq ON lq.Lid=l.Lid
                                    JOIN questoes q ON q.Qid=lq.Qid
                                    JOIN turmalista tl ON tl.Lid=l.Lid
                                    WHERE tl.Tid=:Tid AND l.Lid=:Lid
                                    ORDER BY lq.pos ASC, q.Qtitulo ASC' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getQuestao( $Tid, $Lid, $Qid ) {
    // return (Qid, Qtitulo, corpo, alfabeto, TLid)
    try {
      $stmt = $this->db->prepare( 'SELECT q.Qid, q.Qtitulo, q.corpo, q.alfabeto, tl.TLid
                                    FROM questoes q
                                    JOIN listaquestao lq ON lq.Qid=q.Qid
                                    JOIN listas l ON l.Lid=lq.Lid
                                    JOIN turmalista tl ON tl.Lid=l.Lid
                                    JOIN turmas t ON tl.Tid=t.Tid
                                    JOIN userturma ut ON ut.Tid=t.Tid
                                    WHERE tl.Tid=:Tid AND l.Lid=:Lid AND q.Qid=:Qid AND ut.Uid=:Uid' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->bindValue( ':Uid', $_SESSION[ 'Uid' ] );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function submissao( $tSubmissao, $Qid, $TLid, $DFAdesign, $DFAdados ) {
    if ( $tSubmissao == 'salvar' ) {              // esta salvando?
      $status = $this->status_SALVO_RASCUNHO;
    } else if ( $tSubmissao == 'submeter' ) {     // ou submetendo?
      $status = $this->status_AGUARDANDO_CORRECAO;
    } else {
      die( 'Erro no tipo de Submissao = ' . $tSubmissao );
    }

    $Sid = $this->getSid( $Qid, $TLid );

    date_default_timezone_set( 'America/Sao_Paulo' );
    $dt       = new DateTime( 'NOW' );
    $horaJuiz = $dt->format( "Y-m-d H:i:s" );

    if ( $Sid == null ) { // nao existe salvo, entao cria novo submissoes
      $this->novoSubmeter( $Qid, $TLid, $DFAdesign, $DFAdados, $status, $horaJuiz );
    } else {
      $this->alterarSubmeter( $Sid[ 'Sid' ], $DFAdesign, $DFAdados, $status, $horaJuiz );
    }
  }

  function getSid( $Qid, $TLid ) {
    // return ( Sid )
    try {
      $stmt = $this->db->prepare( 'SELECT s.Sid
                                    FROM submissoes s
                                    WHERE s.Qid=:Qid AND
                                          s.TLid=:TLid AND
                                          s.Uid=:Uid' );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->bindValue( ':TLid', $TLid );
      $stmt->bindValue( ':Uid', $_SESSION[ 'Uid' ] );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function novoSubmeter( $Qid, $TLid, $DFAdesign, $DFAdados, $status, $horaJuiz ) {
    try {
      $stmt = $this->db->prepare( 'INSERT INTO submissoes (Uid, TLid, Qid, respostaDados, respostaDesigner, status, horaJuiz)
                                    VALUES (
                                      :Uid,
                                      :TLid,
                                      :Qid,
                                      :respostaDados,
                                      :respostaDesigner,
                                      :status,
                                      :horaJuiz )' );
      $stmt->bindValue( ':Uid', $_SESSION[ 'Uid' ] );
      $stmt->bindValue( ':TLid', $TLid );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->bindValue( ':respostaDados', $DFAdados );
      $stmt->bindValue( ':respostaDesigner', $DFAdesign );
      $stmt->bindValue( ':status', $status );
      $stmt->bindValue( ':horaJuiz', $horaJuiz );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function alterarSubmeter( $Sid, $DFAdesign, $DFAdados, $status, $horaJuiz ) {
    try {
      $stmt = $this->db->prepare( 'UPDATE submissoes s SET
                                      s.respostaDesigner=:DFAdesign,
                                      s.respostaDados=:DFAdados,
                                      s.status=:status,
                                      s.horaJuiz=:horaJuiz
                                      WHERE s.Sid=:Sid' );
      $stmt->bindValue( ':DFAdesign', $DFAdesign );
      $stmt->bindValue( ':DFAdados', $DFAdados );
      $stmt->bindValue( ':status', $status );
      $stmt->bindValue( ':horaJuiz', $horaJuiz );
      $stmt->bindValue( ':Sid', $Sid );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function alterarStatusSubmissao( $Sid, $status, $palavra ) {
    try {
      $stmt = $this->db->prepare( 'UPDATE submissoes s SET
                                      s.status=:status,
                                      s.palavra=:palavra
                                      WHERE s.Sid=:Sid' );
      $stmt->bindValue( ':status', $status );
      $stmt->bindValue( ':palavra', $palavra );
      $stmt->bindValue( ':Sid', $Sid );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getSubmetida( $Tid, $Lid, $Qid ) {
    // return ( respostaDesigner, status, palavra )
    try {
      $stmt = $this->db->prepare( 'SELECT s.respostaDesigner, s.status, s.palavra
                                    FROM submissoes s
                                    JOIN turmalista tl ON tl.TLid=s.TLid
                                    WHERE  s.Qid = :Qid AND
                                          tl.Lid = :Lid AND
                                          tl.Tid = :Tid AND
                                           s.Uid = :Uid ' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->bindValue( ':Uid', $_SESSION[ 'Uid' ] );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getRespostaParaOJuiz( $TLid, $Qid ) {
    // return ( Sid, alfabeto, gabaritoDados, respostaDados )
    try {
      $stmt = $this->db->prepare( 'SELECT s.Sid, q.alfabeto, q.gabaritoDados, s.respostaDados
                                    FROM submissoes s
                                    JOIN turmalista tl ON tl.TLid=s.TLid
                                    JOIN questoes q ON q.Qid=s.Qid
                                    WHERE  s.Qid  = :Qid AND
                                          tl.TLid = :TLid AND
                                           s.Uid  = :Uid ' );
      $stmt->bindValue( ':TLid', $TLid );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->bindValue( ':Uid', $_SESSION[ 'Uid' ] );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  public function getCorrecao( $Tid, $Lid, $Qid ) {
    // return ( status )
    try {
      $stmt = $this->db->prepare( 'SELECT s.status
                                    FROM submissoes s
                                    JOIN turmalista tl ON tl.TLid=s.TLid
                                    WHERE  s.Qid = :Qid AND
                                          tl.Lid = :Lid AND
                                          tl.Tid = :Tid AND
                                           s.Uid = :Uid ' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->bindValue( ':Uid', $_SESSION[ 'Uid' ] );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  public function getNumQuestoesAcerto( $Tid, $Lid ) {
    // return COUNT(status='C')
    try {
      $stmt = $this->db->prepare( 'SELECT COUNT(s.Sid) AS cont
																	FROM submissoes s
																	JOIN turmalista tl ON tl.TLid=s.TLid
																	JOIN usuarios u ON u.Uid = s.Uid
																	WHERE tl.Tid=:Tid AND tl.Lid=:Lid AND s.Uid=:Uid AND s.status=\'C\'' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':Uid', $_SESSION[ 'Uid' ] );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result[ 'cont' ];
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  public function getNumQuestoes( $Lid ) {
    // return COUNT(lq.Qid)
    try {
      $stmt = $this->db->prepare( 'SELECT COUNT(lq.Qid) AS numQ
                                  FROM listaquestao lq
                                  WHERE Lid=:Lid' );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result[ 'numQ' ];
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  public function estorouDeadline( $Tid, $Lid ) {
    // True se antes da data
    // False se apos da data
    try {
      $stmt = $this->db->prepare( 'SELECT tl.dia, tl.hora
                                  FROM turmalista tl
                                  WHERE tl.Tid=:Tid AND tl.Lid=:Lid' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );
      if ( $result == null ) {
        die( "estorouDeadline(): Nao encontrou esta Lista" );
      }
      date_default_timezone_set( 'America/Sao_Paulo' );
      $deadline = new DateTime( $result[ 'dia' ] . ' ' . $result[ 'hora' ] );
      $agora    = new DateTime( 'NOW' );

      $r = !( $agora->format( 'Y-m-d H:i' ) <= $deadline->format( 'Y-m-d H:i' ) );
      // se agora eh depois do deadline  -> TRUE
      // se agora nao passou do deadline -> FALSE

      return $r;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }
}


