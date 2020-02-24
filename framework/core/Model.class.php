<?php 

    class Model{
        protected $db;
        protected $table;
        protected $fields = array();

        public function __construct($table){
            $dbconfig['host'] = $GLOBALS['config']['host'];
            $dbconfig['user'] = $GLOBALS['config']['hostuser'];
            $dbconfig['password'] = $GLOBALS['config']['password'];
            $dbconfig['dbname'] = $GLOBALS['config']['dbname'];
            $dbconfig['port'] = $GLOBALS['config']['port'];
            $dbconfig['charset'] = $GLOBALS['config']['charset'];

            $this->db = new Mysql($dbconfig);
            $this->table = $GLOBALS['config']['prefix'].$table;
            $this->getFields();
        }

        private function getFields(){
            $sql = "DES ". $this->table;
            $result = $this->db->getAll($sql);
            foreach ($result as $val) {
                $this->fields[] = $val['Field'];
                if($val['Key'] == 'PRI'){
                    $pk = $val['Field'];
                }
            }
            if(isset($pk)){
                $this->fields['pk'] = $pk;
            }
        }

        public function insert($list){
            $field_list = '';
            $value_list = '';

            foreach($list as $key => $val){
                if(in_array($key, $this->fields)){
                    $field_list .= "`".$key."`" . ',';
                    $value_list .= "`".$val."`" . ',';
                }
            }

            $field_list = rtrim($field_list,',');
            $value_list = rtrim($value_list,',');

        $sql = "INSERT INTO `{$this->table}` ({$field_list}) VALUES ($value_list)";
        if($this->db->query($sql)) {
            return $this->db->getInsertId();
        }
        else {
            return false;
        }
        }

        public function update($list){
            $uplist = '';
            $where = 0;   
            foreach ($list as $key => $val) {
    
                if (in_array($key, $this->fields)) {
    
                    if ($key == $this->fields['pk']) {
                        $where = "`$key`=$val";
                    } else {
                        $uplist .= "`$key`='$val'".",";
                    }
    
                }
    
            }
            $uplist = rtrim($uplist,',');
            $sql = "UPDATE `{$this->table}` SET {$uplist} WHERE {$where}";
            if ($this->db->query($sql)) {
                if ($rows = mysql_affected_rows()) {
                    return $rows;   
                } else {
                    return false;  
                }      
            } 
            else {
                return false;  
            }
        }

        public function delete($pk){
            $where = 0;
            if(is_array($pk)){
                $where = "`{$this->fields['pk']}` in (".implode(',', $pk).")";
            }
            else {
                $where = "`{$this->fields['pk']}`=$pk";
            }

            $sql = "DELETE FROM `{$this->table}` WHERE $where";

            if ($this->db->query($sql)) {
                if ($rows = mysqli_affected_rows()) // returns the num. of affected in a prev. MySQL operation
                { 
                    return $rows;
                } 
                else {
                    return false;
                }        
            } 
            else {
            return false; 
            }
        }

        public function selectByPK($pk){
            $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->fields['pk']}`=$pk";
            return $this->db->getRow($sql);
        }

        public function total(){
            $sql = "SELECT count(*) FROM {$this->table}";
            return $this->db->getOne($sql);
        }

        public function pageRows($offset, $limit, $where=''){
            if(empty($where)){
                $sql = "SELECT * FROM {$this->table} limit $offset, $limit";
            }
            else{
            $sql = "SELECT * FROM {$this->table} WHERE $where limit $offset, $limit";
            }
            return $this->db->gatAll($sql);
        }
    }

?>