<?php
/**
 * MySQL DB. All data is stored in test database
 * Create an empty MySQL database and set the dbname, username
 * and password below
 *
 * This class will create the table with sample data
 * automatically on first `get` or `get($id)` request
 */
use Luracast\Restler\RestException;
class DB_PDO_MySQL
{
    private $db;
    function __construct()
    {
        try {
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
            $this->db = new PDO(
                'mysql:host=localhost;dbname=test',
                'demo',
                'demo',
                $options
            );
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new RestException(501, 'MySQL: ' . $e->getMessage());
        }
    }
    function get($id, $installTableOnFailure = FALSE)
    {
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $sql = $this->db->prepare('SELECT * FROM todos WHERE id = :id');
            $sql->execute(array(':id' => $id));
            return $this->id2int($sql->fetch());
        } catch (PDOException $e) {
            if (!$installTableOnFailure && $e->getCode() == '42S02') {
                $this->install();
                return $this->get($id, TRUE);
            }
            throw new RestException(501, 'MySQL: ' . $e->getMessage());
        }
    }
    function getAll($installTableOnFailure = FALSE)
    {
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $this->db->query('SELECT * FROM todos');
            return $this->id2int($stmt->fetchAll());
        } catch (PDOException $e) {
            if (!$installTableOnFailure && $e->getCode() == '42S02') {
                $this->install();
                return $this->getAll(TRUE);
            }
            throw new RestException(501, 'MySQL: ' . $e->getMessage());
        }
    }
    function insert($rec)
    {
        $sql = $this->db->prepare("INSERT INTO todos (description, completed) VALUES (:description, :completed)");
        if (!$sql->execute(array(':description' => $rec['description'], ':completed' => $rec['completed'])))
            return FALSE;
        return $this->get($this->db->lastInsertId());
    }
    function update($id, $rec)
    {
        $sql = $this->db->prepare("UPDATE todos SET description = :description, completed = :completed WHERE id = :id");
        if (!$sql->execute(array(':id' => $id, ':description' => $rec['description'], ':completed' => $rec['completed'])))
            return FALSE;
        return $this->get($id);
    }
    function delete($id)
    {
        $r = $this->get($id);
        if (!$r || !$this->db->prepare('DELETE FROM todos WHERE id = ?')->execute(array($id)))
            return FALSE;
        return $r;
    }
    private function id2int($r)
    {
        if (is_array($r)) {
            if (isset($r['id'])) {
                $r['id'] = intval($r['id']);
				$r['completed'] = (bool) $r['completed'];
            } else {
                foreach ($r as &$r0) {
                    $r0['id'] = intval($r0['id']);
					$r0['completed'] = (bool) $r0['completed'];
                }
            }
        }
		
        return $r;
    }
    private function install()
    {
        $this->db->exec(
            "CREATE TABLE todos (
                id INT AUTO_INCREMENT PRIMARY KEY ,
                description TEXT NOT NULL ,
                completed TINYINT(1) NOT NULL
            ) DEFAULT CHARSET=utf8;"
        );
        $this->db->exec(
            "INSERT INTO todos (description, completed) VALUES ('Check out 9gag', 1);
             INSERT INTO todos (description, completed) VALUES ('Hang the laundry', 1);"
        );
    }
}

?>