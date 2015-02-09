<?php

class Item extends Model
{
    public function get_all_users_item($uid)
    {
        $sql = 'SELECT * FROM reserved_items WHERE user_id=:uid';

        $query = $this->db->prepare($sql);
        $query->execute(array(':uid' => $uid));

        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function get_all_items()
    {
        $sql = 'SELECT * FROM items';

        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function reserve_item($user_id, $item_id, $count)
    {
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

    public function get_item($id)
    {
        $sql = 'SELECT * FROM items WHERE id=:id';

        $query = $this->db->prepare($sql);
        $query->execute(array(':id' => $id));

        return $query->fetch(PDO::FETCH_OBJ);
    }

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

    public function edit_item($id, $name = null, $description = null, 
                              $image = null, $count = null)
    {
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

    public function remove_item($id)
    {
        $sql   = 'DELETE FROM items WHERE id=:id';
        $query = $this->db->prepare($sql);

        return $query->execute(array(':id' => $id));
    }

    private function is_available($id)
    {
        $sql = 'SELECT available_count FROM items WHERE id=:id';
        $query = $this->db->prepare($sql);

        return $query->execute(array(':id' => $id));
    }
}
