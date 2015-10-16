<?php
	include("haut.php");

?>
<datalist id="idProduitData">
<?php
// on se connecte à MySQL 
$db = mysqli_connect('localhost', 'jcanto', 'PscX57Q47','dbjcantoNewWorld'); 


// on crée la requête SQL 
$sql = 'select typeId,typeLibelle from typeProd'; 

// on envoie la requête 
$resultat = mysqli_query($db,$sql);// or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
// on fait une boucle qui va faire un tour pour chaque enregistrement 
while($data = mysqli_fetch_assoc($resultat)) // array/row/assoc
    { 
    // on affiche les informations de l'enregistrement en cours 
    echo "<option value=\"$data[0]\">$data[1]</option>";
    echo $data[0];
    echo $data[1];
    } 

?>
</datalist>


<datalist id="modeProductionLot">
<option value="Bio">
<option value="naturel">
<option value="traditionnel">
<option value="Hors sol">
</datalist>
<datalist id="uniteDeMesure">
<option value="Kg">
<option value="cagette">
<option value="sac">
<option value="litre">
<option value="palette">
<option value="bidon">
<option value="unité">
<option value="gramme">
</datalist>
<?php
//Définition des champs du formulaire
$tab=array();
$tab['date']=array('id'=>'idDate','label'=>'*Date de production: ','placeholder'=>'date','type'=>'date');
$tab['nbJourConservationLot']=array('id'=>'idNbJourConservationLot','label'=>'*Nombre de jour de conservation: ','type'=>'number','placeholder'=>'nombre de jour','value'=>'1');
$tab['quantiteDispo']=array('id'=>'idQuantiteDispo','type'=>'text','label'=>'*Quantité vendu: ','placeholder'=>'ex: 3.8');
$tab['qttMinimaleLot']=array('id'=>'idQttMinimaleProduit','label'=>'*Quantite minimale du produit: ','type'=>'number','placeholder'=>'Quantite minimale','value'=>'1');
$tab['prix']=array('id'=>'idPrix','label'=>'*Prix à l\'unité: ','type'=>'text','placeholder'=>'Prix de l\'unité');
$tab['parcelle']=array('id'=>'idParcelle','label'=>'Parcelle: ','type'=>'text','placeholder'=>'Id de la parcelle');
$tab['commune']=array('id'=>'idCommune','label'=>'*CP commune: ','type'=>'text','placeholder'=>'Code postale de la commune');


//Fonction qui controle le formulaire
function controleDuFormulaireOk($tab,&$tabErreur)
{
	$tab=$_POST;
	$OK=true;
	//Vérification du prix
	if ($tab['prix']<=0)
	{
		$OK=false;
		$tabErreur['prix']="<p class=\"erreur\">Le prix ne peut être inférieur ou égal à 0</p>";
	}
	if ($tab['nbJourConservationLot']<2)
	{
		$OK=false;
		$tabErreur['nbJourConservationLot']="<p class=\"erreur\">Le conservation doit être au moins de deux jours.</p>";
	}

	if ($tab['quantiteDispo']<1)
	{
		$OK=false;
		$tabErreur['quantiteDispo']="<p class=\"erreur\">1 unité au minimum.</p>";
	}



	if ($tab['qttMinimaleLot']<1)
	{
		$OK=false;
		$tabErreur['qttMinimaleLot']="<p class=\"erreur\">1 unité au minimum.</p>";
	}
	if ($tab['qttMinimaleLot']>$tab['quantiteDispo'])
	{
		$OK=false;
		$tabErreur['qttMinimaleLot']="<p class=\"erreur\"> La quantité minimale ne peut être supérieur à la quantité disponible.</p>";
	}

	$dateCourante = date("d/m/Y");
       	

	if (time()-strtotime($tab['date'])<24*60*60)
	{
		$OK=false;
		$tabErreur['date']="<p class=\"erreur\">La date doit être antérieure à aujourd'hui.</p>";
	}
        if (strlen($tab['commune'])!=5)
        {
            $OK=false;
            $tabErreur['commune']="<p class=\"erreur\">Veuillez entrer un code postale de 5 chiffres.</p>";
        }	
	//Vérification de la date
	//Vérification 
	return $OK;
}

//Le formulaire a été posté
function leFormulaireAEtePoste($tab)
{
	foreach($tab as $nomChamp=> $tableau)
	{
		if(!isset($_POST[$nomChamp]))
		{
			return false;
		}
	}
	return true;
	
}

