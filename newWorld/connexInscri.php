
			<?php
//mes fonctions
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
$nom=$_POST['nom'];
$prenom=$_POST['prenom'];
$pseudo=$_POST['pseudo'];
$email=$_POST['mail'];
$mdp=$_POST['mdp1'];
$ville=$_POST['ville'];
$tel=$_POST['tel'];
$rue=$_POST['rue'];
$lieu=$_POST['cp'];
$tabLieu=explode (";",$lieu);
$cp=$tabLieu[0];
$ville=$tabLieu[1];
//generation de la clé
$cle=md5(rand());
//obtention du prochain numero d'utilisateur
$requete='select ifnull(max(utiNum)+1, 1000) from utilisateur';
//execution de la requete
$resultat=mysqli_query($maBase,$requete);
//un seul fetch du resultat
$tabValeur=mysqli_fetch_row($resultat);
$max=$tabValeur[0];
//enregistrement dans la base
$req="insert into utilisateur values($max,'$nom','$prenom','$tel','$rue','$cp','$ville','$email','$pseudo',password('$mdp'),'$cle',false,now())";
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

?>








<?php
function envoyerFormulaire($tabChamp,$tabErreurs)
{
	?>
		<form name="leForm" method="POST" action="connexInscri.php">
		<?php
		foreach($tabChamp as $nomChamp=>$tabInfos)
		{
			$type = $tabInfos['type'];
			$id = $tabInfos['id'];
			$placeHolder = $tabInfos['placeHolder'];
			$label = $tabInfos['label'];
			$value = "";
			if(isset($_POST[$nomChamp]) && $nomChamp!="mdp1" && $nomChamp!="mdp2")
			{
				$value = $_POST[$nomChamp];
			}

			if(isset($tabErreurs[$nomChamp]))
			{
				echo $tabErreurs[$nomChamp];
			}
			echo "<p><label for='id'>$label</label><input type='$type' id='$id' placeholder='$placeHolder' name='$nomChamp' value='$value'></p>";


		}
	?>
	<label>Lieu: </label><input type="text" name="cp" id="CP" placeHolder=" ex: 05000-Gap " list="listeDesCommunes" oninput="remplirListeDesCommunes()">
		<datalist id="listeDesCommunes">
		</datalist>
		<p><input type="submit" value="S'inscrire"></input></p>
		</form>

		<script>
//cette fonction se lance quand la commune change
//elle met à jour la liste des communes commençant par ce qui a été saisi par l'utilisateur
function remplirListeDesCommunes()
{
  //recup du debut du code postal de la commune
  var debutCodeCommune=document.getElementById('CP').value;
  if(debutCodeCommune.length >2)//à partir de trois caractères
  {
	  var dataListeCommunes=document.getElementById('listeDesCommunes');
	  //j'efface toutes les options de la datalist
	  while(dataListeCommunes.options.length>0)
	  {
	    dataListeCommunes.removeChild(dataListeCommunes.childNodes[0]);
	  }
	  //je cree ma requete vers le serveur php
	  var request = new XMLHttpRequest();
	  // prise en charge des chgts d'état de la requete
	  request.onreadystatechange = function(response) 
	  {
	    if (request.readyState === 4) 
	    {
	      if (request.status === 200) 
	      {
		// j'obtient la reponse au format json et l'analyse on obtient un tableau
		var tabJsonOptions = JSON.parse(request.responseText);
		// pour chaque ligne du tableau reçu.
		var noLigne;
		for(noLigne=0;noLigne<tabJsonOptions.length;noLigne++)
		{ 
			// Cree une nouvelle <option>.
			var nouvelleOption = document.createElement('option');
			// on renseigne la value de l'option avec le numéro du produit.
			nouvelleOption.value = tabJsonOptions[noLigne].communeCP+';'+tabJsonOptions[noLigne].communeLibelle;
			//on affiche aussi le codePostal et la commune
			nouvelleOption.text=nouvelleOption.value;
			// ajout  de l'<option> en tant qu'enfant de la liste de selection des produits.
			dataListeCommunes.appendChild(nouvelleOption);
		}

	       } 
	       else 
	       {
		  // An error occured :(
		  alert("Couldn't load datalist options :(");
	       }
	    }
	  };
	  //recup du debut du code postal de la commune
	  var debutCodeCommune=document.getElementById('CP').value;
	  //formation du texte de la requete
	  var texteRequeteAjax='jsonListeDesCommunes.php?debutCommune='+debutCodeCommune;
	  //je l'ouvre
	  request.open('GET', texteRequeteAjax, true);
	  //et l'envoie
	  request.send();
  }//fin du si + de deux caractères ont été saisi
}
</script>
		<?php
}

