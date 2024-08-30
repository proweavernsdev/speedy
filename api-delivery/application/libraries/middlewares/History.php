<?php
class History
{
    private $ci;
    private $table;
    private $tablePrefix;
    public function __construct()
    {
        $this->ci =& get_instance();
        $this->table = $_ENV['DB_PREFIX'] . 'history';
        $this->tablePrefix = 'hstry_';
    }

    // get all history, user: admin
    public function history_get_all()
    {
        $res = $this->ci->db->get($this->table)->row();
        return $res;
    }

    // view the history based on primary key, user: admin
    public function history_get_pk($pk)
    {
        // $res = $this->ci->db->get_where($this->table.'.'.$column, array('id'=> $id))->row();
        $res = $this->ci->db->get_where($this->table, array('hstry_primaryKey' => $pk));
        if ($res->num_rows() > 0) {
            $rows = $res->row();

            return $rows;
        } else {
            $response = array("success" => false, "message" => "This query has no match");
            return $response;
        }
    }

    // create history for record of registration, user: admin
    public function inserted($data, $table, $description)
    {
        // Get the primary key column name and value
        $primaryKeyColumnName = key($data);
        $primaryKeyValue = current($data);

        foreach ($data as $columnName => $value) {
            // Skip inserting the primary key column
            if ($columnName === $primaryKeyColumnName) {
                continue;
            }

            $insertData = array(
                $this->tablePrefix . 'affectedTable' => $table,
                $this->tablePrefix . 'primaryKey' => $primaryKeyValue,
                $this->tablePrefix . 'columnName' => $columnName,
                $this->tablePrefix . 'newValue' => $value,
                $this->tablePrefix . 'date' => date('Y-m-d H:i:s'),
                $this->tablePrefix . 'operation' => 'Add',
                $this->tablePrefix . 'initiatedBy' => $primaryKeyValue,
                $this->tablePrefix . 'description' => $description
            );
            $this->ci->db->insert($this->table, $insertData);
        }
        $response = array("success" => true, "message" => "History has been made");
        return $response;

    }

    // create history for record of registration, user: company
    public function insertedByUser($data, $table, $description, $initiator)
    {
        // Get the primary key column name and value
        $primaryKeyColumnName = key($data);
        $primaryKeyValue = current($data);

        foreach ($data as $columnName => $value) {
            // Skip inserting the primary key column
            if ($columnName === $primaryKeyColumnName) {
                continue;
            }

            $insertData = array(
                $this->tablePrefix . 'affectedTable' => $table,
                $this->tablePrefix . 'primaryKey' => $primaryKeyValue,
                $this->tablePrefix . 'columnName' => $columnName,
                $this->tablePrefix . 'newValue' => $value,
                $this->tablePrefix . 'date' => date('Y-m-d H:i:s'),
                $this->tablePrefix . 'operation' => 'Add',
                $this->tablePrefix . 'initiatedBy' => $initiator,
                $this->tablePrefix . 'description' => $description
            );
            $this->ci->db->insert($this->table, $insertData);
        }
        $response = array("success" => true, "message" => "History has been made");
        return $response;

    }

    // anything that updates, user: all
    public function updated($postUpdt, $preUpdt, $table, $description, $initiator)
    {
        // Get the primary key column name and value
        $primaryKeyColumnName = key($postUpdt);
        $primaryKeyValue = current($postUpdt);

        // check each column inside the arrays $postUpdt
        foreach ($postUpdt as $columnName => $value1) {
            // skip the columnID
            if ($columnName === $primaryKeyColumnName) {
                continue;
            }
            // Check if the key exists in the second array
            if (array_key_exists($columnName, $preUpdt)) {
                $value2 = $preUpdt[$columnName];
                // compare the values of $postUpdt($value1) on $preUpdt($value2)
                if ($value1 == $value2) {
                    // if the value is equal, skip to the next column
                    continue;
                } else {
                    // if $postUpdt value is different, update the table
                    $insertData = array(
                        $this->tablePrefix . 'affectedTable' => $table,
                        $this->tablePrefix . 'primaryKey' => $primaryKeyValue,
                        $this->tablePrefix . 'columnName' => $columnName,
                        $this->tablePrefix . 'oldValue' => $value2,
                        $this->tablePrefix . 'newValue' => $value1,
                        $this->tablePrefix . 'date' => date('Y-m-d H:i:s'),
                        $this->tablePrefix . 'operation' => 'Update',
                        $this->tablePrefix . 'initiatedBy' => $initiator,
                        $this->tablePrefix . 'description' => $description
                    );
                    $this->ci->db->insert($this->table, $insertData);
                }
            } else {
                continue;
            }
        }
        $response = array("success" => true, "message" => "History has been made");
        return $response;
    }

    // anything that deletes, user: all
    public function deleted($data, $table, $description, $initiator)
    {
        // Get the primary key column name and value
        $primaryKeyColumnName = key($data);
        $primaryKeyValue = current($data);

        foreach ($data as $columnName => $value) {
            // Skip inserting the primary key column
            if ($columnName === $primaryKeyColumnName) {
                continue;
            }

            $insertData = array(
                $this->tablePrefix . 'affectedTable' => $table,
                $this->tablePrefix . 'primaryKey' => $primaryKeyValue,
                $this->tablePrefix . 'columnName' => $columnName,
                $this->tablePrefix . 'oldValue' => $value,
                $this->tablePrefix . 'date' => date('Y-m-d H:i:s'),
                $this->tablePrefix . 'operation' => 'Delete',
                $this->tablePrefix . 'initiatedBy' => $initiator,
                $this->tablePrefix . 'description' => $description
            );
            $this->ci->db->insert($this->table, $insertData);
        }
        $response = array("success" => true, "message" => "History has been made");
        return $response;

    }
}