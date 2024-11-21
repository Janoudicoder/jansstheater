<? // database connectie en inlogfuncties
// ===================================
// vul hier alle databasegegevens in
// =================================
define("HOST", "localhost"); // host
define("USER", "cms2023_db"); // gebruikersnaam database
define("PASSWORD", "PFfDzfPvvF"); // wachtwoord database
define("DATABASE", "cms2023_db"); // naam database

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
// if you are connecting via TCP/IP rather than a UNIX socket remember to add the port number as a parameter
// =========================================================================================================
mysqli_set_charset($mysqli, 'utf8');


$query = $mysqli->query("UPDATE sitework_blocks SET tekst = '".$mysqli->real_escape_string($_POST['tekst'])."' 
    WHERE id = '".$_POST['update_id']."'") or die($mysqli->error.__LINE__);

$blocks = $mysqli->query("SELECT * FROM sitework_blocks WHERE id = '".$_POST['add_id']."'") or die($mysqli->error.__LINE__);
$rowBlock = $blocks->fetch_assoc();

?> 

<textarea name="tekst<?=$_POST['add_id'];?>" class="inputveld invoer dropdown block_tekst" cols="60" unselectable="off" style="height:300px;"><?=$_POST['tekst'];?></textarea>
