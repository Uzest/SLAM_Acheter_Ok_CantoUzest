<?php
function verificationSql()
{
$num=$_GET['num'];
$key=$_GET['key'];

$host='localhost';
$user='jcanto';
$mdp='PscX57Q47';
$base='dbjcantoNewWorld';
$maBase=mysqli_connect($host,$user,$mdp,$base);
$requeteExistance='select utiNum from utilisateur where utiNum='.$num.' and utiCle="'.$key.'"';
$resultatRequeteExistance=mysqli_query($maBase,$requeteExistance);
$tabValeurExistance=mysqli_fetch_row($resultatRequeteExistance);
if ($tabValeurExistance[0]==$num)
{
$requete='select utiValidationEmail from utilisateur where utiNum='.$num.' and utiCle="'.$key.'"';
$resultatRequete=mysqli_query($maBase,$requete);
$tabValeur=mysqli_fetch_row($resultatRequete);
if ($tabValeur[0]==1)
{
	echo "<h3>Vous avez déjà validé votre email.</h3>";
}
else
{
$commandeModif='update utilisateur set utiValidationEmail=1 where utiNum='.$num.' and utiCle="'.$key.'" and utiValidationEmail=false and utiDateInscription -interval 7 day<current_date';

$resultat=mysqli_query($maBase,$commandeModif);

echo "<h3>Felicitation, vous avez bien validé votre mail.</h3>";

}
}
else
{
	echo "<h3>Veuillez cliquer sur le lien qui vous a été envoyé.</h3>";
}
}


 
include("haut.php");
include("menu.php");
?>
<div id="fondTerre" >
		<div id="colonneGFormulaire">
			<?php
			verificationSql();
			?>
		</div>
		
		<div id="colonneD">
			<p><img src="images/jardinier.jpg" alt="Jardinier" class="jardinier"></p>
			<p><img src="images/boucher.jpg" alt="Boucher" class="boucher"></p>
		
		</div>


</div>
<?php
include("basGris.php");
include("bas.php");
?>