$tabErreurs = array();


function controleDuFormulaireOK($tab, &$tabErreurs)
{
	$tab=$_POST;
	$ok=true;
	$nb=false;
	$maj=false;
	$min=false;
	if (strlen($tab['nom'])<2)
	{
		$ok=false;
		$tabErreurs['nom']="<p class=\"erreur\">Le nom n'est pas assez long.</p>";
	}
	if (strlen($tab['prenom'])<2)
	{
		$ok=false;
		$tabErreurs['prenom']="<p class=\"erreur\">Le prenom n'est pas assez long.</p>";
	}
	if (strlen($tab['mdp1'])<8)
	{
		$ok=false;
		$tabErreurs['mdp1']="<p class=\"erreur\">Le mot de passe n'est pas assez long.</p>";
	}
	if ($tab['mdp1']!=$tab['mdp2'])
	{
		$ok=false;
		$tabErreurs['mdp2']="<p class=\"erreur\">Les mots de passes ne correspondent pas.</p>";
	}
	if (strlen($tab['pseudo'])<3)
	{
		$ok=false;
		$tabErreurs['pseudo']="<p class=\"erreur\">Le pseudo est trop court.</p>";
	}
	if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $tab['mail']))
	{
	}
	else
	{
		$ok=false;
		$tabErreurs['mail']="<p class=\"erreur\">Cet email est incorrect.</p>";
	}
	$mdpLong=strlen($tab['mdp1']);
	for($cpt=0;$cpt<$mdpLong;$cpt++)
	{
		if($tab['mdp1'][$cpt]>='a' && $tab['mdp1'][$cpt]<='z')
		{
			$min=true;
		}
		if($tab['mdp1'][$cpt]>='A' && $tab['mdp1'][$cpt]<='Z')
		{
			$maj=true;
	        }
		if($tab['mdp1'][$cpt]>=0 && $tab['mdp1'][$cpt]<=9)
		{
			$nb=true;
		}
	}
	if ($maj==false || $min==false || $nb==false)
	{
		$ok=false;
		$tabErreurs['mdp1']="<p class=\"erreur\">La composition du mot de passe n'est pas assez complexe.(Majuscule,minuscule,chiffre, 8 caractères minimum)</p>";
	}
        $numTelephone=$_POST['tel'];
        $numTelephoneSansEspace=str_replace(' ','',$numTelephone);
        $nombreCaracTel=strlen($numTelephoneSansEspace);
        $nombreTelChiffre=0;
	if(!(preg_match('`[0-9]{10}`',$numTelephoneSansEspace)))
	{
		$tabErreurs['tel']="<p class=\"erreur\">Veuillez entrer un numéro de téléphone de 10 chiffres.</p>";
	}
        $localite=$_POST['cp'];
	$lieuSansEspace=str_replace(' ','',$localite);
        if (!(preg_match("#^[0-9]{5};[a-zA-Z]{3,}#i",$variableAMettreICI))) //ça ne fonctionne pas pour le moment
	
        //if (strlen($tab['cp'])!=5)
        {
            $ok=false;
            $tabErreurs['cp']="<p class=\"erreur\">Veuillez entrer un lieu correcte exemple : 05000;Gap</p>";
        }
	
        if (strlen($tab['rue'])<2)
        {
            $ok=false;
            $tabErreurs['rue']="<p class=\"erreur\">Veuillez entrer un nom de rue valide.</p>";
        }






	return $ok;

}

