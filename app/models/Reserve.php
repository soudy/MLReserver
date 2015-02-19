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
    // TODO: http://php.net/manual/en/function.strptime.php
    private $school_hours = array(
        '1' => array('8:30',  '9:30'),
        '2' => array('9:30',  '10:30'),
        '3' => array('10:45', '11:45'),
        '4' => array('11:45', '12:45'),
        '5' => array('13:15', '14:15'),
        '6' => array('14:15', '15:15'),
        '7' => array('15:30', '16:30'),
        '8' => array('16:30', '17:30')
    );

    public function get_all_requests()
    {
        $sql = 'SELECT * FROM requests';

        $query = $this->db->query($sql);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get all reservations, or get all reservations of a specific user
     *
     * @param int $uid
     *
     * @return object|bool
     */
    public function get_all_reservations($uid = null)
    {
        if (isset($uid)) {
            $sql = 'SELECT * FROM reservations WHERE user_id=:uid';

            $query = $this->db->prepare($sql);
            $query->execute(array('uid' => $uid));

            return $query->fetchAll(PDO::FETCH_OBJ);
        }

        $sql = 'SELECT * FROM reservations';

        $query = $this->db->query($sql);

        return $query->fetchAll(PDO::FETCH_OBJ);
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

        if ($count > $this->get_item($item_id)->available_count)
              throw new Exception('You tried to reserve more items than there are available.');

        if (!preg_match('/\b\d{1,2}\-\d{1,2}-\d{4}\b/', $date_from) ||
            !preg_match('/\b\d{1,2}\-\d{1,2}-\d{4}\b/', $date_to))
              throw new Exception('Invalid date format.');

        if (!preg_match('/^[1-8]\-[1-8]/', $hours))
              throw new Exception('Invalid hours format.');

        $dates = $this->date_range($date_from, $date_to);

        $date_from = '%d-%d-%d %s';
        $date_to   = '%d-%d-%d %s';

        if (sizeof($dates) > 14)
            throw new Exception('The maximum amount of days you can reserve is 14 days.
                                 Please shorten your reservation.');

        if (sizeof($dates) > 1)
            $hours = null;

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

        $query->execute($params);
    }

    /**
     * Get all dates inbetween 2 given dates
     *
     * @param string $from
     * @param string $to
     *
     * @return array
     */
    private function date_range($from, $to)
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
     * After reserving an item, update the availability count of that item
     *
     * @param string $item
     * @param string $count
     *
     * @return array
     */
    private function update_availability($item, $count)
    {
    }
}
