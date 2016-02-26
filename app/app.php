<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Client.php";
    require_once __DIR__."/../src/Stylist.php";

    $app = new Silex\Application();

    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=hair_salon';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path'=>__DIR__."/../views"
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function() use ($app) {
        return $app['twig']->render('homepage.html.twig', array('stylists' => Stylist::getAll()));
    });

    $app->post("/stylists", function() use ($app) {
        $new_stylist = new Stylist($_POST['name']);
        $new_stylist->save();
        return $app['twig']->render('homepage.html.twig', array('stylists' => Stylist::getAll()));
    });

    $app->get("/stylists/{id}", function($id) use ($app) {
        $stylist = Stylist::find($id);
        return $app['twig']->render("stylist.html.twig", array('stylist' => $stylist, 'clients' => $stylist->getClients()));
    });

    $app->get("/stylist/{id}/edit", function($id) use ($app) {
        $stylist = Stylist::find($id);
        return $app['twig']->render('stylist_edit.html.twig', array('stylist' => $stylist));
    });

    $app->patch("/stylist/{id}", function($id) use ($app) {
        $new_name = $_POST['new_name'];
        $stylist = Stylist::find($id);
        $stylist->update($new_name);
        return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist, 'clients' => $stylist->getClients()));
    });

    $app->delete("/stylist/{id}/delete", function($id) use ($app) {
        $stylist = Stylist::find($id);
        $stylist->delete();
        return $app['twig']->render('homepage.html.twig', array('stylists' => Stylist::getAll()));
    });

    $app->post("/clients", function() use ($app) {
        $name = $_POST['name'];
        $stylist_id = $_POST['stylist_id'];
        $new_client = new Client($name, $id = null, $stylist_id);
        $new_client->save();
        $stylist = Stylist::find($stylist_id);
        return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist, 'clients' => $stylist->getClients()));
    });

    $app->get("/client/{id}/edit", function($id) use ($app) {
        $client = Client::find($id);
        return $app['twig']->render('client_edit.html.twig', array('client' => $client));
    });

    $app->patch("/client/{id}", function($id) use ($app) {
        $new_client = $_POST['new_client_name'];
        $client = Client::find($id);
        $client->update($new_client);
        $stylist_id = $client->getStylistId();
        $stylist = Stylist::find($stylist_id);
        return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist, 'clients' => $stylist->getClients()));
    });

    $app->delete("/client/{id}/delete", function($id) use ($app) {
        $client = Client::find($id);
        $stylist_id = $client->getStylistId();
        $stylist = Stylist::find($stylist_id);
        $client->delete();
        return $app['twig']->render('stylist.html.twig', array('stylist' => $stylist, 'clients' => $stylist->getClients()));
    });

    return $app;
 ?>
