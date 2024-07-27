<?php
/**
 * 
 * author: M. Sufyan Shaikh
 * description: process form and send info to cookie
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
 * 
 */
require_once plugin_dir_path(__FILE__) . '../outPutMail/sendEmail.php';

function resultProcess()
{
    $saveStatus = confirmUpcomingDta();
    echo json_encode($saveStatus);
    exit;
}

function confirmUpcomingDta()
{
    if (isset($_POST['upcomingStatus']) && isset($_POST['id']) && isset($_POST['email'])) {
        $status = isset($_POST['upcomingStatus']) ? $_POST['upcomingStatus'] : 0;
        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $upcomingData = array();
        $upcomingData['status'] = $status;
        $upcomingData['id'] = $id;
        $upcomingData['email'] = $_POST['email'];

        return array('dataSucess' => saveStatus($upcomingData), 'mailSend' => sendEmail($upcomingData));
    } else {
        return array('error' => 'Error en el envio de status e id para la ejecución');
    }
}

function saveStatus($dataToSave)
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'chocoletras_plugin';
    $result;
    switch ($dataToSave['status']) {
        case 'proceso':
            $result = $wpdb->query($wpdb->prepare("UPDATE $tablename
                                                 SET enProceso = 1 
                                                 WHERE id = '" . $dataToSave['id'] . "' "));
            return $result;
            break;
        case 'envio':
            $result = $wpdb->query($wpdb->prepare("UPDATE $tablename
                                                    SET enviado = 1 
                                                    WHERE id = '" . $dataToSave['id'] . "' "));
            return $result;
            break;

        case 'eliminar':
            $result = $wpdb->query($wpdb->prepare("DELETE FROM $tablename  
                                                            WHERE id = '" . $dataToSave['id'] . "' "));
            return $result;
            break;

        default:
            $result = array('error' => 'Error en la escritura en base de datos');
            break;
    }
    die;

    return array('result' => $result);
}

