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

class Item extends Model
{

    /**
     * Add an item.
     *
     * @param string $name
     * @param string $description
     * @param string $count
     * @param string $image
     *
     * @return bool|null
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
     * @return bool|null
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
     * @return bool|null
     */
    public function remove_item($id)
    {
        if (!$id)
            return false;

        $sql   = 'DELETE FROM items WHERE id=:id';
        $query = $this->db->prepare($sql);

        return $query->execute(array(':id' => $id));
    }

}
