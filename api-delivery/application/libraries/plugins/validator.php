<?php


class Validator{
    private $ci;
    
    private $INT_DATA_TYPES = [
        "TINYINT",
        "SMALLINT",
        "MEDIUMINT",
        "INT",
        "BIGINT",
        "DECIMAL",
        "BIT",
        "BOOLEAN",
    ];
    
    private $FLOAT_DATA_TYPES = [
        "FLOAT",
        "DOUBLE",
        "REAL"
    ];
    
    private $DATE_DATA_TYPES = [
        "DATE",
        "DATETIME",
        "TIMESTAMP",
        "TIME",
        "YEAR",
    ];
    
    private $STRING_DATA_TYPES = [
        "CHAR",
        "VARCHAR",
        "TINYTEXT",
        "TEXT",
        "MEDIUMTEXT",
        "LONGTEXT",
    ];

    public function __construct(){
        $this->ci =& get_instance();
    }

    public function createSchema($tableName){
        $query = $this->ci->db->query("SHOW COLUMNS FROM {$tableName}")->result();
        $schema = [];

        foreach($query as $column){
            $type = explode('(',$column->Type);
            $schema[$column->Field] = (object) [
                'field' => $column->Field,
                'type' => explode('(',$column->Type)[0],
                'max' => rtrim(end($type),')'),
                'nullable' => $column->Null == 'NO' ? false : true,
                'key' => $column->Key,
                'default' => $column->Default,
                'options' => []
            ] ;
        }

        return $schema;
    }

    public function validate($model, $data, $exclude = [], $onlyCheckWhatIsSet = false){

        $this->ci->load->model($model);
        $schema = $this->ci->$model->getSchema();
        foreach($schema as $column=>$rules){
            $alias = $rules->field;

            if(in_array($column,$exclude)) continue;
            
            if(!isset($data[$alias]) && $onlyCheckWhatIsSet) continue;
            else if(!isset($data[$alias]) && $rules->default != '') continue;  
            else if(!isset($data[$alias]) && $rules->nullable === false) throw new Exception($column.' is not set!', 403);
            else if(!isset($data[$alias])) continue;

            if(in_array(strtoupper($rules->type), $this->INT_DATA_TYPES)){
                if(!is_numeric($data[$alias]) || intval($data[$alias]) != $data[$alias]) 
                    throw new Exception($column.' is not an int type!', 403);
            }else if(in_array(strtoupper($rules->type), $this->FLOAT_DATA_TYPES)){
                if(!is_numeric($data[$alias]) || floatval($data[$alias]) != $data[$alias])
                    throw new Exception($column.' is not a float type!', 403);
            }else if(in_array(strtoupper($rules->type), $this->DATE_DATA_TYPES)){
                if(!$this->is_valid_date_type($data[$alias], $rules->type == 'timestamp' || $rules->type == 'year'))
                    throw new Exception($column.' is not a date type!', 403);
            }else {
                if(!in_array(strtoupper($rules->type), $this->STRING_DATA_TYPES))
                throw new Exception($column.' is type can\'t validate!', 403);
            }

            $this->checkOptionRules($data[$alias],$rules);
        }

    }


    public function sanitize($data,$exclude = []){
        $datavars = [];
        foreach($data as $i=>$value){
            $datavars[$i] = $value;
            if(in_array($i,$exclude)) continue;
            $datavars[$i] = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        }

        return $datavars;
    }



    private function is_valid_date_type($value, $probablyNumeric = false) {
        // Regular expressions for each type
        $date_regex = '/^\d{4}-\d{2}-\d{2}$/'; // YYYY-MM-DD
        $datetime_regex = '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/'; // YYYY-MM-DD HH:MM:SS
        $timestamp_regex = '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/'; // YYYY-MM-DD HH:MM:SS
        $time_regex = '/^\d{2}:\d{2}:\d{2}$/'; // HH:MM:SS
        $year_regex = '/^\d{4}$/'; // YYYY
    
        // Check if the value matches any of the regex patterns
        if (preg_match($date_regex, $value))
            return true; // Matched "DATE"
        elseif (preg_match($datetime_regex, $value))
            return true; // Matched "DATETIME"
        elseif (preg_match($timestamp_regex, $value))
            return true; // Matched "TIMESTAMP"
        elseif (preg_match($time_regex, $value))
            return true; // Matched "TIME"
        elseif (preg_match($year_regex, $value))
            return true; // Matched "YEAR"
        

        if($probablyNumeric && is_numeric($value) && intval($value) == $value)
            return true;

        return false;
    }

    private function checkOptionRules($value,$rules){
        $type = 'string';

        if(in_array(strtoupper($rules->type), $this->INT_DATA_TYPES)) $type = 'int';
        else if(in_array(strtoupper($rules->type), $this->FLOAT_DATA_TYPES)) $type = 'float';
        else if(in_array(strtoupper($rules->type), $this->DATE_DATA_TYPES)) $type = 'date';
        
        foreach($rules->options as $rule=>$target){

            if(empty($value) && $value !== '0' && $value !== 0) continue;

            switch($rule){
                case 'min':
                    if( in_array($type, ['int', 'float']) ) {
                        if( floatval($value) < $target) throw new Exception($rules->field." is less than $target!", 403);
                    }elseif($type == 'date'){
                        $date = strtotime(strpos($value, ' ') !== false ? $value : $value.' 00:00:00');
                        if( $date < strtotime($target)) throw new Exception($rules->field." is earlier than $target!", 403);
                    }elseif($type == 'string'){
                        if( strlen($value) < $target) throw new Exception($rules->field." is less than $target characters!", 403);
                    }
                break;

                case 'max':
                    if( in_array($type, ['int', 'float']) ) {
                        if( floatval($value) > $target) throw new Exception($rules->field." is less than $target!", 403);
                    }elseif($type == 'date'){
                        $date = strtotime(strpos($value, ' ') !== false ? $value : $value.' 00:00:00');
                        if( $date > strtotime($target)) throw new Exception($rules->field." is earlier than $target!", 403);
                    }elseif($type == 'string'){
                        if( strlen($value) > $target) throw new Exception($rules->field." is less than $target characters!", 403);
                    }
                break;

                case 'in':
                    $joined = join(', ', $target);

                    if( $type == 'int' ) {
                        if( !in_array(intval($value),$target) ) throw new Exception($rules->field." is not in ($joined)!", 403);
                    }if( $type == 'float' ) {
                        if( !in_array(floatval($value),$target) ) throw new Exception($rules->field." is not in ($joined)!", 403);
                    }elseif($type == 'string'){
                        if( !in_array($value,$target) ) throw new Exception($rules->field." is not in ($joined)!", 403);
                    }
                break;

                case 'equalTo':
                    if($type == 'date'){
                        $date = strtotime(strpos($value, ' ') !== false ? $value : $value.' 00:00:00');
                        if( $date != strtotime($target) ) throw new Exception($rules->field." is not equal to $target!", 403);
                    }else{
                        if( $value != $target ) throw new Exception($rules->field." is not equal to $target!", 403);
                    }
                break;

                case 'regex':
                    if( !preg_match($target, $value) ) throw new Exception($rules->field." does not fulfill this regex: $target!", 403);
                break;
            }

        }



    }
}