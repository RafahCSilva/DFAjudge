<?php
session_start();

// Configure aqui o banco de dados
$DB_HOST  = 'localhost';
$DB_USER  = 'root';
$DB_PASS  = '';
$DB_NAME  = 'icrafael';

// Configure aqui o e-maild o GMAIL
$EMAIL_USER = 'digite aqui o email @gmail.com';
$EMAIL_PASS = 'digite aqui a senha do email';

// Configure aqui a raiz do site (utilizado no links do corpo do e-mails)
$baseSITE = 'http://localhost/ICrafael';


// conectando ao MySQL
try {
  $DB_con = new PDO( "mysql:host={$DB_HOST};dbname={$DB_NAME}", $DB_USER, $DB_PASS );
  $DB_con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch ( PDOException $e ) {
  echo "<br/>ERROR AO CONECTAR COM O BANCO DE DADOS <br/>";
  die( "Erro: <code>" . $e->getMessage() . "</code>" );
}

include_once 'class.UserManager.php';
$userManager = new UserManager( $DB_con );

if ( isset( $_SESSION[ 'Uid' ] ) ) {
  if ( $_SESSION[ "Uadmin" ] == true ) {
    include_once 'class.ProfessorDAO.php';
    $profDAO = new ProfessorDAO( $DB_con, $_SESSION[ 'Uid' ] );
  } else {
    include_once 'class.AlunoDAO.php';
    $alunoDAO = new AlunoDAO( $DB_con );
  }
}


// exemplo pra validar o previlegio da pag (true = admin || false = aluno)
//  $userManager->checarPrevilegio(false);

//$DEBUG = "";
//function DUMP( $var ) {
//  // Serve pra dar var_dump() em uma variavel
//  //  $GLOBALS[ 'DEBUG' ] .= "\n<pre>" . print_r( $var, true ) . "\n</pre>\n";
//}
//
//function DUMP2( $var ) {
//    echo "\n<pre>\n" . print_r( $var, true ) . "\n</pre>\n";
//}

/**
 * Escape all HTML, JavaScript, and CSS
 *
 * @param string $input The input string
 *
 * @return string
 */
function noHTML( $input ) {
  return htmlspecialchars( $input, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
  //return htmlentities( $input, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
}

?>