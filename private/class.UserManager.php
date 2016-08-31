<?php
require_once 'phpPasswordHashingLib/passwordLib.php';

class UserManager {
  private $db;

  function __construct( \PDO $DB_con  ) {
    $this->db = $DB_con;
  }

  public function logar( $uemail, $upass ) {
    try {
      $stmt = $this->db->prepare( "SELECT * FROM usuarios WHERE email=:uemail LIMIT 1" );
      $stmt->bindValue( ':uemail', $uemail );
      $stmt->execute();
      $userRow = $stmt->fetch( PDO::FETCH_ASSOC );

      if ( $stmt->rowCount() > 0 ) {
        if ( password_verify( $upass, $userRow[ 'senha' ] ) ) {
          $_SESSION[ 'Uid' ]    = $userRow[ 'Uid' ];
          $_SESSION[ 'Unome' ]  = $userRow[ 'nome' ];
          $_SESSION[ 'Uadmin' ] = $userRow[ 'admin' ] == "1" ? true : false;

          return true;
        }
      }

      return false;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  public function is_loggedin() {
    if ( isset( $_SESSION[ 'Uid' ] ) ) {
      return true;
    }

    return false;
  }

  public function checarPrevilegio( $pagPrevilegio ) {
    if ( isset( $_SESSION[ 'Uid' ] ) && $_SESSION[ 'Uadmin' ] == $pagPrevilegio ) {
      return;
    }
    header( 'Location: sair.php' );
    exit();
  }

  public function sair() {
    session_destroy();
    //unset($_SESSION['user_session']);
  }

  public function getMeuUsuario() {
    // return [(Uid, nome, email, admin)...]
    try {
      $stmt = $this->db->prepare( 'SELECT u.Uid, u.RA, u.nome, u.email, u.admin
																	FROM usuarios u
																	WHERE u.Uid=:Uid' );
      $stmt->bindValue( ':Uid', $_SESSION[ 'Uid' ] );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  public function setSenhaMeuUsuario( $senha ) {
    try {
      $stmt = $this->db->prepare( 'UPDATE usuarios SET
                                  senha=:senha
                                  WHERE Uid=:Uid' );
      $stmt->bindValue( ':senha', $senha );
      $stmt->bindValue( ':Uid', $_SESSION[ 'Uid' ] );
      $stmt->execute();

    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  public function verificaSenha( $antSenha ) {
    // return [(Uid, nome, email, admin)...]
    try {
      $stmt = $this->db->prepare( 'SELECT u.senha
																	FROM usuarios u
																	WHERE u.Uid=:Uid' );
      $stmt->bindValue( ':Uid', $_SESSION[ 'Uid' ] );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return password_verify( $antSenha, $result[ 'senha' ] );
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }


}