<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( false );

$tituloPag    = 'Ajuda do DFA judge';
$tituloHeader = 'Ajuda do DFA judge';
$pagAtiva     = 'OPCOES';
$backPag      = 'home.php';
$mathjax      = true;
include '_header.php';

include '../tutorial/tutorial.html';

include '_footer.php'; ?>