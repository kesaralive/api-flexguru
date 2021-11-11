<?php
class Session
{
    private $db;
    private $table = 'session';

    //Session properties
    private $id;
    private $username;
    private $created;
    private $expires;
    private $admin = 0;
    private $tutor = 0;
    private $affiliate = 0;
    private $student = 1;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function create($userid, $iat, $exp)
    {
        $this->username = $this->db->findUserByid($userid)[0]['username'];
        $this->id = $userid;
        $this->created = date('Y-m-d H:i:s', $iat);
        $this->expires = date('Y-m-d H:i:s', $exp);
        switch ($this->db->findUserByid($userid)[0]['role']) {
            case 'tu':
                $this->tutor = 1;
                break;
            case 'af':
                $this->affiliate = 1;
                $this->student = 0;
                break;
            default:
                break;
        }

        //Create query
        $this->db->query("INSERT INTO " . $this->table . "(id,username,created,expires,admin,tutor,affiliate,student) VALUES(:id,:username,:created,:expires,:admin,:tutor,:affiliate,:student)");

        //Bind data
        $this->db->bind(':id', $this->id);
        $this->db->bind(':username', $this->username);
        $this->db->bind(':created', $this->created);
        $this->db->bind(':expires', $this->expires);
        $this->db->bind(':admin', $this->admin);
        $this->db->bind(':tutor', $this->tutor);
        $this->db->bind(':affiliate', $this->affiliate);
        $this->db->bind(':student', $this->student);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function sessionByusername($username)
    {
        //Create query
        $this->db->query("SELECT * FROM " . $this->table . " where username = :username");

        //Bind data
        $this->db->bind(':username', $username);

        $this->db->execute();

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function sessionByuserid($userid)
    {
        //Create query
        $this->db->query("SELECT * FROM " . $this->table . " where id = :userid");

        //Bind data
        $this->db->bind(':userid', $userid);

        $this->db->execute();

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}