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
        '0' => array('8:30',  '17:30'),
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
     * TODO: finish this method
     *
     * @return mixed|null
     */
    public function reserve_item($user_id, $item_id, $count)
    {
    }

    public function convert_from_school_time($hour)
    {
        return $this->school_hours[$hour];
    }

}
