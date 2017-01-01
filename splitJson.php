<?php
/*----------------------------------------------------
PROJECT SMCAEN / EURO2016
Traitement du fichier JSON
------------------------------------------------------*/

//Déblocage de la mémoire pour le traitement du fichier
@ini_set('memory_limit', '2048M');

// ouverture du fichier Json
$jsonFile = file_get_contents("json/euro2016Tweets.json");
//Décodage du fichier Json sous forme de Tableau
$jsonDatas = json_decode($jsonFile, true);


// vérification d'erreur Json
$error = json_last_error();
echo "<br>Erreur : $error<br>";


//vérification que c'est un tableau et qu'il ne renvoie pas null/vide
	if (is_array($jsonDatas))
		{
			echo 'non null ! <br/>';
			$jsonDatas !== false && isset($jsonDatas['created_at']);
			
				/*------------------------------------------------------------------------------------------------------------------
				boucle de lecture des données
				A l'aide d'une expression régulière, on isole les enregistrements en fonctions des dates pour créer 3 collections
				------------------------------------------------------------------------------------------------------------------*/

				$pattern1 = '/\bSat Jul 02\s|\bSun Jul 03\s|\bMon Jul 04\s/i';
				$pattern2 = '/\bTue Jul 05\s|\bWed Jul 06\s|\bThu Jul 07\s|\bFri Jul 08\s/i';
				$pattern3 = '/\bSat Jul 09\s|\bSun Jul 10\s|\bMon Jul 11\s/i';
				//initialisation des variables
				$jsonOutput ='';
				$jsonOutput2 ='';
				$jsonOutput3 ='';
				foreach ($jsonDatas as $i => $values)
					{
						
						//collection 1
						if(preg_match($pattern1, $values['created_at']))
							{
								$jsonOutput .= json_encode($values).", \n";
							}
						//collection 2
						if(preg_match($pattern2, $values['created_at']))
							{
								$jsonOutput2 .= json_encode($values).", \n";
							}
						//collection 3
						if(preg_match($pattern3, $values['created_at']))
							{
								$jsonOutput3 .= json_encode($values).", \n";
							}
	
					}
			
			//préparation du formatage json
			$jsonOutput = '['.$jsonOutput.']';
			$jsonOutput2 = '['.$jsonOutput2.']';
			$jsonOutput3 = '['.$jsonOutput3.']';

			/*-------------------------------------------------
			Création des fichiers de collections
			--------------------------------------------------*/
			$fp = fopen('json/collection1Euro2016.json', 'a+');
				if($fp==false)
					{
						echo 'echec de la création du fichier !';
					}
				else
					{
						fwrite($fp, $jsonOutput);
						fclose($fp);
					}
			$fp2 = fopen('json/collection2Euro2016.json', 'a+');
				if($fp2==false)
					{
						echo 'echec de la création du fichier !';
					}
				else
					{
						fwrite($fp2, $jsonOutput2);
						fclose($fp2);
					}
			$fp3 = fopen('json/collection3Euro2016.json', 'a+');
				if($fp3==false)
					{
						echo 'echec de la création du fichier !';
					}
				else
					{
						fwrite($fp3, $jsonOutput3);
						fclose($fp3);
					}

				
		}
	else
	{
		print_r($jsonDatas);
		echo'null';
	}

?>