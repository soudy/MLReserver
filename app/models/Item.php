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
     *
     * @return bool|null
     */
    public function add_item($name, $description, $count)
    {
        if (!($name && $description && $count))
            throw new Exception('Missing fields.');

        if (!intval($count))
            throw new Exception('No valid count number specified.');

        $sql = 'INSERT INTO items (id, name, description, count, available_count)
                           VALUES (NULL, :name, :description, :count, :available_count)';

        $query = $this->db->prepare($sql);
        $params = array(
            ':name'            => $name,
            ':description'     => $description,
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
     * @param int $count
     *
     * @return bool|null
     */
    public function edit_item($id, $name = null, $description = null, $count = null)
    {
        if (!$id)
            throw new Exception('No item specified.');

        if (!intval($count))
            throw new Exception('No valid count number specified.');

        if ($this->update_available_count($id, $count) === false)
            throw new Exception('Can\'t update item count because the amount
                                 of currently reserved items is larger than the count
                                 you defined.');
        else
            $available_count = $this->update_available_count($id, $count);

        $sql = 'UPDATE items SET name=:name, description=:description, count=:count,
                                 available_count=:available_count
                             WHERE id=:id';

        $query = $this->db->prepare($sql);
        $params = array(
            ':name'            => $name,
            ':description'     => $description,
            ':count'           => $count,
            ':available_count' => $available_count,
            ':id'              => $id
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
            throw new Exception('No item specified.');

        $sql   = 'DELETE FROM items WHERE id=:id';

        $query = $this->db->prepare($sql);

        return $query->execute(array(':id' => $id));
    }

    /**
     * Update the available_count based on the new count entered. If the new
     * count entered is lager than the amount of reservations of this item,
     * return false.
     *
     * @param int $id
     * @param int $new_count
     *
     * @return int|bool
     */
    private function update_available_count($id, $new_count)
    {
        $item              = $this->get_item($id);
        $step              = $new_count - $item->count;
        $unavailable_count = $item->count - $item->available_count;
        $available_count   = $item->available_count + $step;

        if ($new_count - $unavailable_count >= 0)
            return $new_count - $unavailable_count;
        else
            return false;

        return $available_count;
    }
}
