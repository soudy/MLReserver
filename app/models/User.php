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

class User extends Model
{
    /* @var int Send user reminders to return items if it's not returned in time */
    const SEND_USER_REMINDERS = 0;

    /* @var string The default access a user gets when created */
    const DEFAULT_ACCESS_GROUP = 'student';

    /* @var int Length of randomly generated password */
    const GENERATED_PASSWORD_LENGTH = 6;

    /**
     * Return an object of all users
     *
     * @return object|bool
     */
    public function get_all_users($order = 'ausername')
    {
        $possible_orders = array('id', 'email', 'username', 'full_name', 'access_group');

        if (!$order)
            $order = 'ausername';

        $direction = $order[0] === 'a' ? 'ASC' : 'DESC';
        $order     = substr($order, 1);

        if (!in_array($order, $possible_orders)) {
            $direction = 'ASC';
            $order     = 'username';
        }

        $sql = "SELECT * FROM users ORDER BY $order $direction";

        $query = $this->db->query($sql);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Return an object of all existing access groups
     *
     * @return object|bool
     */
    public function get_all_access_groups()
    {
        $sql = 'SELECT * FROM access_groups';

        $query = $this->db->query($sql);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Log the user in
     *
     * @param string $username
     * @param string $password
     *
     * @return void
     */
    public function log_in($username, $password)
    {
        if (!$this->check_existance('users', 'username', $username))
            throw new Exception("User $username not found.");

        $sql   = 'SELECT password, id FROM users WHERE username = :username';
        $query = $this->db->prepare($sql);

        $query->execute(array(':username' => $username));
        $query = $query->fetch(PDO::FETCH_OBJ);

        $hashed_password = $query->password;
        $uid             = $query->id;

        if (password_verify($password, $hashed_password))
            $_SESSION['logged_in'] = $uid;
        else
            throw new Exception('Username and password don\'t match.');
    }

    /**
     * Log the user out
     *
     * @return void
     */
    public function log_out()
    {
        session_destroy();

        setcookie('uid'     , '' , time() - 3600 , '/');
        setcookie('session' , '' , time() - 3600 , '/');
    }

    /**
     * Add a user
     *
     * @param string $full_name
     * @param string $email
     * @param string $access_group Decide what the user can or can not do, for
     * example reserve items, create items, create users etc.
     *
     * @return bool
     */
    public function add_user($full_name, $email, $access_group = self::DEFAULT_ACCESS_GROUP)
    {
        if (!($full_name && $email))
            throw new Exception('Missing fields.');

        if (!preg_match('/^[A-Z0-9._%+=]+\@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $email))
            throw new Exception('Invalid e-mail address.');

        /*
         * Username and password will get generated. The username will exist of a
         * first name + last name combination and password will be a randomly
         * generated base64 encoded string. The length of the password is decided by
         * the constant GENERATED_PASSWORD_LENGTH.
         */
        $username     = $this->generate_username($full_name);
        $raw_password = $this->generate_password();
        $password     = password_hash($raw_password, PASSWORD_BCRYPT);

        if ($this->check_existance('users', 'username', $username))
            throw new Exception('User already exists in database.');

        // XXX: Temporary way of showing username/password combination. will be
        // changed into a mail later
        echo htmlspecialchars($username) . '<br />';
        echo $raw_password;

        $sql = 'INSERT INTO users (id, username, password, email, full_name,
                                   access_group, send_reminders)
                           VALUES (NULL, :username, :password, :email, :full_name,
                                   :access_group, :send_reminders)';

        $query = $this->db->prepare($sql);

        $params = array(
            ':username'       => $username,
            ':password'       => $password,
            ':email'          => $email,
            ':full_name'      => $full_name,
            ':access_group'   => $access_group,
            ':send_reminders' => self::SEND_USER_REMINDERS
        );

        return $query->execute($params);
    }

    /**
     * Remove a user.
     *
     * @param int $uid
     *
     * @return bool
     */
    public function remove_user($uid)
    {
        if (!$this->get_user($uid))
            throw new Exception('User not found.');

        $sql   = 'DELETE FROM users WHERE id = :id';
        $query = $this->db->prepare($sql);

        return $query->execute(array(':id' => $uid));
    }

    /**
     *
     * Remove the current user.
     *
     * @param int $uid
     * @param string $password
     * @param string $confirm_password
     *
     * @return bool
     */
    public function remove_account($uid, $password, $confirm_password)
    {
        if (!$this->check_existance('users', 'id', $uid))
            throw new Exception('User not found.');

        if (!($uid && $password && $confirm_password))
            throw new Exception('Missing fields.');

        if (!password_verify($password         , $this->get_user($uid)->password) ||
            !password_verify($confirm_password , $this->get_user($uid)->password))
            throw new Exception('Incorrect password(s).');

        $sql   = 'DELETE FROM users WHERE id=:id';
        $query = $this->db->prepare($sql);

        return $query->execute(array(':id' => $uid));
    }

    /**
     * Edit an user
     *
     * @param int $uid
     * @param string $username
     * @param string $email
     * @param string $full_name
     * @param string $access_group
     *
     * @return bool
     */
    public function edit_user($uid, $username = '', $email = '',
                              $full_name = '', $access_group = '')
    {
        if (!$this->get_user($uid))
            throw new Exception('User not found.');

        if (!preg_match('/^[A-Z0-9._%+=]+\@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $email))
            throw new Exception('Invalid e-mail address.');

        $sql = 'UPDATE users SET username = :username, email = :email,
                                 full_name = :full_name, access_group = :access_group
                             WHERE id = :uid';

        $query = $this->db->prepare($sql);
        $params = array(
            ':username'       => $username,
            ':email'          => $email,
            ':full_name'      => $full_name,
            ':access_group'   => $access_group,
            ':uid'            => $uid
        );

        return $query->execute($params);
    }

    /**
     * Edit user settings
     *
     * @param int $uid
     * @param string $email
     * @param string $full_name
     * @param string $password
     * @param int $send_reminders
     *
     * @return bool
     */
    public function edit_settings($uid, $email = '', $username = '',
                                  $password = '', $send_reminders = '')
    {
        if (!$this->get_user($uid))
            throw new Exception('User not found.');

        if ($this->check_existance('users', 'username', $username))
            throw new Exception('Username already taken.');

        if (!preg_match('/^[A-Z0-9._%+=]+\@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $email))
            throw new Exception('Invalid email.');

        $sql = 'UPDATE users SET email = :email, username = :username,
                                 password = :password, send_reminders = :send_reminders
                             WHERE id=:uid';

        $query = $this->db->prepare($sql);
        $params = array(
            ':email'          => $email,
            ':username'       => $username,
            ':password'       => $password,
            ':send_reminders' => $send_reminders,
            ':uid'            => $uid
        );

        return $query->execute($params);
    }

    /**
     * Return a hashed new password chosen by the user if everything matches.
     *
     * @param int $uid
     * @param string $current_password
     * @param string $new_password
     * @param string $confirm_new_password
     *
     * @return string
     */
    public function new_password($uid, $current_password, $new_password, $confirm_new_password)
    {
        if (!$this->get_user($uid))
            throw new Exception('User not found.');

        $user = $this->get_user($uid);

        if (!password_verify($current_password, $user->password))
            throw new Exception('Current password doesn\'t match.');

        if ($new_password !== $confirm_new_password)
            throw new Exception('New passwords don\'t match.');

        return password_hash($new_password, PASSWORD_BCRYPT);
    }

    public function import_from_magister($filename)
    {
        // TODO
        throw new Exception('Not yet implemented');
    }

    /**
     * Generate a username based on fullname, eg Dennis Ritchie => dritchie
     *
     * @param string $full_name
     *
     * @return string
     */
    private function generate_username($full_name)
    {
        $username_parts = explode(' ', strtolower($full_name));
        $username       = '';

        for ($i = 0; $i < sizeof($username_parts); $i++) {
            if ($i === sizeof($username_parts) - 1) {
                $username .= $username_parts[$i];
                break;
            }

            $username .= $username_parts[$i][0];
        }

        return $username;
    }

    /**
     * Generate a (temporary) password. The user is advised to change this
     * password.
     *
     * @param int $len
     *
     * @return string
     */
    private function generate_password($len = self::GENERATED_PASSWORD_LENGTH)
    {
        return substr(base64_encode(openssl_random_pseudo_bytes(128)), 0, $len);
    }
}
