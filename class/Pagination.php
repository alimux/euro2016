<?php
/*----------------------------------------------------
gestion de la pagination
-------------------------------------------------------*/

class Pagination
	{
		private $_msgPerPage = 10;
		private $_totalPages;
		private $_currentPage;
		private $_firstEntry;
		private $_keyWord;


		public function Pagination($totalReccords)
		{
			//définition du nombre de page
			$this->_totalPages = ceil($totalReccords / $this->_msgPerPage);

			//initialisation par défaut de la page courante 
			$this->_currentPage = 1;
			if(isset($_GET['page']) && $_GET['page'] <= $this->_totalPages)
					{
						$this->_currentPage = $_GET['page'];
					}

			else
					{
								$this->_currentPage = 1;
					}
					//echo "page : courante ".$this->_currentPage;

			
		}

		public function affichagePagination()
			{
				$i = 1;
				if(isset($_POST['searchTweets']))
					{
						$this->_keyWord = $_POST['searchTweets'];
					}
				if(isset($_GET['searchTweets']))
					{
						$this->_keyWord = $_GET['searchTweets'];
					}
					
				
				echo "<ul class=\"pagination\"> 
				<li class=\"disabled\"><a href=\"#!\"><i class=\"material-icons\">chevron_left</i></a></li>";
					//boucle
					for($i == 1; $i<= $this->_totalPages; $i++)
						{
							 if($i==$this->_currentPage)
							 	{
							 	echo "
   								 <li class=\"active\"><a href=\"index.php?page=$i&searchTweets=".$this->_keyWord."\">$i</a></li>";
   								}
   							else
   							{
   								echo "
   				 <li class=\"active\"><a href=\"index.php?page=$i&searchTweets=".$this->_keyWord."\">$i</a></li>";			
   							}

   						}
   				echo "<li class=\"waves-effect\"><a href=\"#!\"><i class=\"material-icons\">chevron_right</i></a></li>
   					</ul>";
			}
		
		public function getLimit()
			{
				//renvoie le paramètre skip de la recherche
				return $this->_msgPerPage;

			}
		public function getSkip()
			{
				//premiere entrée
			$this->_firstEntry = ($this->_currentPage-1) * $this->_msgPerPage;
			//echo "<br> page courante : ".$this->_currentPage."<br> Premiere entrée :".$this->_firstEntry."<br>";
				//renvoie le paramètre limite de la recherche
				return $this->_firstEntry;

			}
	}

?>