<?php
require_once ('../src/config.php');
require_once ('../src/classes/Session.php');
$firstname  = array( 'David', 'Niklas', 'Marius', 'Jonas', 'Leon');
$lastname   = array( 'Feldhoff', 'Devenish', 'Mamsch', 'Elfering', 'Stapper');
$mail       = array( 'feldhoff.david@gmail.com', 'devenishniklas@gmail.com', 'marius.mamsch@gmail.com', 'jonas.elfering@gmail.com', 'leonstapper96@gmail.com');
$username   = array( 'xgwsdfe', 'xgwsnde', 'xgadmmh', 'xgadelf', 'xgadles');
$birthday   = array( '1994-05-04', '1996-12-16', '1996-05-15', '1996-06-03', '1996-06-02');
$street     = array( 'Moorstr.', 'Roter Weg', 'Siemensstr.', 'Zur Ritze', 'Buchdahlstr.');
$housenumber= array( '88a', '1', '88', '69', '00');
$zip        = array( "48432", "48165", "48432", "48432", "48429");
$iban       = array( "123", "345", "678", "901", "234" );
$bic        = array( 'WELADED1RHN', 'WELADED1RHN','WELADED1RHN','WELADED1RHN', 'WELADED1RHN');
for($i = 0; $i < count($username); $i++) {
    Session::create_user(
        $firstname[$i],
        $lastname[$i],
        $mail[$i],
        $username[$i],
        'test',
        'test',
        $birthday[$i],
        $street[$i],
        $housenumber[$i],
        $zip[$i],
        $iban[$i],
        $bic[$i]
    );
}


?>