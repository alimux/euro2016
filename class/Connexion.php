<?php
class  Connexion
	{
		private $_connexion;
		private $_db;
		
		

		//constructeur
		public function Connexion()
			{
				//echo "connexion en cours...";
				//connexion Mongo localhost
				
				$this->_connexion = new MongoClient();
				$this->_db = $this->_connexion->selectDB(DB);

				//$collectionName = COLLECTION;
				//echo $this->_db.'<br>';//echo COLLECTION;

				

			}

		public function getConnexion()
			{
				//renvoie la connexion à la base de donnée
				return $this->_db;
			}
	}
?>