function envoyerMail()
{
$nom=$_POST['nom'];
$prenom=$_POST['prenom'];
$mail=$_POST['mail'];

//debut du sql
//connexion à la base de donnée
$host='localhost';
$user='jcanto';
$mdp='PscX57Q47';
$base='dbjcantoNewWorld';
$maBase=mysqli_connect($host,$user,$mdp,$base);
$requete='select utiNum,utiCle from utilisateur where utiValidationEmail="'.$mail.'"';
$resultat=mysqli_query($maBase,$requete);

$tabValeur=mysqli_fetch_row($resultat);
$utiNum=$tabValeur[0];
$cle=$tabValeur[1];





     $to = $mail;

     // Sujet
     $subject = 'Inscription NewWorld';

     // message
     $message = '
     <html>
      <head>
       <title>Confirmation de l\'addresse email.</title>
      </head>
      <body>
       <p>Cher '.$nom.' '.$prenom.',</p>
<p>'.$mail.'</p>
<p>Nous vous remercions pour votre inscription avec nous. Votre nouveau compte a été mis en place.</p>
<p>Pour finir votre inscription il suffit de cliquer sur ce lien http://172.16.63.111/~jcanto/newWorld/verifEmail.php?num='.$utiNum.'&key='.$cle.' </p>
      </body>
     </html>
     ';

     // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
     $headers  = 'MIME-Version: 1.0' . "\r\n";
     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
     $headers .= 'From: NewWorld <newworld@noreplay.com>' . "\r\n";

     // Envoi
     mail($to, $subject, $message, $headers);


}


include("haut.php");
include("menu.php");
?>
<div id="fondTerreFormulaire" >
		<div id="colonneG">

<h1>Inscription</h1>
<?php
//le corps du probleme
$tabChamp=array(
'nom'=>array('id'=>'idNom','label'=>'Nom: ','placeHolder'=>'Tapez votre nom', 'type'=>'text'),
'prenom'=>array('id'=>'idPrenom','label'=>'Prenom: ','placeHolder'=>'Tapez votre prenom', 'type'=>'text'),
'mail'=>array('id'=>'idMail','label'=>'Email: ','placeHolder'=>'Tapez votre email', 'type'=>'email'),
'pseudo'=>array('id'=>'idPseudo','label'=>'Pseudo: ','placeHolder'=>'Tapez votre pseudo', 'type'=>'text'),
'mdp1'=>array('id'=>'idMdp1','label'=>'Mot de passe: ','placeHolder'=>'Tapez votre mot de passe', 'type'=>'password'),
'mdp2'=>array('id'=>'idMdp2','label'=>'Mot de passe (confirmation): ','placeHolder'=>'Confirmez votre mot de passe', 'type'=>'password'),
'tel'=>array('id'=>'idTel','label'=>'Teléphone:','placeHolder'=>'ex:06 43 54 87 XX','type'=>'text'),
'rue'=>array('id'=>'idRue','label'=>'Rue:','placeHolder'=>'Tapez votre rue','type'=>'text'),
);

if(!(leFormulaireAEtePoste($tabChamp)))
{
	envoyerFormulaire($tabChamp, $tabErreurs);
}
else //le formulaire a été posté on va le vérifier
{
	if(controleDuFormulaireOk($tabChamp,$tabErreurs))
	{
		

		if(enregistrerDansLaBase())
		{
		envoyerMail();
		rendreCompte('<h3>Votre inscription a été réalisée avec succès.</h3><h3>Bienvenue chez nous</h3><p>Un mail de confirmation vous a été envoyé sur votre adresse email.</p>');
		}
		else
		{
		rendreCompte('Problème de connexion à la base de donnée.');
		}
	}
	else
	{
		envoyerFormulaire($tabChamp,$tabErreurs);
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
