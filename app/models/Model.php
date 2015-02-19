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
    protected $db;

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
        if (!isset($_SESSION['logged_in']) || !isset($uid))
            return false;

        $sql = 'SELECT * FROM users WHERE id=:id';

        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $uid));

        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Return an object of the user's permissions, or false if user id isn't set.
     *
     * @param int $uid
     *
     * @return object|bool
     */
    public function get_user_permissions($uid)
    {
        $access_group = $this->get_user($uid)->access_group;
        $sql          = 'SELECT * FROM access_groups WHERE name=:name';

        $query = $this->db->prepare($sql);
        $query->execute(array(':name' => $access_group));

        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Returns an object containing all info of item by id, or null if it can't
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
     * Returns all existing items in the database. If uid is defined, return all
     * items reserved by uid.
     *
     * @param int $uid
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
     * Check if an item is available.
     *
     * @param int $id
     *
     * @return object|bool
     */
    protected function is_available($id)
    {
        $sql = 'SELECT available_count FROM items WHERE id=:id';

        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));

        return $query->fetch(PDO::FETCH_OBJ)->available_count;
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

        $sql   = "SELECT `$row` FROM `$table` WHERE `$row` = :value";
        $query = $this->db->prepare($sql);

        $query->execute(array(':value' => $value));
        return $query->fetch();
    }

}
