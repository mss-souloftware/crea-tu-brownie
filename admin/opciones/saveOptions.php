<?php
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
 */

function saveConditionales()
{
    $salida = ['salida' => saveFirstOption()];
    echo json_encode($salida);
    exit;
}

function saveFirstOption()
{
    $savedItemsInOptions = array();
    $upcomingElements = [
        "precLetra" => "conditionalSubmit_precLetras",
        "precCoraz" => "conditionalSubmit_precCorazon",
        "precEnvio" => "conditionalSubmit_precEnvio",
        "expressShiping" => "expressShipinglSubmit_Page",
        "maxCaracteres" => "conditionalSubmit_maximoC",
        "gastoMinimo" => "conditionalSubmit_Gminimo",
        "pluginPage" => "conditionalSubmit_Page",
        "termCond" => "termCondlSubmit_Page",
        "saturdayShiping" => "saturdayShipinglSubmit_Page",
    ];

    foreach ($upcomingElements as $key => $value) {
        if (isset($_POST[$value])) {
            !saveOptionOrUpdate($key, $_POST[$value]) ? null : $savedItemsInOptions["Sucess_" . $key] = $key;
        }
    }
    return $savedItemsInOptions;
}

function saveOptionOrUpdate($nombre, $option)
{
    $confirm;
    if (!get_option($nombre)) {
        $confirm = add_option($nombre, $option);
    } elseif (get_option($nombre)) {
        $confirm = update_option($nombre, $option);
    } else {
        $confirm = false;
    }

    return $confirm;
}