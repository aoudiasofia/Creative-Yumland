<?php
session_start();

if (isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];
    $orders_file = '../data/commandes.json';

    $orders = [];
    if (file_exists($orders_file)) {
        $orders_json = file_get_contents($orders_file);
        $orders_json = preg_replace('/^<<<<<<< .*?^=======\s*|>>>>>>> .*/ms', '', $orders_json);
        $orders = json_decode($orders_json, true);
    }

    $order_found = false;
    if (is_array($orders)) {
        foreach ($orders as &$order) {
            if (isset($order['id']) && $order['id'] === $order_id) {
                $order['status'] = $new_status;
                $order_found = true;
                break;
            }
        }
    }

    if ($order_found) {
        file_put_contents($orders_file, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

header('Location: livreur.php');
exit();