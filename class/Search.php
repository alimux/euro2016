<?php
/*----------------------------------
search class
--------------------------------------*/
class Search
	{
		private $_searchExpression;
		private $_keywordForm;
		private $_collection;
		private $_limit;
		private $_skip;
		private $_cursor;
		private $_score;
		private $_class;

		public function Search($keyWord, $dataBase)
			{
				
				if(isset($_POST['creneau']))
					{
						//echo "le créneau est :". $_POST['creneau'];
						switch ($_POST['creneau'])
						 {
							case 1:
								$collection = "collection1";
								break;
							case 2:
								$collection = "collection2";
								break;
							case 3:
								$collection = "collection3";
								break;
							
							default:
								# code...
								break;
						}
						$this->_collection = $dataBase->selectCollection($collection);

					}
				else
					{
						$this->_collection = $dataBase->selectCollection(COLLECTION);
						
							
					}
				/* Dans mongoDB, on fait passer des Tableaux pour les recherches */
				$this->_keywordForm = $keyWord;
				$this->_searchExpression = array("text"=>new MongoRegex("/".$this->_keywordForm."/i"));



			}


		public function getAnalytics($limit, $skip)
			{
					if($this->_keywordForm=="")
					{
						return null;
					}
					else
					{
						//echo "limite : ".$limit;
						$this->_limit = $limit;
						$this->_skip = $skip;

						//recherche dans les tweets
						//({$text: {$search: "montext"}})
						$this->_cursor = $this->_collection->find($this->_searchExpression)->limit($this->_limit)->skip($this->_skip);	
						$this->tweetSentimentAnalysis();

						return $this->_cursor;
					}
			}

		public function countAnalytics()
			{
				//comptage des enregistrements
				$countReccords = $this->_collection->find($this->_searchExpression)->count();
				
				return $countReccords;
			}

		public function tweetSentimentAnalysis()
			{
				$strings=array();
				foreach ($this->_cursor as $num_string=>$string)
					{
						$strings[$num_string]=$string["text"];
					}

					$sentiment = new \PHPInsight\Sentiment();
					foreach ($strings as $string) 
						{

							// calculations:
							$this->_scores = $sentiment->score($string);
							$this->_class = $sentiment->categorise($string);
						}
			}

		public function getScore()
			{
				$sentimentScore = "<a class=\"waves-light btn red\">Negative value : ".$this->_scores['neg']."</a>
				<a class=\"waves-light btn green\">Positive value : ".$this->_scores['pos']."</a>
				<a class=\"waves-light btn blue\">Neutral value : ".$this->_scores['neu']."</a> ";
				return $sentimentScore;
			}
		public function getClass()
			{
				if($this->_class=="pos")
				{
					$sentimentDominant = "<span class=\"new badge Positive green\" data-badge-caption=\"\">Positive</span>";
				}
				else if($this->_class=="neg")
				{
					$sentimentDominant = "<span class=\"new badge Negative red\" data-badge-caption=\"\">Negative</span>";
				}
				else if($this->_class=="neu")
				{
					$sentimentDominant = "<span class=\"new badge Neutral blue\" data-badge-caption=\"\">Neutral</span>";
				}
				return $sentimentDominant;
			}

	}

?>