function enregistrerDansLaBase()
{
	//connexion à la base de donnée

$host='localhost';
$user='jcanto';
$mdp='PscX57Q47';
$base='dbjcantoNewWorld';
//connexion
$maBase=mysqli_connect($host,$user,$mdp,$base);
//recuperer les données à enregistrer depuis le tableau $_POST

$modeProd=$_POST['modeProduction'];
$uniteMesure=$_POST['uniteDeMesure'];
$dateRecolte=$_POST['date'];
$nbJour=$_POST['nbJourConservationLot'];
$prix=$_POST['prix'];
$quantiteDispo=$_POST['quantiteDispo'];
$quantiteMini=$_POST['qttMinimaleLot'];
$commune=$_POST['commune'];
$parcelle=$_POST['parcelle'];
if (empty($parcelle))
{
	$parcelle=0;
}
$uti=1000;
//execution de la requete
$requete='select ifnull(max(lotNum)+1, 1000) from lot';
$resultat=mysqli_query($maBase,$requete);
//un seul fetch du resultat
$tabValeur=mysqli_fetch_row($resultat);
$num=$tabValeur[0];

//enregistrement dans la base
$req="insert into lot values($num,'$modeProd','$uniteMesure','$dateRecolte',$nbJour,$prix,$quantiteDispo,$quantiteMini,'$commune',$parcelle,1,$uti)";
//execution de la requete
$resultat=mysqli_query($maBase,$req);
return $resultat;

}

function rendreCompte($message)
{
	?>
		<div>
	<?php
			echo $message;
	?>
		</div>
	<?php
}

//FORMULAIRE
function envoyerLeFormulaire($tabChamp, $tabErreur)
{	
	?>
		<form name="formulaire" method="POST" action="formulaireLot.php">
		<h6>Tout les champs annontés d'une * sont obligatoires.</h6>
		<?php
		foreach($tabChamp as $nomChamp=>$tabInfos)
		{
			$type = $tabInfos['type'];
			$id = $tabInfos['id'];
			$placeHolder = $tabInfos['placeholder'];
			$label = $tabInfos['label'];
			$value = "";
			if(isset($_POST[$nomChamp]))
			{
				$value = $_POST[$nomChamp];
			}

			if(isset($tabErreur[$nomChamp]))
			{
				echo $tabErreur[$nomChamp];
			}
			echo "<p><label for='id'>$label</label><input type='$type' id='$id' placeholder='$placeHolder' name='$nomChamp'"; 
			if($nomChamp!="parcelle" )
			{   
				echo "required";
			} 
			echo ">";
			if($nomChamp=='quantiteDispo')
			{
?>					
			<select name="uniteDeMesure" id="uniteDeMesure">
				<option value="1">g</option>
				<option value="2">kg</option>
				<option value="3">cagette</option>
				<option value="4">bidon 2L</option>
				<option value="5">sac</option>
				<option value="6">filet</option>
				<option value="7">bidon 1L</option>
			</select>
<?php
			}
		echo "</p>";


		}

		?>
		<p>
			<label>*Produit:</label>
		<select name="categorie" id="idCategorie">
			<optgroup label="Légumes">
				<option value="1">Pommes de terre</option>
				<option value="5">Radis</option>
				<option value="2">Salades</option>
			</optgroup>
			<optgroup label="Viande">
				<option value="3">Volailles</option>
				<option value="4">Boeuf</option>
		</select>



		</p>

<?php
		if(isset($_POST['uniteMesure']))
		{
			$value = $_POST['uniteMesure'];
		}
	if(isset($tabErreur['uniteMesure']))
	{
		echo $tabErreur['uniteMesure'];
	}
?>
		
		<p>
			<label>*Mode de production: </label>
			<select name="modeProduction" id="modeProduction">
				<option value="1">Bio</option>
				<option value="2">Naturel</option>
				<option value="3">Tradition</option>
				<option value="4">Hors-sol</option>
			</select>

		</p>		
		<input type="submit" value="Envoyer">
		</form>
		<?php

}




	include("menu.php");
?>

<div id="fondTerreFormulaire">

	<div id="colonneGFormulaire">
<h3>Saisie des lots</h3>
<?
//Le corps du script
//Pas d'erreur
$tabErreur=array();

//si le formulaire n'a pas été posté ou qu'il est mal rempli

if(!(leFormulaireAEtePoste($tab)))
{
	envoyerLeFormulaire($tab,$tabErreur);
}
else
{
	if (controleDuFormulaireOk($tab,$tabErreur))
	{

		if(enregistrerDansLaBase())
		{
			rendreCompte('<h3>Votre lot a bien été enregistré.</h3>');
		}
		else
		{
			rendreCompte('Problème de connexion à la base de données.');
		}

	}
	else
	{
	envoyerLeFormulaire($tab,$tabErreur);
	}
}
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
