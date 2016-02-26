<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Stylist.php";

    $server = 'mysql:host=localhost;dbname=hair_salon_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StylistTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Stylist::deleteAll();
            Client::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = "Lizzy";
            $test_stylist = new Stylist($name);

            //Act
            $result = $test_stylist->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function test_getId()
        {
            //Arrange
            $name = "Monica";
            $id = 3;
            $test_stylist = new Stylist($name, $id);

            //Act
            $result = $test_stylist->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            //Arrange
            $name = "Sophie";
            $test_stylist = new Stylist($name);
            $test_stylist->save();

            //Act
            $result = Stylist::getAll();

            //Assert
            $this->assertEquals($test_stylist, $result[0]);
        }

        function test_getAll()
        {
            $name = "Sam";
            $test_stylist = new Stylist($name);
            $test_stylist->save();

            $name2 = "Margo";
            $test_stylist2 = new Stylist($name2);
            $test_stylist2->save();

            //Act
            $result = Stylist::getAll();

            //Assert
            $this->assertEquals([$test_stylist, $test_stylist2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $name = "Felicity";
            $test_stylist = new Stylist($name);

            //Act
            Stylist::deleteAll();
            $result = Stylist::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_update()
        {
            //Arrange
            $name = "Asami";
            $test_stylist = new Stylist($name);
            $test_stylist->save();

            $new_name = "Korra";

            //Act
            $test_stylist->update($new_name);

            //Assert
            $this->assertEquals("Korra", $test_stylist->getName());
        }

        function test_getClients()
        {
            //Arrange
            $name = "Bob";
            $id = null;
            $test_stylist = new Stylist($name, $id);
            $test_stylist->save();

            $stylist_id = $test_stylist->getId();

            $name = "Nicholas";
            $test_client = new Client($name, $id, $stylist_id);
            $test_client->save();

            $name2 = "Marky Mark";
            $test_client2 = new Client($name2, $id, 3);
            $test_client2->save();

            //Act
            $result = $test_stylist->getClients();

            //Assert
            $this->assertEquals([$test_client], $result);
        }

        function test_find()
        {
            //Arrange
            $name = "Morgan";
            $test_stylist = new Stylist($name);
            $test_stylist->save();

            $name2 = "Franny";
            $test_stylist2 = new Stylist($name2);
            $test_stylist2->save();

            //Act
            $result = Stylist::find($test_stylist2->getId());

            //Assert
            $this->assertEquals($test_stylist2, $result);

        }
    }
 ?>
