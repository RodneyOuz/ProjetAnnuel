<?php

namespace App\Core;


class Database
{

	private $pdo;
	private $table;

	public function __construct(){
		try{
			$this->pdo = new \PDO(DBDRIVER.":dbname=".DBNAME.";host=".DBHOST.";port=".DBPORT, DBUSER, DBPWD);

			$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    		$this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

		}catch(Exception $e){
			die ("Erreur SQL ".$e->getMessage());
		}

		//echo get_called_class(); //  App\Models\User ici on peut récupérer le nom de la table
		$classExploded = explode("\\", get_called_class());
		$this->table = DBPREFIX.end($classExploded);
		// echo "Nom de la table : " .$this->table. "</br>";
	}

	public function save(){

		//INSERT ou un UPDATE

		
		// Array ([firstname] => Yves [lastname] => Skrzypczyk [email] => y.skrzypczyk@gmail.com [pwd] => Test1234 [country] => fr [status] => 0 [role] => 0 [isDeleted] => 0 [pdo] => PDO Object ( ) [table] => )
		//print_r(get_object_vars($this));
		
		//Array ( [pdo] => [table] => )
		//print_r(get_class_vars(get_class()));

		//Créer une requête SQL Dynamique en fonction de la class enfant
		//Pour faire un insert ou un update.
		//Si l'objet a un ID il s'agit d'un update

		//Array ( [firstname] => Yves [lastname] => Skrzypczyk [email] => y.skrzypczyk@gmail.com [pwd] => Test1234 [country] => fr [status] => 0 [role] => 0 [isDeleted] => 0 )


		$data = array_diff_key (
					
					get_object_vars($this), 

					get_class_vars(get_class())

				);
		/*$class = get_class(); // App\Core\Database
		echo "Nom de la classe : " .$class. "</br>";
		$class_vars = get_class_vars($class);  // Array ( [pdo] => [table] =>)
		echo "class vars : ";
		print_r($class_vars);
		echo "</br>";
		$object_vars = get_object_vars($this); 
		echo "Object vars : ";
		print_r($object_vars); //  Array ( [firstname] => sarah [lastname] => mcdonald [email] => sarahmac@gmail.com [pwd] => aqzsedrf [country] => fr [status] => 0 [role] => 0 [isDeleted] => 0 [pdo] => PDO Object ( ) [table] => mqwp_User )*/


		if(is_null($this->getId())){
		
			echo $this->getId();
			//INSERT 

			$columns = array_keys($data); // Array ( [0] => firstname [1] => lastname [2] => email [3] => pwd [4] => country [5] => status [6] => role [7] => isDeleted )
			// print_r(implode(",", $columns)); // firstname,lastname,email,pwd,country,status,role,isDeleted
			print_r(implode(",:", $columns));
			$query = $this->pdo->prepare("INSERT INTO ".$this->table." (
											".implode(",", $columns)."
											) VALUES (
											:".implode(",:", $columns)."
											)");

		}else{
			//UPDATE 
			foreach ($columns as $column) {

				$columnsTopdate[] = $column."=:".$column;
				print_r($columnsToUpdate);
        	}

        $query = $this->pdo->prepare("UPDATE ".$this->table." SET ".implode(",",$columnsToUpdate)." WHERE id=".$this->getId());
		}

		$query->execute($data);

	}

}