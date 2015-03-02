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
        $sql = 'SELECT * FROM reservations WHERE id=:rid';

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

        // TODO: availability check

        if (!preg_match('/\b\d{1,2}\-\d{1,2}-\d{4}\b/', $date_from) ||
            !preg_match('/\b\d{1,2}\-\d{1,2}-\d{4}\b/', $date_to))
            throw new Exception('Invalid date format.');

        if (!preg_match('/^[1-8]\-[1-8]/', $hours))
            throw new Exception('Invalid hours format.');

        if (strtotime($date_from) < strtotime(date('d-n-Y')))
            throw new Exception('You can\'t reserve in the past!');

        $dates = $this->date_range($date_from, $date_to);

        if (sizeof($dates) > 14)
            throw new Exception('The maximum amount of days you can reserve is 14 days.
                                 Please shorten your reservation.');

        if (sizeof($dates) > 1)
            $hours = null;

        /* First, insert the reservation into the reservations table. */
        $sql = 'INSERT INTO reservations (id, item_id, user_id, reserved_at, date_from,
                                          date_to, count, hours)
                                  VALUES (null, :item_id, :user_id, :reserved_at,
                                          :date_from, :date_to, :count, :hours)';

        $query = $this->db->prepare($sql);

        $params = array(
            ':item_id'     => $item_id,
            ':user_id'     => $user_id,
            ':reserved_at' => date('d-m-Y G:i:s'),
            ':date_from'   => $date_from,
            ':date_to'     => $date_to,
            ':count'       => $count,
            ':hours'       => $hours
        );

        // TODO
        /* $this->get_available_count($date_from, $date_to, null); */

        $query->execute($params);

        /* Then insert the date(s) into the calender table. */
        $reservation_id = $this->db->lastInsertId('reservations');
        $reservation    = $this->get_reservation($reservation_id);

        $this->create_calender_dates($reservation, $dates);
    }

    public function remove_reservation($id)
    {
        $sql = 'DELETE FROM reservations WHERE id=:id';

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
            $dates[] = date('d-m-Y', $current);
            $current = strtotime('+1 day', $current);
        }

        return $dates;
    }

    /**
     * Check if user $uid created the reservation $rid
     *
     * @param int $uid
     * @param int $rid
     *
     * @return bool
     */
    protected function owns_reservation($uid, $rid)
    {
        $sql = 'SELECT user_id FROM reservations WHERE id=:rid';

        $query = $this->db->prepare($sql);
        $query->execute(array(':rid' => $rid));

        return $query->fetch()['user_id'] === $uid;
    }

    /**
     * Get the amount of available items in a time period
     *
     * @param string $date_from
     * @param string $date_to
     * @param string $hours
     *
     * @return int
     */
    private function get_available_count($date_from, $date_to, $hours = null)
    {
        $dates           = $this->date_range($date_from, $date_to);
        $formatted_dates = array();

        for ($i = 0; $i < sizeof($dates); ++$i)
            $dates[$i] = $this->db->quote($dates[$i]);

        $formatted_dates = implode(' OR ', $dates);

        // TODO: fuck this ill finish it later
        $sql = "SELECT SUM(reservation_count) as sum
                FROM (
                    SELECT DISTINCT reservation_id, reservation_count, date
                    FROM calender
                ) x
                WHERE date=$formatted_dates";

        $query = $this->db->query($sql);

        var_dump($query->fetch());
        exit(1);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Insert dates into the calender with the fitting reservation id.
     *
     * @param object $reservation
     * @param array|string $dates
     *
     * @return bool
     */
    private function create_calender_dates($reservation, $dates)
    {
        $dates_formatted = array();

        // Fill the array with properly formatted database insert entries.
        foreach ($dates as $date) {
            $date              = sprintf('(%s, "%s", %d, "%s", %d)', 'null', $date,
                                         $reservation->id, $reservation->hours,
                                         $reservation->count);
            $dates_formatted[] = $date;
        }

        // Concatenate the whole array into a string, ready for insertion.
        $dates = implode(',', $dates_formatted);

        $sql = "INSERT INTO calender (id, date, reservation_id, reservation_hours,
                                      reservation_count)
                VALUES $dates";

        return $this->db->query($sql);
    }
}
