<?php

		class Post {
			//DB stuff

			 private $conn;
    private $table = 'posts';


			//posts properties

			public $id;
			public $category_id;
			public $category_name;
			public $title;
			public $body;
			public $author;
			public $created_at;

			//constructor with DB

			public function __construct($db) {
				$this->conn = $db;
			}

				//Get Posts

			public function read() {
				//create query

				$query = 'SELECT 
							c.name as category_name,
							p.id,
							p.category_id,
							p.title,
							p.body,
							p.author,
							p.created_at
							FROM
							 ' . $this->table . ' p
							 LEFT JOIN 
							 	categories c ON p.category_id = c.id
							 ORDER BY 
							 	p.created_at DESC';

							 	//prepare statement

							 	$stmt = $this->conn->prepare($query);


							 	//Execute query

							 	$stmt->execute();

							 	return $stmt;
			}

			//Get Single Post


			public function read_single(){
				 //create query for single post


				$query = 'SELECT
							c.name as category_name,
							p.id,
							p.category_id,
							p.title,
							p.body,
							p.author,
							p.created_at 
						FROM
							' .$this->table .' p 
						LEFT JOIN
							categories c ON p.category_id = c.id
						WHERE 
							p.id = ?
						LIMIT 0,1';

						//PREPARE STATEMENT

						$stmt = $this->conn->prepare($query);


						//BIND ID
						$stmt->bindParam(1, $this->id);

						//EXECUTE QUERY
						$stmt->execute();

						$row = $stmt->fetch(PDO::FETCH_ASSOC);

						//SET PROPERTIES
						$this->title = $row['title'];
						$this->body = $row['body'];
						$this->author = $row['author'];
						$this->category_id = $row['category_id'];
						$this->category_name = $row['category_name'];



			}




			//CREATE POST

			public function create(){
				//CREATE QUERY

				$query = ' INSERT INTO ' . 
							$this->table . '
							SET
								title = :title,
								body = :body,
								author = :author,
								category_id = :category_id';

				//PREPARE STATEMENT

								$stmt = $this->conn->prepare($query);
				//SANITIZE INCOMING DATA INPUTS
								$this->title = htmlspecialchars(strip_tags(title));
								$this->body = htmlspecialchars(strip_tags(body));
								$this->author = htmlspecialchars(strip_tags(author));
								$this->category_id = htmlspecialchars(strip_tags(category_id));
							

				//BIND DATA
								$stmt->bindParam(':title', $this->title);
								$stmt->bindParam(':body', $this->body);
								$stmt->bindParam(':author', $this->author);
								$stmt->bindParam(':category_id', $this->category_id);



				//Execute QUERY
						if ($stmt->execute()) {
							return true;
						}

							//PRINT ERROR IF SOMETHING GOES WRONG
						printf("Error: %S. \n", $stmt->error);

						return false;

							
			}
		}


?>