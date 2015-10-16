<?
session_start();

//Connexion à la base de données

$host='localhost';
$user='jcanto';
$mdp='PscX57Q47';
$base='dbjcantoNewWorld';
//connexion
$maBase=mysqli_connect($host,$user,$mdp,$base);


$tabRes=array();
$debutCommune=$_GET['debutCommune'];
$requete="select communeCodeInsee, communeLibelle, communeCP from communes where communeCP like '$debutCommune%'";
$curseur=mysqli_query($maBase,$requete);
while($tab=mysqli_fetch_assoc($curseur))
{
        $tabRes[]=$tab;
}
echo json_encode($tabRes);
?>
