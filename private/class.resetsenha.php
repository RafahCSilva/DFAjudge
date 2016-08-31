<?php
include_once( 'phpPasswordHashingLib/passwordLib.php' );
require 'PHPMailer/PHPMailerAutoload.php';

class RESETSENHA {
  private $db;
  private $linkSite;
  private $localNovaSenha;

  public function __construct( \PDO $DB_con, $baseSite ) {
    $this->db             = $DB_con;
    $this->linkSite       = $baseSite;
    $this->localNovaSenha = $baseSite . '/novaSenha.php';
  }

  public function _bemVindo( $uemail ) {
    // ver se user existe
    $usuario = $this->getUserByEmail( $uemail );
    if ( $usuario == null ) {// nao existe
      return;
    }
    $Uid   = $usuario[ 'Uid' ];
    $nome  = $usuario[ 'nome' ];
    $email = $usuario[ 'email' ];

    // Gera token
    $token = password_hash( strval( microtime( true ) * 10000 ), PASSWORD_DEFAULT );
    $this->addTokenUser( $Uid, $token );

    //envia email
    $mail = $this->getPHPMailer();
    $mail->addAddress( $email, $nome ); // quem recebe

    $mail->Subject = 'Seja bem-vindo ao DFA judge';

    $b = file_get_contents( "../private/emailBemVindo_body.html", FILE_TEXT );
    $b = str_replace( '{NOME}', $nome, $b );
    $b = str_replace( '{EMAIL}', $email, $b );
    $b = str_replace( '{LOCAL}', $this->localNovaSenha, $b );
    $b = str_replace( '{TOKEN}', $token, $b );

    $mail->Body = $b;
    if ( !$mail->Send() ) {
      die( 'Mail Send error: ' . $mail->ErrorInfo );
    }
  }

