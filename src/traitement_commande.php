<?php
session_start();
$file = '../data/commandes.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    $data = json_decode(file_get_contents($file), true);

    foreach ($data as &$order) {
        if ($order['id'] === $order_id) {
            $order['status'] = $new_status;
            // Si on attribue un livreur
            if (isset($_POST['livreur_id'])) {
                $order['livreur'] = $_POST['livreur_id'];
            }
        }
    }

    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    
    // On redirige selon qui a cliqué
    if ($_SESSION['role'] === 'restaurateur') header("Location: restaurant.php");
    elseif ($_SESSION['role'] === 'livreur') header("Location: livreur.php");
    else header("Location: accueil.php");
    exit();
}