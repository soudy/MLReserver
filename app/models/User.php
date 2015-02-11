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
    /**
     * @var int Send user reminders to return items if it's not returned in time
     */
    const SEND_USER_REMINDERS = 0;

    /**
     * @var string The default access a user gets when created
     */
    const DEFAULT_ACCESS_GROUP = 'student';

    const GENERATED_PASSWORD_LENGTH = 6;

    /**
     * Return an object of all users in the database
     *
     * @return object|null
     */
    public function get_all_users()
    {
        $sql = 'SELECT * FROM users';

        $query = $this->db->query($sql);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Return an object of all existing access groups
     *
     * @return object|null
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
     * @return bool
     */
    public function log_in($username, $password)
    {
        if (!($username && $password)) {
            throw new Exception('Please enter all fields.');
            return false;
        }

        $sql   = 'SELECT password, id FROM users WHERE username = :username';
        $query = $this->db->prepare($sql);

        $query->execute(array(':username' => $username));
        $query = $query->fetch();

        $hashed_password = $query['password'];
        $uid             = $query['id'];

        if (password_verify($password, $hashed_password)) {
            $this->set_user_session($uid);
            return true;
        } else {
            throw new Exception('Username and password don\'t match.');
            return false;
        }
    }

    /**
     * Log the user out
     *
     * @return void
     */
    public function logout()
    {
        session_destroy();

        setcookie('uid',     '', time() - 3600, '/');
        setcookie('session', '', time() - 3600, '/');

        header('Location: ' . URL);
    }

    /**
     * If the cookies session and uid are set, check the database entry of uid
     * for the value of session. If the value of session in the database is the
     * same as the value of session in the cookie, the user may be logged in.
     *
     * @return mixed|null
     */
    public function check_user_session()
    {
        if (!($_COOKIE['uid'] && $_COOKIE['session'])) {
            throw new Exception('You\'re not logged in.');
            return false;
        }

        $sql = 'SELECT session FROM users WHERE id=:id';

        $query = $this->db->prepare($sql);

        $query->execute(array(':id' => $_COOKIE['uid']));

        return isset($query->fetch()['session']);
    }

    /**
     * Add a user
     *
     * @param string $full_name
     * @param string $email
     * @param string $access_group Decide what the user can or can not do, for
     * example reserve items, create items, create users etc.
     *
     * @return bool|null
     */
    public function add_user($full_name, $email, $access_group = self::DEFAULT_ACCESS_GROUP)
    {
        if (!($full_name && $email)) {
            throw new Exception('Missing fields.');
            return false;
        }

        /*
         * Username and password will get generated. The username will exist of a
         * first name + last name combination and password will be a randomly
         * generated string. The length of the password is decided by the
         * constant GENERATED_PASSWORD_LENGTH.
         */
        $username     = $this->generate_username($full_name);
        $raw_password = $this->generate_password();
        $password     = password_hash($raw_password, PASSWORD_BCRYPT);

        if ($this->check_existance('users', 'username', $username)) {
            throw new Exception('User already exists in database.');
            return false;
        }

        // XXX: Temporary way of showing username/password combination. will be
        // changed into a mail later
        echo $username . '<br />';
        echo $raw_password;


        $sql = 'INSERT INTO users (id, username, password, email, full_name,
                                   access_group, send_reminders, session)
                           VALUES (NULL, :username, :password, :email, :full_name,
                                   :access_group, :send_reminders, NULL)';

        $query = $this->db->prepare($sql);

        $params = array(
            ':username'       => $username,
            ':password'       => $password,
            ':email'          => $email,
            ':full_name'      => $full_name,
            ':access_group'   => $access_group,
            ':send_reminders' => self::SEND_USER_REMINDERS
        );

        $query->execute($params);

        return true;
    }

    /**
     * Remove a user.
     *
     * @param int $uid
     *
     * @return bool|null
     */
    public function remove_user($uid)
    {
        if (!$uid) {
            throw new Exception('Missing arguments.');
            return false;
        }

        $sql   = 'DELETE FROM users WHERE id=:id';
        $query = $this->db->prepare($sql);

        $query->execute(array(':id' => $uid));
    }

    public function edit_settings($uid, $email = null, $full_name = null,
                                  $send_reminders = null, $current_password = null,
                                  $new_password = null, $confirm_password = null)
    {
        if (!$uid) {
            throw new Exception('Missing arguments.');
            return false;
        }

        $password = $this->change_password($uid, $current_password, $new_password, $confirm_password);

        $sql = 'UPDATE users SET email=:email, full_name=:email,
                                 send_reminders=:set_reminders, password=:password';
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
     * @return mixed|null
     */
    public function edit_user($uid, $username = null, $email = null,
                              $full_name = null, $access_group = null)
    {
        if (!$uid) {
            throw new Exception('Missing arguments.');
            return false;
        }

        $sql = 'UPDATE users SET username=:username, email=:email,
                                 full_name=:full_name, access_group=:access_group
                             WHERE id=:uid';

        $query = $this->db->prepare($sql);
        $params = array(
            ':username'     => $username,
            ':email'        => $email,
            ':full_name'    => $full_name,
            ':access_group' => $access_group,
            ':uid'          => $uid
        );

        return $query->execute($params);
    }

    private function change_password($uid, $current_password, $new_password, $confirm_password)
    {
        if (!($uid && $current_password && $new_password && $confirm_password)) {
            throw new Exception('');
            return false;
        }

        $user = $this->get_user($uid);
    }

    /**
     *
     * See if a row => value combination already exists.
     *
     * @param string $table
     * @param string $row
     * @param mixed $value
     *
     * @return mixed|null
     */
    private function check_existance($table, $row, $value)
    {
        if (!($table || $row || $value)) {
            throw new Exception('Missing arguments.');
            return false;
        }

        $sql   = "SELECT `$row` FROM `$table` WHERE `$row` = :value";
        $query = $this->db->prepare($sql);

        $query->execute(array(':value' => $value));
        return $query->fetch();
    }

    /**
     * Setting a user login session a.k.a actually logging the user in.
     *
     * @param int $uid
     *
     * @return bool|null
     */
    private function set_user_session($uid)
    {
        if (!$uid) {
            throw new Exception('Missing arguments.');
            return false;
        }

        $_SESSION['logged_in'] = $uid;

        // Generate hash to store in database / cookie and verify upon entering
        // the site
        $salt = base64_encode(openssl_random_pseudo_bytes(16));
        $hash = md5($salt . $uid . $salt);

        setcookie('session' , $hash , time() + 3600 * 24 * 365 , '/');
        setcookie('uid'     , $uid  , time() + 3600 * 24 * 365 , '/');

        // Insert hash into database to check later
        $sql = 'UPDATE users SET session=:hash WHERE id=:id';

        $query = $this->db->prepare($sql);

        $params = array(
            ':hash' => $hash,
            ':id'   => $uid
        );

        return $query->execute($params);
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
        if (!$full_name) {
            throw new Exception('Missing arguments.');
            return false;
        }

        $_username = split(' ', strtolower($full_name));

        for ($i = 0; $i < sizeof($_username); $i++) {
            if ($i === sizeof($_username) - 1) {
                $username .= $_username[$i];
                break;
            }
            $username .= $_username[$i][0];
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
