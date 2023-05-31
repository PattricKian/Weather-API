<?php

require_once "Location.php";
header('Content-Type: application/json; charset=utf-8');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    header("HTTP/1.1 201 CREATED");
    $location = new Location();
    $location->setLocation($data['location']);
    $location->setCountry($data['country']);
    $location->setCapital($data['capital']);
    $location->setLatitude($data['latitude']);
    $location->setLongitude($data['longitude']);
    $location->setLocalTime($data['local_time']);
    $location->save();
    echo json_encode($location->toArray());
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("HTTP/1.1 409 NO");
    $id = $_GET['id'];
    if($id) {
        header("HTTP/1.1 200 OK");
        $location = Location::findById($id);
        echo json_encode($location, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        $locations = Location::findAll();
        $visits = [];
        foreach ($locations as $l) {
            if($visits[$l['country']]) {
                $visits[$l['country']]++;
            } else {
                $visits[$l['country']] = 1;
            }
        }
        header("HTTP/1.1 200 OK");
        echo json_encode([$visits, $locations]);
    }
}
?>


