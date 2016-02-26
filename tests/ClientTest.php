<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Client.php";
    require_once "src/Stylist.php";

    $server = 'mysql:host=localhost;dbname=hair_salon_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class ClientTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Client::deleteAll();
            Stylist::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = "Jade";
            $id = 2;
            $stylist_id = 8;
            $test_client = new Client($name, $id, $stylist_id);

            //Act
            $result = $test_client->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function test_getId()
        {
            //Arrange
            $name = "Ellie";
            $id = null;
            $test_stylist = new Stylist($name, $id);
            $test_stylist->save();

            $client_name = "Lady Gaga";
            $stylist_id = $test_stylist->getId();

            $test_client = new Client($client_name, $id, $stylist_id);
            $test_client->save();

            //Act
            $result = $test_client->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_getAll()
        {
            //Arrange
            $name = "Maury";
            $id = 1;
            $stylist_id = 1;

            $test_client = new Client($name, $id, $stylist_id);
            $test_client->save();

            $name2 = "Ollie";
            $id2 = 2;
            $stylist_id2 = 1;

            $test_client2 = new Client($name2, $id2, $stylist_id2);
            $test_client2->save();

            //Act
            $result = Client::getAll();

            //Assert
            $this->assertEquals([$test_client, $test_client2], $result);
        }

        function test_find()
        {
            //Arrange
            $name = "Bear Tooth";
            $id = 1;
            $stylist_id = 1;

            $test_client = new Client($name, $id, $stylist_id);

            $test_client->save();

            $name2 = "Organic Oasis";
            $id = 2;
            $stylist_id = 1;

            $test_client2 = new Client($name2, $id, $stylist_id);

            $test_client2->save();

            //Act
            $result = Client::find($test_client2->getId());

            //Assert
            $this->assertEquals($test_client2, $result);
        }

        function test_update()
		{
			//Arrange
			$stylist_name = "Gabby";
			$test_stylist = new Stylist($stylist_name);
			$test_stylist->save();
			$name = "Kate";
			$stylist_id =  $test_stylist->getId();
            $id = 1;
			$test_client = new Client($name, $id, $stylist_id);
			$test_client->save();
			$new_name = "Katherine";
			//Act
			$test_client->update($new_name);
			//Assert
			$this->assertEquals('Katherine', $test_client->getName());
		}
    }

 ?>
