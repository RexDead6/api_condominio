<?php

require_once dirname( __DIR__ ) . '/util/DBHelper.php';

class BaseModel{

    private $connection;
    private $table;
    public $className;
    private $sql = null;
    private $wheres = "";
    private $inner = "";
    private $order = "";

    public function __construct($table = null, $className = null){

        $this->connection = (new DBHelper())->connect();
        $this->table = $table;
        $this->className = $className;
    }  

    //Método para obtener todos los registros de una tabla
    public function getAll(){
        try {

            $tbl = substr($this->table, 0, 3);
            $this->sql = "SELECT * FROM {$this->table} AS {$tbl} {$this->inner} {$this->wheres} {$this->order}";

            $query = $this->connection->prepare($this->sql);
            $query->execute();

            return $query->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->className);

        } catch (PDOException $error) {

            return $error->getMessage();
        }
    }

    //Método para obtener el primer registro de una consulta
    public function getFirst(){

        $list = $this->getAll();

        if(count($list) > 0){

            return $list[0];

        } else{

            return null;
        }
    }

    //Método para insertar un registro
    public function insert($obj){

        try {

            $keys = implode("`, `",array_keys($obj));
            $values = ":" . implode(", :",array_keys($obj));
            $this->sql = "INSERT INTO $this->table (`$keys`) VALUES($values)";
            //echo $this->sql;
            $this->execute($obj);
            $id = $this->connection->lastInsertId();
            return $id;

        } catch (PDOException $error) {
            echo $error->getMessage();
            return false;
        }
    }

    //Método para realizar un update a un registro
    public function update($obj){

        try {
            
            $keys = "";

            foreach($obj as $key => $value){

                $keys .= "`$key`=:$key,";
            }

            $keys = rtrim($keys,',');
            $this->sql = "UPDATE $this->table SET $keys $this->wheres";

            $affectedRows = $this->execute($obj);

            return $affectedRows > 0;

        } catch (PDOException $error) {
            
            return $error->getMessage();
        }
    }

    //Método para eliminar un registro (hay que modificarlo xd)
    public function delete(){

        try {

            $this->sql = "DELETE FROM $this->table $this->wheres";
            $affectedRows = $this->execute();

            return $affectedRows;
            
        } catch (PDOException $error) {

            return $error->getMessage();
        }
    }

    //Método para obtener registros con un inner
    public function inner($table, $column, $second_table = null){

        if ($second_table == null){
            $second_table = $this->table;
        }

        $tbl = substr($table, 0, 3);
        $self_tbl = substr($second_table, 0, 3);
        $this->inner .= "INNER JOIN {$table} AS {$tbl} ON {$tbl}.{$column} = {$self_tbl}.{$column} ";
        return $this;
    }

    //Método para filtrar con AND
    public function where($key,$condition, $value){

        $this->wheres .= (strpos($this->wheres, "WHERE")) ? " AND " : " WHERE ";
        $this->wheres .= "$key $condition " . ((is_string($value)) ? "\"$value\"" : $value) . " ";
        
        return $this;
    }

    //Método para filtrar con OR
    public function orWhere($key,$condition, $value){

        $this->wheres .= (strpos($this->wheres, "WHERE")) ? " OR " : " WHERE ";
        $this->wheres .= "$key $condition " . ((is_string($value)) ? "\"$value\"" : $value) . " ";

        return $this;
    }

    public function orderBy($colum, $type="DESC"){
        $this->order = "ORDER BY {$colum} {$type}";

        return $this;
    }

    //Método para ejectuar una consulta
    private function execute($obj = null){

        $query = $this->connection->prepare($this->sql);

        if($obj !== null){
            
            foreach($obj as $key => $value){

                if(empty($value) && $value != 0){

                    $value = null;
                }
                $query->bindValue(":$key",$value);
            }
        }

        $query->execute();
        $this->resetValues();

        return $query->rowCount();
    }

    //Reestablecer los valores de los atributos wheres y sql
    private function resetValues(){
        
        $this->wheres = "";
        $this->inner = "";
        $this->order = "";
        $this->sql = null;
    }


	/**
	 * @return mixed
	 */
	public function getConnection() {
		return $this->connection;
	}
}


?>