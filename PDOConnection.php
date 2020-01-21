<?php
class PDOConnection {

    private $dbh;
    function __construct() {
        try {

            $server         = "UCRGRJO-08";
            $db_username    = "custom_apa";
            $db_password    = "custom_apa";
            $service_name   = "p01";
            $sid            = "";
            $port           = 1521;
            $dbtns          = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = $server)(PORT = $port)) (CONNECT_DATA = (SERVICE_NAME = $service_name) (SID = $sid)))";

            //$this->dbh = new PDO("mysql:host=".$server.";dbname=".dbname, $db_username, $db_password);
            $this->dbh = new PDO("oci:dbname=" . $dbtns . ";charset=utf8", $db_username, $db_password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function select($sql) {
		//echo $sql;
        $sql_stmt = $this->dbh->prepare($sql);
        $sql_stmt->execute();
        $result = $sql_stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
	 public function passarParametro($sentencaCriacao,$siglaSocioResponsavel,$siglaUsuario) {
		      try {
	$sql_stmt = $this->dbh->prepare($sentencaCriacao);
	    $sql_stmt->bindParam(':siglaSocioResponsavel',$siglaSocioResponsavel, PDO::PARAM_STR);
    $sql_stmt->bindParam(':siglaUsuario',$siglaUsuario, PDO::PARAM_STR);
	$sucesso=$sql_stmt->execute();
		  }
       catch (PDOException $erro){
           echo "Não foi possivel inserir os dados no banco: ".$erro->getMessage();
       }
    }//passarParamento
	
    public function insert($sql) {
        $sql_stmt = $this->dbh->prepare($sql);
        try {
            $result = $sql_stmt->execute();
        } catch (PDOException $e) {
            trigger_error('Error occured while trying to insert into the DB:' . $e->getMessage(), E_USER_ERROR);
        }
        if ($result) {
            return $sql_stmt->rowCount();
        }
    }

    function __destruct() {
        $this->dbh = NULL;
    }

}
?>