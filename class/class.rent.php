<?php
class rent extends connectPDO{

  public function __construct(){
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
  * affichage de la liste de salles à louer
  **/
  public function listOptionsRoom(){
    $html='';
    $optionsRoom = $this->getRoomsToRent();
    
    foreach ($optionsRoom as $room => $roomInfos) {
      $html.='<option value="'.$roomInfos['id'].'" data-icon="img/salle-'.$roomInfos['id'].'.jpg" class="left">'.$roomInfos['town'].'</option>';
    }

    return $html;
  }

  /**
  * Insertion d'un client dans la base
  **/
  public function insertClient( $data ){

    $test_mail = $this->testClientMail($data['mail']);
    if( !empty($test_mail) ){
      return $test_mail['id'];
    }

    $sql = 'INSERT INTO client
            ( name, tel, mail )
            VALUES ( :name, :tel, :mail )
            ';
    $stmt = $this->dbh->prepare($sql);
    $name = $data['name'];
    $tel = $data['tel'];
    $mail = $data['mail'];
    $stmt->bindparam(':name', $name, PDO::PARAM_STR);
    $stmt->bindparam(':tel', $tel, PDO::PARAM_STR);
    $stmt->bindparam(':mail', $mail, PDO::PARAM_STR);
    $stmt->execute();
    return $this->dbh->lastInsertId();
  }

  /**
  * test existence d'un client dans la base
  **/
  public function testClientMail( $mail ){
    $sql = 'SELECT id FROM client WHERE mail = :mail';
    $stmt = $this->dbh->prepare($sql);
    $stmt->bindparam(':mail', $mail, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result;
  }


  /**
  * Insertion d'une réservation
  **/
  public function insertRentRoom( $salle_id, $client_id, $date_res ){
    $sql = 'INSERT INTO res_salle
            ( salle_id, client_id, date_res )
            VALUES ( :salle_id, :client_id, :date_res  )
            ';
    $stmt = $this->dbh->prepare($sql);
    $stmt->bindparam(':salle_id', $salle_id, PDO::PARAM_INT);
    $stmt->bindparam(':client_id', $client_id, PDO::PARAM_INT);
    $stmt->bindparam(':date_res', $date_res, PDO::PARAM_STR);
    $stmt->execute();
    return $this->dbh->lastInsertId();
  }

  /**
  * Liste des réservations
  **/
  public function listRentRoom(){
    $sql = 'SELECT res.id, res.date_res, res.salle_id,
            salle.name as salle_name,salle.address,salle.postcode,salle.town,
            client.name as client_name,client.mail,client.tel
            FROM res_salle AS res
            RIGHT JOIN salle
            ON salle.id=res.salle_id
            RIGHT JOIN client
            ON client.id=res.client_id
            ';
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchall(PDO::FETCH_ASSOC);
    return $result;
  }

  /**
  * Liste des salles
  **/
  private function getRoomsToRent(){
    $sql = 'SELECT id,name,address,postcode,town FROM salle';
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchall(PDO::FETCH_ASSOC);
    return $result;
  }

}

$rent = new rent;

?>