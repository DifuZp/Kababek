<?php
$login_form = file_get_contents("login.html"); //načteme ze souboru "register.html" html tabulku pro registraci
$register_form = file_get_contents("register.html"); //načteme ze souboru "login.html" html tabulku pro přihlášení

function registerUser($jmeno, $heslo, $heslo2){  //registrace
    $moje_bool = false;
    if(trim($heslo)==trim($heslo2)){            //odstraníme /
        if($jmeno != null && $heslo != null){   // pokud nesjou pole prázdne tak zapíše data zadaná u registrace to .txt souboru
            $newuser = $jmeno . ":" . $heslo . "\n";  
            file_put_contents("databaze.txt", $newuser, FILE_APPEND | LOCK_EX); //zapíše data do souboru
            $moje_bool = true;
        }else{
            echo("Není vše vyplněno");  //error massage že je některé z polí prázdné
        }
    }else{
        echo("Předchozí Heslo Není Stejné"); // hesla se neshodují
    }
    return $moje_bool;
};

function login($inputUser, $inputPassword){    //přihlášení 
    $db_file = file("databaze.txt");           // soubor ze kterého budeme brát přihlašovací údaje
    $logged = false;
    $is_right = '';
    if($inputUser == null || $inputPassword == null){ // pokud je jedno z polí prázdné tak hodí error massage
        echo("Info Nevyplněno");
    };
    foreach ($db_file as $users) {      //porovná informace pro ověření z .txt souboru
        $user_arr = explode(":", $users); //rozdělí data mezi ":" odsebe
        $uzivatel = isset($user_arr[0])?($user_arr[0]):''; //kontroluje jestli zadané informace tam jsou
        $heslo = isset($user_arr[1])?($user_arr[1]):'';

        if($inputUser == trim($uzivatel) && $inputPassword == trim($heslo)){  //
            $is_right = '';
            $logged = true;
            break;
        }else{
            $is_right = "Špatné údaje"; //error massage pro špatně zadané údaje
        }
    }
    echo($is_right);        //vše je správně
    return array($logged, $inputUser);  
};

if(isset($_POST['loginn'])){            //vezme informace z tabulky po zmáčknutí tlačítka
    list($bool, $username) = login($_POST['username'], $_POST['password']);
    if($bool){
        echo($username. " is logged");  //pozdrav na úspěšné přihlášení
    }
}elseif(isset($_POST['registerr'])){    //pokud zmáčkneme tlačítko tak nám naše 
    if(registerUser($_POST['username2'], $_POST['password2'], $_POST['password3'])){
        echo($login_form);          //načte html soubor pro přihlášení
    }else{
        echo($register_form);       //načte html soubor pro registraci
    }
}elseif(isset($_POST['to_login'])){
    echo($login_form);               //načte html soubor pro přihlášení
}else{
    echo($register_form);           //načte html soubor pro registraci
}

?>