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

class Item extends Model
{

    /**
     * Returns an object containing all the items reserved or queued by uid.
     *
     * @param int $uid
     *
     * @return mixed
     */
    public function get_all_users_item($uid)
    {
        $sql = 'SELECT * FROM reserved_items WHERE user_id=:uid';

        $query = $this->db->prepare($sql);
        $query->execute(array(':uid' => $uid));

        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Returns all existing items in the database.
     *
     * @return mixed
     */
    public function get_all_items()
    {
        $sql = 'SELECT * FROM items';

        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Reserve an item. This will be inserted into the queue first and create a
     * cron job to move it to the reserved_item table.
     * TODO: finish this method
     *
     * @return mixed
     */
    public function reserve_item($user_id, $item_id, $count)
    {
        // TODO: get a way of storing date including hours f.e: 09-02-2015 13:39
        if (!$this->is_available($item_id))
            return false;

        $reserved_at = date();
        echo $reserved_at;

        $sql = 'INSERT INTO reserved_items (id, item_id, user_id, reserved_at,
                                            reserved_from, reserved_until, returned,
                                            returned_at, count)
                                    VALUES (NULL, :item_id, :user_id, :reserved_at,
                                            :reserved_from, :reserved_until,
                                            NULL, NULL, :count)';
    }

    /**
     * Add an item.
     *
     * @param string $name
     * @param string $description
     * @param string $count
     * @param string $image
     *
     * @return bool
     */
    public function add_item($name, $description, $count, $image = '')
    {
        $sql = 'INSERT INTO items (id, name, description, image, count, available_count)
                           VALUES (NULL, :name, :description, :image, :count, :available_count)';

        $query = $this->db->prepare($sql);
        $params = array(
            ':name'            => $name,
            ':description'     => $description,
            ':image'           => $image,
            ':count'           => $count,
            ':available_count' => $count
        );

        return $query->execute($params);
    }

    /**
     * Edit an item.
     *
     * @param int $id
     * @param string $name
     * @param string $description
     * @param string $image
     * @param int $count
     *
     * @return bool
     */
    public function edit_item($id, $name = null, $description = null,
                              $image = null, $count = null)
    {
        if (!$id)
            return false;

        $sql = 'UPDATE items SET name=:name, description=:description,
                                 image=:image, count=:count
                             WHERE id=:id';

        $query = $this->db->prepare($sql);
        $params = array(
            ':name'        => $name,
            ':description' => $description,
            ':image'       => $image,
            ':count'       => $count,
            ':id'          => $id
        );

        return $query->execute($params);
    }

    /**
     * Remove an item.
     *
     * @param int $id
     *
     * @return bool
     */
    public function remove_item($id)
    {
        if (!$id)
            return false;

        $sql   = 'DELETE FROM items WHERE id=:id';
        $query = $this->db->prepare($sql);

        return $query->execute(array(':id' => $id));
    }

    /**
     * Check if an item is available
     *
     * @param int $id
     *
     * @return int
     */
    private function is_available($id)
    {
        if (!$id)
            return false;

        $sql = 'SELECT available_count FROM items WHERE id=:id';
        $query = $this->db->prepare($sql);

        $query->execute(array(':id' => $id));
        return $query->fetch(PDO::FETCH_OBJ)->available_count;
    }
}
