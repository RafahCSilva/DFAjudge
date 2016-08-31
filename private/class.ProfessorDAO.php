<?php

class ProfessorDAO {
  public $status_SALVO_RASCUNHO      = 'S';
  public $status_AGUARDANDO_CORRECAO = 'A';
  public $status_CORRIGIDO_CORRETO   = 'C';
  public $status_CORRIGIDO_INCORRETO = 'I';
  public $status_ERROR               = 'E';

  private $db;
  private $UidADMIN;

  public function __construct( \PDO $db, $UidADMIN ) {
    $this->db       = $db;
    $this->UidADMIN = $UidADMIN;
  }

  function getTurmas() {
    //return [ (Tid, Tnome, quad) ... ]
    try {
      $stmt = $this->db->prepare( 'SELECT t.Tid, t.Tnome, t.quad
																	FROM turmas t
																	WHERE t.UidCriador=:idUser
																	ORDER BY t.Tnome' );
      $stmt->bindValue( ':idUser', $this->UidADMIN );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getTurmaContAlunos( $Tid ) {
    try {
      $stmt = $this->db->prepare( 'SELECT COUNT(ut.Uid) AS numA
                                  FROM userturma ut
                                  WHERE ut.Tid=:Tid' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result[ 'numA' ];
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getTurmaContListas( $Tid ) {
    try {
      $stmt = $this->db->prepare( 'SELECT COUNT(tl.Lid) AS numL
                                  FROM turmalista tl
                                  WHERE tl.Tid=:Tid' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result[ 'numL' ];
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getTurma( $Tid ) {
    //return (Tid, Tnome, quad)
    try {
      $stmt = $this->db->prepare( 'SELECT t.Tid, t.Tnome, t.quad
                                   FROM turmas t
                                   WHERE t.UidCriador=:idUser AND t.Tid=:Tid' );
      $stmt->bindValue( ':idUser', $this->UidADMIN );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getTurmaSubmissao( $Tid ) {
    //return (Tid, Tnome, quad)
    try {
      $stmt = $this->db->prepare( 'SELECT t.Tid, t.Tnome, t.quad
                                   FROM turmas t
                                   WHERE  t.Tid=:Tid' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getTurmaAlunos( $Tid ) {
    //return [ (Uid, nome)... ]
    try {
      $stmt = $this->db->prepare( 'SELECT u.Uid, u.nome, u.RA
																	FROM turmas t
																	JOIN userturma ut ON ut.Tid = t.Tid
																	JOIN usuarios u ON u.Uid = ut.Uid
																	WHERE t.Tid=:Tid AND t.UidCriador=:idProf
																	ORDER BY u.nome' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':idProf', $this->UidADMIN );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  // get usuario q pertence a uma turma deste Tid ============= FAIL
  function getAlunosDaTurma( $Tid ) {
    // return [ ( Uid ) ... ]
    try {
      $stmt = $this->db->prepare( 'SELECT u.Uid
																	FROM usuarios u
																	ORDER BY u.nome' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }


  // get listas q pertence a uma turma com esse Tid
  function getListasDaTurma( $Tid ) {
    // return [ ( Lid, Ltitulo, dia, hora ) ... ]
    try {
      $stmt = $this->db->prepare( 'SELECT l.Lid, l.Ltitulo, tl.dia, tl.hora
																	FROM listas l
																	JOIN turmalista tl ON tl.Lid=l.Lid
																	WHERE tl.Tid=:Tid AND l.UidCriador=:UidCriador
																	ORDER BY l.Ltitulo' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  // get lista q pertence a uma turma com esse Tid
  function getListaDaTurma( $Tid, $Lid ) {
    // return (Lid, Ltitulo, dia, hora)
    try {
      $stmt = $this->db->prepare( 'SELECT l.Lid, l.Ltitulo, tl.dia, tl.hora
																	FROM listas l
																	JOIN turmalista tl ON tl.Lid=l.Lid
																	WHERE tl.Tid=:Tid AND l.Lid=:Lid AND l.UidCriador=:UidCriador
																	ORDER BY l.Ltitulo' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  // add nova turma
  function novaTurma( $TXTnome, $TXTquad, $listLista, $listAlunos, $deadline ) {
    try {
      $stmt = $this->db->prepare( 'INSERT INTO turmas(Tnome, quad, UidCriador) VALUES
                                  (:Tnome, :quad, :UidCriador)' );
      $stmt->bindValue( ':Tnome', $TXTnome );
      $stmt->bindValue( ':quad', $TXTquad );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();

      $Tid = $this->db->lastInsertId();

      foreach ( $listAlunos as $aluno ) {
        $stmt = $this->db->prepare( 'INSERT INTO userturma ( Uid, Tid) VALUES (:Uid, :Tid)' );
        $stmt->bindValue( ':Uid', $aluno );
        $stmt->bindValue( ':Tid', $Tid );
        $stmt->execute();
      }
      foreach ( $listLista as $lista ) {
        $stmt = $this->db->prepare( 'INSERT INTO turmalista (Lid, Tid, dia, hora) VALUES (:Lid, :Tid, :dia, :hora)' );
        $stmt->bindValue( ':Lid', $lista );
        $stmt->bindValue( ':Tid', $Tid );
        $stmt->bindValue( ':dia', $deadline[ $lista ][ 0 ] );
        $stmt->bindValue( ':hora', $deadline[ $lista ][ 1 ] );
        $stmt->execute();
      }

    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }

  }

  // add alterar Turma
  function alterarTurma( $Tid, $TXTnome, $TXTquad, $listLista, $listAlunos, $deadline ) {
    try {
      /// TURMA
      $stmt = $this->db->prepare( 'UPDATE turmas SET Tnome=:Tnome, quad=:quad WHERE Tid=:Tid AND UidCriador=:UidCriador' );
      $stmt->bindValue( ':Tnome', $TXTnome );
      $stmt->bindValue( ':quad', $TXTquad );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();

      /// TURMA <> USUARIOS
      $Tatual = $this->getTurmaUserUid( $Tid );

      if ( $listAlunos == null ) {
        $listAlunos = Array();
      }
      $Tdelete = array_diff( $Tatual, $listAlunos );
      $Tadd    = array_diff( $listAlunos, $Tatual );

      // remove todos os antigos
      foreach ( $Tdelete as $aluno ) {
        $stmt = $this->db->prepare( 'DELETE FROM userturma WHERE Tid=:Tid AND Uid=:Uid' );
        $stmt->bindValue( ':Tid', $Tid );
        $stmt->bindValue( ':Uid', $aluno );
        $stmt->execute();
      }
      // add os novos
      foreach ( $Tadd as $aluno ) {
        $stmt = $this->db->prepare( 'INSERT INTO userturma ( Uid, Tid) VALUES (:Uid, :Tid)' );
        $stmt->bindValue( ':Uid', $aluno );
        $stmt->bindValue( ':Tid', $Tid );
        $stmt->execute();
      }

      /// TURMA <> LISTA
      $Latual = $this->getTurmaListaLid( $Tid );
      if ( $listLista == null ) {
        $listLista = array();
      }

      $Ldelete = array_diff( $Latual, $listLista );
      $Ladd    = array_diff( $listLista, $Latual );

      // deleta os removidos
      foreach ( $Ldelete as $lista ) {
        $stmt = $this->db->prepare( 'DELETE FROM turmalista WHERE Tid=:Tid AND Lid=:Lid' );
        $stmt->bindValue( ':Tid', $Tid );
        $stmt->bindValue( ':Lid', $lista );
        $stmt->execute();
      }
      // add os novos
      foreach ( $Ladd as $lista ) {
        $stmt = $this->db->prepare( 'INSERT INTO turmalista (Lid, Tid) VALUES (:Lid, :Tid)' );
        $stmt->bindValue( ':Lid', $lista );
        $stmt->bindValue( ':Tid', $Tid );
        $stmt->execute();
      }
      // edit deadlines
      foreach ( $listLista as $lista ) {
        $stmt = $this->db->prepare( 'UPDATE turmalista SET dia=:dia , hora=:hora WHERE Lid=:Lid AND Tid=:Tid' );
        $stmt->bindValue( ':dia', $deadline[ $lista ][ 0 ] );
        $stmt->bindValue( ':hora', $deadline[ $lista ][ 1 ] );
        $stmt->bindValue( ':Lid', $lista );
        $stmt->bindValue( ':Tid', $Tid );
        $stmt->execute();
      }
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getTurmaUserUid( $Tid ) {
    try {
      $stmt = $this->db->prepare( 'SELECT ut.Uid
																	FROM userturma ut
																	WHERE ut.Tid=:Tid' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      $resp = Array();
      foreach ( $result as $r ) {
        $resp[] = $r[ 'Uid' ];
      }

      return $resp;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getTurmaListaLid( $Tid ) {
    try {
      $stmt = $this->db->prepare( 'SELECT tl.Lid
																	FROM turmalista tl
																	WHERE tl.Tid=:Tid' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      $resp = Array();
      foreach ( $result as $r ) {
        $resp[] = $r[ 'Lid' ];
      }

      return $resp;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }


  /// ===========  LISTAS
  // get todas as listas

  function getListas() {
    // return [ ( Uid, nome ) .. ]
    try {
      $stmt = $this->db->prepare( 'SELECT l.Lid, l.Ltitulo
																	FROM listas l
																	WHERE l.UidCriador=:UidCriador
																	ORDER BY l.Ltitulo' );

      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getListaContQuestoes( $Lid ) {
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

  function getLista( $Lid ) {
    // return [ ( Lid, Ltitulo ) .. ]
    try {
      $stmt = $this->db->prepare( 'SELECT l.Lid, l.Ltitulo
																	FROM listas l
																	WHERE l.Lid=:Lid AND l.UidCriador=:UidCriador
																	ORDER BY l.Ltitulo' );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  // get todas as listas da turma Tid
  function getTurmaListas( $Tid ) {
    // return [ ( Uid, nome, dia, hora ) ... ]
    try {
      $stmt = $this->db->prepare( 'SELECT l.Lid, l.Ltitulo, tl.dia, tl.hora
																	FROM listas l
																	JOIN turmalista tl ON l.Lid=tl.Lid
																	WHERE tl.Tid=:Tid
																	ORDER BY l.Ltitulo' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getTurmaLista( $Tid, $Lid ) {
    // return (Lid, Ltitulo, dia, hora)
    try {
      $stmt = $this->db->prepare( 'SELECT l.Lid, l.Ltitulo, tl.dia, tl.hora
																	FROM listas l
																	JOIN turmalista tl ON l.Lid=tl.Lid
																	WHERE tl.Tid=:Tid AND l.Lid=:Lid' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function alterarLista( $Lid, $TXTtitulo, $listQuestoes ) {
    try {
      /// LISTA
      $stmt = $this->db->prepare( 'UPDATE listas SET Ltitulo=:Ltitulo WHERE Lid=:Lid AND UidCriador=:UidCriador' );
      $stmt->bindValue( ':Ltitulo', $TXTtitulo );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();

      $Latual = $this->getListaQuestoesQid( $Lid );

      $Ldelete = array_diff( $Latual, $listQuestoes );
      $ladd    = array_diff( $listQuestoes, $Latual );

      // deleta os removidos
      foreach ( $Ldelete as $questao ) {
        $stmt = $this->db->prepare( 'DELETE FROM listaquestao WHERE Qid=:Qid' );
        $stmt->bindValue( ':Qid', $questao );
        $stmt->execute();
      }
      // add os novos
      foreach ( $ladd as $questao ) {
        $stmt = $this->db->prepare( 'INSERT INTO listaquestao ( Lid, Qid) VALUES (:Lid, :Qid)' );
        $stmt->bindValue( ':Qid', $questao );
        $stmt->bindValue( ':Lid', $Lid );
        $stmt->execute();
      }
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getListaQuestoesQid( $Lid ) {
    try {
      $stmt = $this->db->prepare( 'SELECT lq.Qid
																	FROM listaquestao lq
																	WHERE lq.Lid=:Lid' );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      $resp = Array();
      foreach ( $result as $r ) {
        $resp[] = $r[ 'Qid' ];
      }

      return $resp;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function novaLista( $TXTtitulo, $listQuestoes ) {
    try {
      $stmt = $this->db->prepare( 'INSERT INTO listas ( Ltitulo, UidCriador) VALUES (:Ltitulo, :UidCriador)' );
      $stmt->bindValue( ':Ltitulo', $TXTtitulo );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();

      $Lid = $this->db->lastInsertId();

      if ( $listQuestoes != null ) {
        foreach ( $listQuestoes as $questao ) {
          $stmt = $this->db->prepare( 'INSERT INTO listaquestao ( Lid, Qid) VALUES (:Lid, :Qid)' );
          $stmt->bindValue( ':Qid', $questao );
          $stmt->bindValue( ':Lid', $Lid );
          $stmt->execute();
        }
      }
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getUsuarios() {
    // return [ ( Uid, RA, nome, email, admin ) ... ]
    try {
      $stmt = $this->db->prepare( 'SELECT u.Uid, u.RA, u.nome, u.email, u.admin
																	FROM usuarios u
																	ORDER BY u.nome' );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  // get um usuario pelo Uid
  function getUsuario( $Aid ) {
    // return (Uid, RA, nome, email, admin)
    try {
      $stmt = $this->db->prepare( 'SELECT u.Uid, u.RA, u.nome, u.email, u.admin
																	FROM usuarios u
																	WHERE u.Uid=:Uid' );
      $stmt->bindValue( ':Uid', $Aid );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  // get o id do usuario pelo email
  function getUsuarioIdByEmail( $email ) {
    try {
      $stmt = $this->db->prepare( 'SELECT u.Uid
																	FROM usuarios u
																	WHERE u.email=:email' );
      $stmt->bindValue( ':email', $email );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return ( $result == null ) ? -1 : $result[ 'Uid' ];
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  // editar um usuario
  function alterarUsuario( $Aid, $TXTra, $TXTnome, $TXTemail, $TXTadmin ) {
    try {
      $stmt = $this->db->prepare( 'UPDATE usuarios SET
                                  RA=:ra,
                                  nome=:nome,
                                  email=:email,
                                  admin=:admin
                                  WHERE Uid=:Uid' );
      $stmt->bindValue( ':ra', $TXTra );
      $stmt->bindValue( ':nome', $TXTnome );
      $stmt->bindValue( ':email', $TXTemail );
      $stmt->bindValue( ':admin', $TXTadmin );
      $stmt->bindValue( ':Uid', $Aid );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  // editar um usuario
  function resetarUsuario( $Aid, $TXTra, $TXTnome, $TXTemail, $TXTadmin ) {
    try {
      $stmt = $this->db->prepare( 'UPDATE usuarios SET
                                  RA=:ra,
                                  nome=:nome,
                                  email=:email,
                                  senha=:senha,
                                  admin=:admin
                                  WHERE Uid=:Uid' );
      $stmt->bindValue( ':ra', $TXTra );
      $stmt->bindValue( ':nome', $TXTnome );
      $stmt->bindValue( ':email', $TXTemail );
      $stmt->bindValue( ':senha', '111' );
      $stmt->bindValue( ':admin', $TXTadmin );
      $stmt->bindValue( ':Uid', $Aid );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  // add novo usuario
  function novoUsuario( $TXTra, $TXTnome, $TXTemail, $TXTadmin ) {
    try {
      $stmt = $this->db->prepare( 'INSERT INTO usuarios (RA, nome, email, senha, admin) VALUES
                                  (:ra, :nome, :email, :pass, :admin)' );
      $stmt->bindValue( ':ra', $TXTra );
      $stmt->bindValue( ':nome', $TXTnome );
      $stmt->bindValue( ':email', $TXTemail );
      $stmt->bindValue( ':pass', "111" );
      $stmt->bindValue( ':admin', $TXTadmin );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  // get usuarios alunos
  function getUsuariosAluno() {
    // return [(Uid, nome)]
    try {
      $stmt = $this->db->prepare( 'SELECT u.Uid, u.nome
																	FROM usuarios u
																	WHERE u.admin=0
																	ORDER BY u.nome' );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getUsuariosAluno2() {
    // return [ ( Uid, nome, checked ) .. ]
    try {
      $stmt = $this->db->prepare( 'SELECT u.Uid, u.nome, u.admin AS checked
																	FROM usuarios u
																	WHERE u.admin=0
																	ORDER BY u.nome' );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }


  // ============== QUESTOES
  function getQuestoes() {
    // return [ ( Qid, Qtitulo ) ... ]
    try {
      $stmt = $this->db->prepare( 'SELECT q.Qid, q.Qtitulo
																	FROM questoes q
																	WHERE q.UidCriador=:UidCriador
																	ORDER BY q.Qid' );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getQuestoesDaLista( $Lid ) {
    // return [(Qid, Qtitulo)]
    try {
      $stmt = $this->db->prepare( 'SELECT q.Qid, q.Qtitulo
																	FROM questoes q
																	JOIN listaquestao lq ON lq.Qid=q.Qid
																	JOIN listas l ON l.Lid=lq.Lid
																	WHERE l.UidCriador=:UidCriador AND l.Lid=:Lid
																	ORDER BY q.Qtitulo' );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getQuestoesDaListaOrdem( $Lid ) {
    // return [ ( Qid, Qtitulo, LQid, pos ) ... ]
    try {
      $stmt = $this->db->prepare( 'SELECT q.Qid, q.Qtitulo, lq.LQid, lq.pos
																	FROM questoes q
																	JOIN listaquestao lq ON lq.Qid=q.Qid
																	JOIN listas l ON l.Lid=lq.Lid
																	WHERE l.UidCriador=:UidCriador AND l.Lid=:Lid
																	ORDER BY lq.pos ASC, q.Qtitulo ASC' );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  // add nova Questao
  function novaQuestao( $Qtitulo, $Qcorpo, $Qalfabeto, $DFAdesign, $DFAdados ) {
    try {
      $stmt = $this->db->prepare( 'INSERT INTO questoes (Qtitulo, corpo, alfabeto, gabaritoDados, gabaritoDesigner, UidCriador) VALUES
                                 (:Qtitulo, :corpo, :alfabeto, :gabaritoDados, :gabaritoDesigner, :UidCriador)' );
      $stmt->bindValue( ':Qtitulo', $Qtitulo );
      $stmt->bindValue( ':corpo', $Qcorpo );
      $stmt->bindValue( ':alfabeto', $Qalfabeto );
      $stmt->bindValue( ':gabaritoDados', $DFAdados );
      $stmt->bindValue( ':gabaritoDesigner', $DFAdesign );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();

    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getQuestao( $Qid ) {
    // return (Qid, Qtitulo, corpo, alfabeto, gabaritoDesigner)
    try {
      $stmt = $this->db->prepare( 'SELECT q.Qid, q.Qtitulo, q.corpo, q.alfabeto, q.gabaritoDados, q.gabaritoDesigner
																	FROM questoes q
																	WHERE q.Qid=:Qid AND q.UidCriador=:UidCriador' );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getQuestaoSubmissao( $Qid ) {
    // return (Qid, Qtitulo, corpo, alfabeto, gabaritoDesigner)
    try {
      $stmt = $this->db->prepare( 'SELECT q.Qid, q.Qtitulo, q.corpo, q.alfabeto, q.gabaritoDados, q.gabaritoDesigner
																	FROM questoes q
																	WHERE q.Qid=:Qid' );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function alterarQuestao( $Qid, $Qtitulo, $Qcorpo, $Qalfabeto, $DFAdesign, $DFAdados ) {
    try {
      $stmt = $this->db->prepare( 'UPDATE questoes q SET
                                  Qtitulo=:Qtitulo,
                                  corpo=:corpo,
                                  alfabeto=:alfabeto,
                                  gabaritoDados=:gabaritoDados,
                                  gabaritoDesigner=:gabaritoDesigner
                                  WHERE Qid=:Qid AND UidCriador=:UidCriador' );
      $stmt->bindValue( ':Qtitulo', $Qtitulo );
      $stmt->bindValue( ':corpo', $Qcorpo );
      $stmt->bindValue( ':alfabeto', $Qalfabeto );
      $stmt->bindValue( ':gabaritoDados', $DFAdados );
      $stmt->bindValue( ':gabaritoDesigner', $DFAdesign );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function deletaQuestao( $Qid ) {
    try {
      $stmt = $this->db->prepare( 'DELETE FROM questoes
                                    WHERE Qid=:Qid AND UidCriador=:UidCriador' );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }


  function deletaLista( $Lid ) {
    try {
      $stmt = $this->db->prepare( 'DELETE FROM listas
                                    WHERE Lid=:Lid AND UidCriador=:UidCriador' );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function deletaTurma( $Tid ) {
    try {
      $stmt = $this->db->prepare( 'DELETE FROM turmas
                                    WHERE Tid=:Tid AND UidCriador=:UidCriador' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function deletaUsuario( $Aid ) {
    try {
      $stmt = $this->db->prepare( 'DELETE FROM usuarios
                                    WHERE Uid=:Uid' );
      $stmt->bindValue( ':Uid', $Aid );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function deletaSubmissao( $Sid ) {
    try {
      $stmt = $this->db->prepare( 'DELETE FROM submissoes
                                    WHERE Sid=:Sid' );
      $stmt->bindValue( ':Sid', $Sid );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }


  function removeAlunoTurma( $Tid, $Aid ) {
    try {
      $stmt = $this->db->prepare( 'DELETE FROM userturma
                                    WHERE Tid=:Tid AND Uid=:Uid' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Uid', $Aid );
      $stmt->execute();

    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }

  }

  function removeListaTurma( $Tid, $Lid ) {
    try {
      $stmt = $this->db->prepare( 'DELETE FROM turmalista
                                    WHERE Tid=:Tid AND Lid=:Lid' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }

  }

  public function questoesExport( $listQuestoes ) {
    try {
      $ids  = '(' . join( ', ', $listQuestoes ) . ')';
      $stmt = $this->db->prepare( 'SELECT q.Qid, q.Qtitulo, q.corpo, q.alfabeto, q.gabaritoDesigner, q.gabaritoDados
																	FROM questoes q
																	WHERE q.UidCriador=:UidCriador AND q.Qid IN ' . $ids );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();
      $result = $stmt->fetchALL( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  function getStatusSubmissao( $Tid, $Lid, $Qid, $Aid ) {
    //return (status)

    try {
      $stmt = $this->db->prepare( 'SELECT s.status
																	FROM submissoes s
																	JOIN turmalista tl ON tl.TLid=s.TLid
																	JOIN usuarios u ON u.Uid = s.Uid
																	JOIN questoes q ON q.Qid = s.Qid
																	WHERE tl.Tid=:Tid AND tl.Lid=:Lid AND s.Qid=:Qid AND s.Uid=:Uid ' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->bindValue( ':Uid', $Aid );
      //      $stmt->bindValue( ':idProf', $this->UidADMIN );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }


  function getSubmissaoContCorretosPorLista( $Tid, $Lid, $Aid ) {
    //return ( COUNT(status='C')... )
    try {
      $stmt = $this->db->prepare( 'SELECT COUNT(s.Sid) AS cont
																	FROM submissoes s
																	JOIN turmalista tl ON tl.TLid=s.TLid
																	JOIN usuarios u ON u.Uid = s.Uid
																	WHERE tl.Tid=:Tid AND tl.Lid=:Lid AND s.Uid=:Uid AND s.status=:status' );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':Uid', $Aid );
      $stmt->bindValue( ':status', 'C' );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result[ 'cont' ];
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  public function setListasQuestaoOrdem( $Lid, $ordem ) {
    try {
      foreach ( $ordem as $pos => $id ) {
        $stmt = $this->db->prepare( 'UPDATE listaquestao SET pos=:pos WHERE LQid=:LQid AND Lid=:Lid' );
        $stmt->bindValue( ':pos', $pos );
        $stmt->bindValue( ':LQid', $id );
        $stmt->bindValue( ':Lid', $Lid );
        $stmt->execute();
      }
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  public function contUsoQuestoes( $Qid ) {
    //return [ COUNT(listas q tem aquela questao) ]
    try {
      $stmt = $this->db->prepare( 'SELECT COUNT(q.Qid) AS cont
																	FROM questoes q
																	JOIN listaquestao lq ON lq.Qid=q.Qid
																	WHERE lq.Qid=:Qid AND q.UidCriador=:Uid' );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->bindValue( ':Uid', $this->UidADMIN );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result[ 'cont' ];
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  public function getSubmissaoAluno( $Aid, $Tid, $Lid, $Qid ) {
    // return (respostaDesigner, status, palavra, horaJuiz)
    try {
      $stmt = $this->db->prepare( 'SELECT
                                   s.respostaDesigner, s.status, s.palavra, s.horaJuiz
																	FROM submissoes s
                                  JOIN turmalista tl ON tl.TLid=s.TLid
																	WHERE
																	s.Uid=:Uid AND
																	tl.Tid=:Tid AND
																	tl.Lid=:Lid AND
																	s.Qid=:Qid' );
      $stmt->bindValue( ':Uid', $Aid );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  public function getSubmissaoAluno2( $Aid, $Tid, $Lid, $Qid ) {
    try {
      $stmt = $this->db->prepare( 'SELECT
                                    u.RA, u.nome,
                                    t.Tnome, t.quad, tl.dia, tl.hora,
                                    l.Ltitulo,
                                    q.Qid, q.Qtitulo, q.corpo, q.alfabeto,
                                    s.respostaDesigner, s.status, s.palavra, s.horaJuiz
																	FROM questoes q
																	JOIN listaquestao lq ON lq.Qid=q.Qid
																	JOIN listas l ON lq.Lid=l.Lid
																	JOIN turmalista tl ON tl.Lid=l.Lid
																	JOIN turmas t ON t.Tid=tl.Tid
																	JOIN userturma ut ON ut.Tid=t.Tid
																	JOIN  usuarios u  ON u.Uid=ut.Uid
																	JOIN submissoes s ON s.Uid=u.Uid AND s.TLid=tl.TLid AND s.Qid=q.Qid
																	WHERE
																	s.Uid=:Uid AND
																	tl.Tid=:Tid AND
																	tl.Lid=:Lid AND
																	s.Qid=:Qid AND
																	t.UidCriador=:UidCriador' );
      $stmt->bindValue( ':Uid', $Aid );
      $stmt->bindValue( ':Tid', $Tid );
      $stmt->bindValue( ':Lid', $Lid );
      $stmt->bindValue( ':Qid', $Qid );
      $stmt->bindValue( ':UidCriador', $this->UidADMIN );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }


}