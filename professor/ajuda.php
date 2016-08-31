<?php
include_once '../private/DBconfig.php';
$userManager->checarPrevilegio( true );

$tituloPag    = 'Ajuda do DFA judge';
$tituloHeader = 'Ajuda do DFA judge';
$backPag      = 'home.php';
$pagAtiva     = 'OPCOES';
$mathjax      = true;
include '_header.php';

include '../tutorial/tutorial.html';

include '_footer.php'; ?>