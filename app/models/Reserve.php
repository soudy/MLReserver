<?php
/*
 *
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

class Reserve extends Model
{

    /**
     * Returns an object containing all info of the reservation by id, or null if it can't
     * be found.
     *
     * @param int $rid
     *
     * @return object|bool
     */
    public function get_reservation($rid)
    {
        $sql = 'SELECT * FROM reservations WHERE id = :rid';

        $query = $this->db->prepare($sql);
        $query->execute(array(':rid' => $rid));

        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Reserve an item
     *
     * @param int $user_id
     * @param int $item_id
     * @param int $count
     * @param string $date_from
     * @param string $date_to
     * @param string $hours
     *
     * @return bool
     */
    public function reserve_item($user_id, $item_id, $count, $date_from, $date_to,
                                 $hours)
    {
        if (empty($user_id) || empty($item_id) || empty($count) || empty($date_from)
            || empty($date_to))
            throw new Exception('Missing fields.');

        if (!preg_match('/\b\d{4}\-\d{2}-\d{2}\b/', $date_from) ||
            !preg_match('/\b\d{4}\-\d{2}-\d{2}\b/', $date_to))
            throw new Exception('Invalid date format.');

        if (!preg_match('/^[1-8]\-[1-8]/', $hours))
            throw new Exception('Invalid hours format.');

        if (strtotime($date_from) < strtotime(date('Y-m-d')))
            throw new Exception('You can\'t reserve in the past!');

        $dates = $this->date_range($date_from, $date_to);

        if (sizeof($dates) > 14)
            throw new Exception('The maximum amount of days you can reserve is 14 days.
                                 Please shorten your reservation.');

        if (sizeof($dates) > 1)
            $hours = null;

        if ($count > $this->get_available_count($item_id, $date_from, $date_to))
            throw new Exception('You tried to reserve more items then there are
                                 available in this time period.');

        $sql = 'INSERT INTO reservations (id, item_id, user_id, reserved_at, date_from,
                                          date_to, count, hours)
                                  VALUES (null, :item_id, :user_id, :reserved_at,
                                          :date_from, :date_to, :count, :hours)';

        $query = $this->db->prepare($sql);

        $params = array(
            ':item_id'     => $item_id,
            ':user_id'     => $user_id,
            ':reserved_at' => date('Y-m-d G:i:s'),
            ':date_from'   => $date_from,
            ':date_to'     => $date_to,
            ':count'       => $count,
            ':hours'       => $hours
        );

        return $query->execute($params);
    }

    /**
     * Remove reservation.
     *
     * @param int $id
     *
     * @return bool
     */
    public function remove_reservation($id)
    {
        $sql = 'DELETE FROM reservations WHERE id = :id';

        $query = $this->db->prepare($sql);

        return $query->execute(array('id' => $id));
    }

    /**
     * Get all dates inbetween 2 given dates
     *
     * @param string $from
     * @param string $to
     *
     * @return array
     */
    protected function date_range($from, $to)
    {
        $dates    = array();

        $current  = strtotime($from);
        $to       = strtotime($to);

        while ($current <= $to) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        return $dates;
    }

    /**
     * Check if user $uid created the reservation $rid.
     *
     * @param int $uid
     * @param int $rid
     *
     * @return bool
     */
    protected function owns_reservation($uid, $rid)
    {
        $sql = 'SELECT user_id FROM reservations WHERE id = :rid';


        $query = $this->db->prepare($sql);
        $query->execute(array(':rid' => $rid));

        return $query->fetch()['user_id'] === $uid;
    }

    /**
     * Check if an item has been reserved.
     *
     * @param int $item_id
     *
     * @return int
     */
    protected function has_reservations($item_id)
    {
        $sql = 'SELECT * FROM reservations WHERE item_id = :item_id';

        $query = $this->db->prepare($sql);
        $query->execute(array('item_id' => $item_id));

        return $query->rowCount();
    }

    /**
     * Get the amount of available items in a time period.
     * TODO: implement hours support
     *
     * @param int $item_id
     * @param string $date_from
     * @param string $date_to
     * @param string $hours
     *
     * @return int
     */
    protected function get_available_count($item_id, $date_from, $date_to, $hours = null)
    {
        if (!$this->has_reservations($item_id))
            return $this->get_item($item_id)->count;

        $dates           = $this->date_range($date_from, $date_to);
        $formatted_dates = array();

        // Add quotes to dates for query to work.
        for ($i = 0; $i < sizeof($dates); ++$i)
            $dates[$i] = $this->db->quote($dates[$i]);

        $formatted_dates = implode(' OR ', $dates);

        $sql = "SELECT (items.count - sum(reservations.count)) AS available_count
                FROM reservations
                INNER JOIN items
                    ON items.id = reservations.item_id
                WHERE ($formatted_dates BETWEEN $date_from AND $date_to)
                    AND item_id = $item_id;";

        $query = $this->db->query($sql);

        return $query->fetch()['available_count'];
    }
}

