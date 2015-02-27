<?php
/*
 * MLReserver is a reservation system primarily made for making sharing items
 * easy and clear between a large group of people.
 * Copyright (C) 2015 soud
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

class Model
{
    const REQUEST_STATUS_CODES = array(
        0 => 'pending',
        1 => 'approved',
        2 => 'denied'
    );

    protected $db;
    private $permissions;

    /*
     * Not using a singleton for database
     * @see http://stackoverflow.com/a/4596323
     */
    public function __construct()
    {
        try {
            $this->db = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME .
                                ';charset=' . DB_CHARSET, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            die('Failed to open PDO database connection: ' . $e->getMessage());
        }

        if (isset($_SESSION['logged_in']))
            $this->permissions = $this->get_user_permissions($_SESSION['logged_in']);
    }

    /**
     * Returns user permissions table
     *
     * @return object|bool
     */
    public function get_permission($permission)
    {
        return $this->permissions->$permission;
    }

    /**
     * Returns an object containing all user information
     *
     * @param int $uid
     *
     * @return object|bool
     */
    public function get_user($uid)
    {
        $sql = 'SELECT * FROM users WHERE id=:id';

        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $uid));

        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Returns an object containing all info of the item by id, or null if it can't
     * be found.
     *
     * @param int $id
     *
     * @return object|bool
     */
    public function get_item($id)
    {
        $sql = 'SELECT * FROM items WHERE id=:id';

        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));

        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get request status name by code
     *
     * @return string
     */
    public function get_status_code($status_code)
    {
        return self::REQUEST_STATUS_CODES[$status_code];
    }


    /**
     * Returns an object containing all reservations by a user or false if the
     * user has no reservations.
     *
     * @param int $uid
     *
     * @return object|bool
     */
    public function get_reservations($uid)
    {
        $sql = 'SELECT * FROM reservations WHERE user_id=:uid';

        $query = $this->db->prepare($sql);
        $query->execute(array(':uid' => $uid));

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Returns an object containing all requests by a user or false if the
     * user has no requests.
     *
     * @param int $uid
     *
     * @return object|bool
     */
    public function get_requests($uid)
    {
        $sql = 'SELECT * FROM requests WHERE user_id=:uid';

        $query = $this->db->prepare($sql);
        $query->execute(array(':uid' => $uid));

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Returns all existing items in the database
     *
     * @return object|bool
     */
    public function get_all_items()
    {
        $sql = 'SELECT * FROM items';

        $query = $this->db->query($sql);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get all requests
     *
     * @return object|bool
     */
    public function get_all_requests($status_code = null)
    {
        if ($status_code) {
            $sql = 'SELECT * FROM requests WHERE status=:status';

            $query = $this->db->prepare($sql);
            $query->execute(array(':status' => $status_code));

            return $query->fetchAll(PDO::FETCH_OBJ);
        }

        $sql = 'SELECT * FROM requests';

        $query = $this->db->query($sql);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get all reservations
     *
     * @return object|bool
     */
    public function get_all_reservations()
    {
        $sql = 'SELECT * FROM reservations';

        $query = $this->db->query($sql);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * See if a row => value combination exists in given table.
     *
     * @param string $table
     * @param string $row
     * @param mixed $value
     *
     * @return bool
     */
    protected function check_existance($table, $row, $value)
    {
        if (!($table || $row || $value))
            throw new Exception('Missing argument(s).');

        $sql   = "SELECT `$row` FROM `$table` WHERE `$row`=:value";
        $query = $this->db->prepare($sql);

        $query->execute(array(':value' => $value));
        return $query->fetch();
    }

    /**
     * Return an object of the user's permissions, or false if user id isn't set.
     *
     * @param int $uid
     *
     * @return object|bool
     */
    private function get_user_permissions($uid)
    {
        $access_group = $this->get_user($uid)->access_group;
        $sql          = 'SELECT * FROM access_groups WHERE name=:name';

        $query = $this->db->prepare($sql);
        $query->execute(array(':name' => $access_group));

        return $query->fetch(PDO::FETCH_OBJ);
    }
}
