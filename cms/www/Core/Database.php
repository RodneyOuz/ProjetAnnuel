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





		if(is_null($this->getId())){
			//INSERT 
			$columns = array_keys($data);
			$query = $this->pdo->prepare("INSERT INTO ".$this->table." (
											".implode(",", $columns)."
											) VALUES (
											:".implode(",:", $columns)."
											)");

		}else{
			//UPDATE 
			
		}

		$query->execute($data);

	}

}