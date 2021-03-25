<?php
class connectPDO {

  protected $dbh;
  protected $user   = 'root';
  protected $host   = 'localhost';
  protected $pass   = 'root';
  protected $dbname = 'lasalle';
  protected $tables = ['salle', 'client'];
  protected $msgTable =  "un problème est survenue lors de la création des tables";

  protected static $error   = '';

  public function __construct(){

    date_default_timezone_set('Europe/paris');
    setlocale(LC_TIME, "fr_FR");

    $this->connex();

  }

  /**
  * Connexion
  **/
  protected function connex(){
    try {
       
        $this->setDBH();

    } catch (PDOException $e) {

      $dbh = new PDO(
                    'mysql:host='.$this->host,
                    $this->user,
                    $this->pass
                  );

      /*
      creation de la base de données et des tables
      */

      $createDataBase = $this->createDataBase( $dbh );

      if( $createDataBase!==false ){

        $this->dbh = null;
        $this->setDBH();

        $createTable = $this->createTable( $this->dbh );
        
        if( $createTable==false ){

          $this->setError( $this->msgTable );
          return false;

        }else{
          return; 
        }
      }

      $msg =  $e->getMessage();
      $this->setError( $msg );

    }

  }

  /**
  * Ajouter erreur
  **/
  private function setDBH(){
    $this->dbh = new PDO(
                    'mysql:host='.$this->host.
                    ';dbname='.$this->dbname.
                    ';charset=UTF8',
                    $this->user,
                    $this->pass,
                    array( PDO::ATTR_PERSISTENT => true)
                  );
  }

  /**
  * Ajouter erreur
  **/
  public function setError($msg){
    self::$error = $msg;
  }

  /**
  * Récupérer erreurs
  **/
  public function getError(){
    return self::$error;
  }

  /**
  * Fonction de test d'existence de la table dans la base de données 
  **/
  protected function testTable(PDO $pdo, $table) {

      // Try a select statement against the table
      // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
      try {
          $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
      } catch (PDOException $e) {
          // We got an exception == table not found
          return FALSE;
      }

      // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
      return $result !== FALSE;
  }


  /**
  * création de la base de données
  **/
  protected function createDataBase( PDO $pdo ) {
    
    try {

      $rows = $pdo->exec("CREATE DATABASE IF NOT EXISTS $this->dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        CREATE USER '$this->user'@'$this->host' IDENTIFIED BY '$this->pass';
        GRANT ALL ON `$this->dbname`.* TO '$this->user'@'$this->host';
        FLUSH PRIVILEGES;
        USE $this->dbname;");
      return $rows;
        
    }catch (PDOException $e) {

      $msg =  $e->getMessage();
      $this->setError( $msg );
      
      return false;
    }
  }

  /**
  * création des tables dans la base de données 
  **/
  protected function createTable( PDO $pdo ) {

    try {
      $rows = $pdo->exec("
        CREATE TABLE IF NOT EXISTS `client` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(255) NOT NULL,
          `tel` VARCHAR(20) NOT NULL,
          `mail` VARCHAR(100) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        CREATE TABLE IF NOT EXISTS `salle` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(255) NOT NULL,
          `address` TEXT NOT NULL,
          `postcode` int(5) NOT NULL,
          `town` VARCHAR(255) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        INSERT INTO `salle` (`name`,`address`, `postcode`, `town`) VALUES
          ('Salle de la muerte','18, place du village des Schtroumpfs', 75000, 'Paris'),
          ('Salle de la grisaille','56, avenue du mauvais temps', 69000, 'Lyon'),
          ('Salle de la plage','33, rue de l\'Apéro', 13000, 'Marseille');

        CREATE TABLE IF NOT EXISTS `res_salle` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `salle_id` int(11) NOT NULL,
          `client_id` int(11) NOT NULL,
          `date_res` DATE NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
      ");
      return $rows;

    }catch (PDOException $e) {

      $msg =  $e->getMessage();
      $this->setError( $msg );
      
    }

  }

}

$connectPDO = new connectPDO;
?>