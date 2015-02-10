<?php
/*
 * MLReserver is a reservation system useful for sharing items in an honest way.
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
            die("Failed to open PDO database connection: $e");
        }
    }

    /**
     * Returns an object containing all user information
     *
     * @param int $uid
     *
     * @return mixed
     */
    public function get_user($uid)
    {
        if (!($_SESSION['logged_in'] || $uid))
            return false;

        $sql = 'SELECT * FROM users WHERE id=:id';

        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $uid));

        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Return an object of the user's permissions
     *
     * @return object
     */
    public function get_user_permissions($uid)
    {
        if (!$uid)
            return false;

        $access_group = $this->get_user($uid)->access_group;
        $sql          = 'SELECT * FROM access_groups WHERE name = :name';

        $query = $this->db->prepare($sql);
        $query->execute(array(':name' => $access_group));

        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Returns an object containing all info of item by id.
     *
     * @return mixed
     */
    public function get_item($id)
    {
        $sql = 'SELECT * FROM items WHERE id=:id';

        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));

        return $query->fetch(PDO::FETCH_OBJ);
    }
}