  private function getUserByEmail( $email ) {
    try {
      $stmt = $this->db->prepare( "SELECT *
                                   FROM usuarios
                                   WHERE email=:email
                                   LIMIT 1" );
      $stmt->bindValue( ':email', $email );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;

    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  private function addTokenUser( $Uid, $token ) {
    try {
      $stmt = $this->db->prepare( 'UPDATE usuarios SET token=:token WHERE Uid=:Uid' );
      $stmt->bindValue( ':token', $token );
      $stmt->bindValue( ':Uid', $Uid );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  private function getPHPMailer() {
    $mail            = new PHPMailer;
    $mail->CharSet   = 'UTF-8';//'ISO-8859-1';
    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $EMAIL_USER;                            // SMTP username
    $mail->Password   = $EMAIL_PASS;                            // SMTP password
    $mail->SMTPSecure = 'tls'; //'ssl';                         // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587; //465;                             // TCP port to connect to
    $mail->WordWrap   = 70;                                     // tamanho da quebra do texto
    $mail->isHTML( true );                                      // Set email format to HTML
    $mail->setFrom( $EMAIL_USER;, 'DFA judge' );        // quem envia
    return $mail;
  }


  public function _resetSenha( $uemail ) {
    /* http://stackoverflow.com/questions/6585649/php-forgot-password-function
       1  Quando o usuário pede para redefinir sua senha, torná-los inserir seu endereço de e-mail
       2  Não indicam se aquele endereço de e-mail era válido ou não (basta dizer-lhes que um email foi enviado).
       Esta é aberto para o debate uma vez que reduz a usabilidade (isto é, não tenho idéia de qual e-mail
       I registrada), mas oferece menos informação para as pessoas que tentam reunir informações sobre
       quais e-mails são efectivamente registado em seu site.
       3  Gerar um token (talvez botar um timestamp com um sal) e armazená-lo no banco de dados no registro do usuário.
       4  Enviar um e-mail para o usuário, juntamente com um link para seu http * s * página reset
       (endereço de token e-mail no url).
       5  Use o endereço token e-mail para validar o usuário.
       6  Deixá-los escolher uma nova senha, substituindo o antigo.
       7  Além disso, é uma boa idéia para expirar esses símbolos depois de um determinado período de tempo,
       geralmente 24 horas.
       8  Opcionalmente, gravar quantas "esqueceu" tentativas têm acontecido, e talvez implementar a
       funcionalidade mais complexa se as pessoas estão pedindo uma tonelada de e-mails.
       9  Opcionalmente, ficha (em uma tabela separada) o endereço IP da pessoa que solicita a reposição.
       Incrementar uma contagem a partir desse IP. Se ele nunca chega a mais de, digamos, 10 ... Ignore seus pedidos futuros.
      */

    // ver se user existe
    $usuario = $this->getUserByEmail( $uemail );
    if ( $usuario == null ) {// nao existe
      return;
    }
    $Uid   = $usuario[ 'Uid' ];
    $nome  = $usuario[ 'nome' ];
    $email = $usuario[ 'email' ];

    // Gera token
    $token = password_hash( strval( microtime( true ) * 10000 ), PASSWORD_DEFAULT );
    $this->addTokenUser( $Uid, $token );

    //envia email
    $mail = $this->getPHPMailer();
    $mail->addAddress( $email, $nome ); // quem recebe

    $mail->Subject = 'Recuperação de senha do DFA judge';

    $b = file_get_contents( "private/emailResetSenha_body.html", FILE_TEXT );
    $b = str_replace( '{NOME}', $nome, $b );
    $b = str_replace( '{LOCAL}', $this->localNovaSenha, $b );
    $b = str_replace( '{TOKEN}', $token, $b );

    $mail->Body = $b;
    $mail->send();

  }

  public function _novaSenha( $senha, $token ) {

    // ver se user existe pelo token
    $usuario = $this->getUserByToken( $token );
    if ( $usuario == null ) {// nao existe
      return;
    }
    $Uid   = $usuario[ 'Uid' ];
    $nome  = $usuario[ 'nome' ];
    $email = $usuario[ 'email' ];

    // Gera hash da senha
    $hash_senha = password_hash( $senha, PASSWORD_DEFAULT );

    // altera a senha e deleta o token
    $this->novaSenhaUser( $Uid, $hash_senha );
    $this->delTokenUser( $Uid );

    //envia email

    $mail = $this->getPHPMailer();
    $mail->addAddress( $email, $nome );  // quem recebe

    $mail->Subject = 'Sua senha do DFA judge foi alterada';

    $b = file_get_contents( "private/emailNovaSenha_body.html", FILE_TEXT );
    $b = str_replace( '{NOME}', $nome, $b );
    $b = str_replace( '{EMAIL}', $email, $b );
    $b = str_replace( '{SITE}', $this->linkSite, $b );

    $mail->Body = $b;

    $mail->send();
  }

  private function getUserByToken( $token ) {
    try {
      $stmt = $this->db->prepare( "SELECT *
                                   FROM usuarios
                                   WHERE token=:token
                                   LIMIT 1" );
      $stmt->bindValue( ':token', $token );
      $stmt->execute();
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      return $result;

    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  private function novaSenhaUser( $Uid, $senha ) {
    try {
      $stmt = $this->db->prepare( 'UPDATE usuarios SET senha=:senha WHERE Uid=:Uid' );
      $stmt->bindValue( ':senha', $senha );
      $stmt->bindValue( ':Uid', $Uid );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }

  private function delTokenUser( $Uid ) {
    try {
      $stmt = $this->db->prepare( 'UPDATE usuarios SET token=:token WHERE Uid=:Uid' );
      $stmt->bindValue( ':token', '-' );
      $stmt->bindValue( ':Uid', $Uid );
      $stmt->execute();
    } catch ( PDOException $e ) {
      die( "Erro: <code>" . $e->getMessage() . "</code>" );
    }
  }
}