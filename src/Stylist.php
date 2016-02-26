<?php
    class Stylist
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function setName($name)
        {
            $this->name = $name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO stylists (name) VALUES  ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE stylists SET name = '{$new_name}' WHERE stylist_id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM stylists WHERE stylist_id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM clients WHERE stylist_id = {$this->getId()};");
        }

        static function getAll()
        {
            $returned_stylists = $GLOBALS['DB']->query("SELECT * FROM stylists;");
            $stylists = [];

            foreach ($returned_stylists as $stylist) {
                $name = $stylist['name'];
                $id = $stylist['stylist_id'];
                $new_stylist = new Stylist($name, $id);
                array_push($stylists, $new_stylist);
            }
            return $stylists;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM stylists");
        }

        function getClients()
        {
            $clients = array();
            $returned_clients = $GLOBALS['DB']->query("SELECT * FROM clients WHERE stylist_id = {$this->getId()};");

            foreach($returned_clients as $client)
            {
                $name = $client['name'];
                $id = $client['id'];
                $stylist_id = $client['stylist_id'];
                $new_client =  new Client($name, $id, $stylist_id);
                array_push($clients, $new_client);
            }
            return $clients;
        }

        static function find($search_id)
        {
            $found_stylist = null;
            $stylists = Stylist::getAll();
            foreach ($stylists as $stylist) {
                if ($stylist->getId() == $search_id) {
                    $found_stylist = $stylist;
                }
            }
            return $found_stylist;
        }
    }

 ?>
