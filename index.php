<?php

require 'flight/Flight.php';

Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=db_mascota','root','1234'));




# Leer los datos.
Flight::route('GET /mascotas', function () {
    $sentencia = Flight::db()->prepare("SELECT * FROM mascota");
    $sentencia->execute();
    $datos = $sentencia->fetchAll();
    Flight::json($datos);
});



# Inserta a manda nuevos datos.
Flight::route('POST /mascotas', function() {
    $nombre = (Flight::request()->data->nombre);
    $tipo_mascota = (Flight::request()->data->tipo_mascota);
    $dni_mascota = (Flight::request()->data->dniMascota);

    $sql = "INSERT INTO mascota (nombre, tipo_mascota, dniMascota) VALUES (?,?,?)";
    
    $sentencia = Flight::db()->prepare($sql);
    $sentencia->bindParam(1, $nombre);
    $sentencia->bindParam(2, $tipo_mascota);
    $sentencia->bindParam(3, $dni_mascota);
    $sentencia->execute();

    Flight::jsonp(["Mascota agregada"]);
});


# Eliminar datos a travez de su id.
Flight::route('DELETE /mascotas', function() {

    $id = (Flight::request()->data->id_mascota);

    $sql = "DELETE FROM mascota WHERE id_mascota=?";
    $sentencia = Flight::db()->prepare($sql);
    $sentencia->bindParam(1, $id);
    $sentencia->execute();

    Flight::jsonp(["Mascota eliminada '$id'"]);

});


# Actualizar mascota 
Flight::route('PUT /mascotas', function() {

    $id = (Flight::request()->data->id_mascota);
    $nombre = (Flight::request()->data->nombre);
    $tipo_mascota = (Flight::request()->data->tipo_mascota);
    $dni_mascota = (Flight::request()->data->dniMascota);

    $sql = "UPDATE mascota SET nombre=?, tipo_mascota=?, dniMascota=? WHERE id_mascota=?";
    
    $sentencia = Flight::db()->prepare($sql);

    $sentencia->bindParam(1, $nombre);
    $sentencia->bindParam(2, $tipo_mascota);
    $sentencia->bindParam(3, $dni_mascota);
    $sentencia->bindParam(4, $id);
    
    $sentencia->execute();

    Flight::jsonp(["Mascota actualizada"]);
});



// Llamar un dato -> una mascota:

Flight::route('GET /mascotas/@id', function($id) {
    $sentencia = Flight::db()->prepare("SELECT * FROM mascota WHERE id_mascota=?");
    $sentencia->bindParam(1, $id);
    $sentencia->execute();
    $datos = $sentencia->fetchAll();
    Flight::json($datos);
});

Flight::start();
