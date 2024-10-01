<?php
    /*
        Legacy Database class of Vidlii.
        This class is DEPRECATED in favor of DB class, which can work with multiple DBs as well as PDO, only better.
    */

    namespace Vidlii\Vidlii;

    class Database {
        protected $Connection;
        public $RowNum;

        function __construct(bool $Show_Errors = false) {
            print_r($_ENV);

            try {
                $this->Connection = new \PDO($_ENV["database"]);
                // $this->Connection->setAttribute(PDO::NULL_TO_STRING);
                if($Show_Errors||1) { $this->Connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); }
                $this->execute("SET SESSION sql_mode = 'TRADITIONAL'");
                return true;
            } catch(\PDOException $e) {
            	die($e);
                header($_SERVER['SERVER_PROTOCOL'] . ' Database Unavailable', true, 503);
                die("<center>Database Error</center>");
            }
        }

        public function execute(string $SQL, bool $Single = false, array $Execute = []): array {
			try {
            	$Query = $this->Connection->prepare($SQL);
            	$Query->execute($Execute);
            } catch (\Exception $e) {die($e);}

            $this->RowNum = $Query->rowCount();

            if ($this->RowNum == 0) {
                return [];
            } elseif ($Single) {
                return @$Query->fetch(\PDO::FETCH_ASSOC);
            } else {
                return @$Query->fetchAll(\PDO::FETCH_ASSOC);
            }
        }

        public function modify(string $SQL, array $Execute = []): bool {
            
            if ($Execute) {
                
                foreach ($Execute as $Key => $Value) {
                    
                    $Execute[$Key] = str_ireplace("eval", "evaI", $Value);
                    if (is_null($Value)) {
                    	$Execute[$Key] = 0;
                    }
                }
                
            }
            try {
            	$Query = $this->Connection->prepare($SQL);
            	$Query->execute($Execute);
            } catch (\Exception $e) {die($e);}

            $this->RowNum = $Query->rowCount();

            if ($this->RowNum > 0) {
                return true;
            }
            return false;
        }

        public function last_id() {
            return $this->Connection->lastInsertId();
        }
    }
