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

class Request extends Reserve
{
    /* @var Maximum length of a message when requesting an item. */
    const MESSAGE_SIZE = 255;

    /**
     * Get a requests
     *
     * @return object|bool
     */
    public function get_request($id)
    {
        $sql = 'SELECT * FROM requests WHERE id=:id';

        $query = $this->db->prepare($sql);
        $query->execute(array('id' => $id));

        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Request an item
     *
     * @param int $user_id
     * @param int $item_id
     * @param int $count
     * @param string $date_from
     * @param string $date_to
     * @param string $hours
     * @param string $message
     *
     * @return bool
     */
    public function request_item($user_id, $item_id, $count, $date_from, $date_to,
                                 $hours, $message)
    {
        if (empty($user_id) || empty($item_id) || empty($count) || empty($date_from)
            || empty($date_to) || empty($message))
            throw new Exception('Missing fields.');

        if (sizeof($message) > self::MESSAGE_SIZE)
            throw new Exception('Message too long. Maximum size: ' . self::MESSAGE_SIZE);

        if (!preg_match('/\b\d{1,2}\-\d{1,2}-\d{4}\b/', $date_from) ||
            !preg_match('/\b\d{1,2}\-\d{1,2}-\d{4}\b/', $date_to))
            throw new Exception('Invalid date format.');

        if (!preg_match('/^[1-8]\-[2-8]/', $hours))
            throw new Exception('Invalid hours format.');

        if ($count > $this->get_item($item_id)->count)
            throw new Exception('You can\'t reserve more items than there are available.');

        if (strtotime($date_from) < strtotime(date('d-n-Y')))
            throw new Exception('You can\'t request in the past yet.');

        $dates = $this->date_range($date_from, $date_to);

        if (sizeof($dates) > 14)
            throw new Exception('The maximum amount of days you can request is 14 days.
                                 Please shorten the duration your request.');

        if (sizeof($dates) > 1)
            $hours = null;

        $sql = 'INSERT INTO requests (id, item_id, user_id, requested_at, date_from,
                                      date_to, count, hours, message, status)
                              VALUES (null, :item_id, :user_id, :requested_at,
                                      :date_from, :date_to, :count, :hours, :message, :status)';

        $query = $this->db->prepare($sql);

        $params = array(
            ':item_id'      => $item_id,
            ':user_id'      => $user_id,
            ':requested_at' => date('d-m-Y G:i:s'),
            ':date_from'    => $date_from,
            ':date_to'      => $date_to,
            ':count'        => $count,
            ':hours'        => $hours,
            ':message'      => $message,
            ':status'       => self::REQUEST_STATUS_CODES[0]
        );

        $query->execute($params);
    }

    /**
     * Remove request
     *
     * @param int $id
     *
     * @return bool
     */
    public function remove_request($id)
    {
        $sql = 'DELETE FROM requests WHERE id=:id';

        $query = $this->db->prepare($sql);
        return $query->execute(array(':id' => $id));
    }

    /**
     * Change the status of a request (pending, approved, denied)
     *
     * @param int $rid
     * @param int $status
     *
     * @return bool
     */
    public function update_request_status($rid, $status)
    {
        $sql = 'UPDATE requests SET status=:status WHERE id=:rid';

        $query = $this->db->prepare($sql);
        $params = array(
            ':status' => $status,
            ':rid'    => $rid
        );

        return $query->execute($params);
    }

    /**
     * Check if user $uid created the request $rid
     *
     * @param int $uid
     * @param int $rid
     *
     * @return bool
     */
    public function owns_request($uid, $rid)
    {
        $sql = 'SELECT user_id FROM requests WHERE id=:rid';

        $query = $this->db->prepare($sql);
        $query->execute(array(':rid' => $rid));

        return $query->fetch()['user_id'] === $uid;
    }
}
