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

function modelemail($typeEmail, $data = null)
{
    if ($typeEmail === 'abandoned') {
        return typeabandoned($data);
    } else if ($typeEmail === 'nuevo') {
        return typenuevo($data);
    } elseif ($typeEmail === 'proceso') {
        return typeproceso(); // No data needed
    } elseif ($typeEmail === 'envio') {
        return typeEnviado(); // No data needed
    } else {
        return 'Invalid email type';
    }
}

function typeabandoned($data)
{
    $currentOrderDate = date('d F Y');

    $email = '
    
    <table cellspacing="0"
        style="max-width: 650px; width: 100%; margin: 0 auto; border: 1px solid #CCCCCC; padding: 15px; font-family: Arial, Helvetica, sans-serif;">
        <thead>
            <tr style="border-bottom: 2px solid #CCCCCC;">
                <td>
                    <a style="max-width: 150px;" href="https://creatubrownie.com/" target="_blank">
                        <img style="max-width: 150px;"
                            src="https://creatubrownie.com/wp-content/uploads/2023/10/cropped-brownie-personalizado-chocoleta.png"
                            alt="Crea Tu Brownie">
                    </a>
                </td>
                <td>
                    <p style="text-align: right; color: #7d7d7d;">
                        ' . $currentOrderDate . '                
                     </p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="border-bottom: 1px solid #CCCCCC;"></div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">
                    <h2 style="font-size: 24px; font-weight: bold; text-align: center; margin-top: 40px;">' . get_option('abandoned_cart_email_subject', '') . '</h2>
                    <p style="text-align: center; font-size: 16px;  margin-bottom: 40px;">
                       ' . get_option('abandoned_cart_email_body', '') . ' <span
                            style="text-decoration: underline; line-height: 16.8px;"><strong><em>' . get_option('abandoned_cart_coupon', '') . '</em></strong></span>
                    </p>
                </td>
            </tr>
            <tr style="background: #000;">
                <td style=" background: #000; padding:10px 20px;">
                    <p style="color: #fff; font-size: 14px; margin: 0;">Detalles Perfil</p>
                </td>
                <td style="background: #000; padding:10px 20px;">
                    <p style="color: #fff; font-size: 14px; margin: 0;">Detalles Envio</p>
                </td>
            </tr>
            <tr>
                <td style="padding:10px 20px;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                <li>
                    <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Nombre:</strong> ' . $data->nombre . '</p>
                </li>
                <li>
                    <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Email:</strong>' . $data->email . '</p>
                </li>
                <li>
                    <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Telefono:</strong>' . $data->telefono . '</p>
                </li>';
    $repareFrase = json_decode($data->frase, true);
    if (is_array($repareFrase)) {
        $fraseCount = count($repareFrase);
    } else {
        $fraseCount = 0;
    }

    $email .= '<li>
                    <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Frases: (' . $fraseCount . ')</strong></p>
                </li> ';
    if (is_array($repareFrase)) {
        foreach ($repareFrase as $frase) {
            $email .= '<li><p style="font-size: 14px; line-height: 120%; color: #000;">' . htmlspecialchars($frase) . '</p></li>';
        }
    }

    $email .= '<li>
                    <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Mensaje:</strong><br>
                    ' . $data->message . '    
                    </p>
                </li>
            </ul>
                        </td>

                <td style="padding:10px 20px;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Direccion:</strong>
                            ' . $data->direccion . '
                            </p>
                        </li>
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Ciudad:</strong>
                            ' . $data->ciudad . '
                            </p>
                        </li>
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Provincia:</strong>
                                ' . $data->province . '
                            </p>
                        </li>
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Codigo Postal:</strong>
                                ' . $data->cp . '
                            </p>
                        </li>
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Fecha de
                                    Entrega:</strong> ' . $data->fechaEntrega . '
                            </p>
                        </li>
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Pagado:</strong> 
                            ' . $data->payment . '
                            </p>
                        </li>';
    if ($data->coupon) {
        $email .= '<li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Cupón:</strong>
                            ' . $data->coupon . '
                            </p>
                        </li>';
    }
    $email .= '</ul>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="margin: 20px 0;"></div>
                </td>
            </tr>
            <tr style="background: #000;">
                <td style=" background: #000; padding:10px 20px;">
                    <p style="color: #fff; font-size: 14px; margin: 0;">Elementos</p>
                </td>
                <td style="background: #000; padding:10px 20px;">
                    <p style="color: #fff; font-size: 14px; margin: 0; text-align: right;">Precio</p>
                </td>
            </tr>
            <tr>
                <td style="padding:10px 20px;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;">Frases - ' . $fraseCount . '</p>
                        </li>';
                        if ($data->express === 'on') {
                            $email .= '<li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;">Envío Express</p>
                        </li>';
                        } else{
                        $email .= '<li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;">Envío Normal</p>
                        </li>';
                        }
    // if ($data->coupon) {
    //     $email .= '<li>
    //                         <p style="font-size: 14px; line-height: 120%; color: #000;">Cupón</p>
    //                     </li>';
    // }
    $email .= '</ul>
                </td>

                <td style="padding:10px 20px;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000; text-align: right;">€' . $data->precio . '</p>
                        </li>
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000; text-align: right;">';
                            if ($data->express === 'on') {
                                $email .= '€' . get_option('expressShiping');
                            } else{
                                $email .= '€' . get_option('precEnvio');
                            }
                            $email .= '</p>
                        </li>';
    // if ($data->coupon) {
    //     $email .= '<li>
    //                         <p style="font-size: 14px; line-height: 120%; color: #000; text-align: right;">- €10</p>
    //                     </li> ';
    // }

    $email .= ' </ul>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="border-bottom: 1px dotted #CCCCCC;"></div>
                </td>
            </tr>
            <tr>
                <td style="padding:0px 20px;">
                    <p style="font-size: 14px; line-height: 120%; color: #000;">TOTAL</p>
                </td>

                <td style="padding:0px 20px;">
                    <p style="font-size: 14px; line-height: 120%; color: #000; text-align: right;">€' . $data->precio . '</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="border-bottom: 1px dotted #CCCCCC;"></div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p style="text-align: center; font-size: 16px;  margin: 40px 0;">
                        <a href="' . get_option('ctf_settings')['plugin_page'] . '?abandoned=' . $data->id . '&coupon=' . get_option('abandoned_cart_coupon', '') . '" target="_blank">Compra Completa!</a>
                    </p>
                </td>
            </tr>
        </tbody>
        <tfoot style="background: #000; padding: 20px;">
            <tr>
                <td colspan="2">
                    <p style="text-align: center; margin: 25px 0;">
                        <span style="color: #ffffff; line-height: 1; font-size: 14px;">
                            <a rel="noopener" href="https://creatubrownie.com/choco-store/" target="_blank"
                                style="color: #ffffff;">Tienda</a> |
                            <a rel="noopener" href="https://creatubrownie.com/crea-tu-frase/" target="_blank"
                                style="color: #ffffff;">Frase</a> |
                            <a rel="noopener" href="https://creatubrownie.com/my-account/" target="_blank"
                                style="color: #ffffff;">Cuenta </a>|
                            <a rel="noopener" href="https://creatubrownie.com/about/" target="_blank"
                                style="color: #ffffff;">Quienes somos </a>|
                            <a rel="noopener" href="https://creatubrownie.com/contact-us/" target="_blank"
                                style="color: #ffffff;">Contacto</a>
                        </span>
                    </p>
                    <p style="font-size: 14px; line-height: 1; text-align: center; color: #fff; margin-bottom: 30px;">Copyright © 2024 <span
                            style="color: #ffffff; line-height: 1;"><a rel="noopener"
                                href="https://creatubrownie.com/" target="_blank"
                                style="color: #ffffff;">Chocoletra</a>.</span></p>
                </td>
            </tr>
        </tfoot>
    </table>
    ';

    return $email;
}

// echo typeabandoned($data);
function typenuevo($data)
{
    $currentOrderDate = date('d F Y');

    $email = '
    
    <table cellspacing="0"
        style="max-width: 650px; width: 100%; margin: 0 auto; border: 1px solid #CCCCCC; padding: 15px; font-family: Arial, Helvetica, sans-serif;">
        <thead>
            <tr style="border-bottom: 2px solid #CCCCCC;">
                <td>
                    <a style="max-width: 150px;" href="https://creatubrownie.com/" target="_blank">
                        <img style="max-width: 150px;"
                            src="https://creatubrownie.com/wp-content/uploads/2023/10/cropped-brownie-personalizado-chocoleta.png"
                            alt="Crea Tu Brownie">
                    </a>
                </td>
                <td>
                    <p style="text-align: right; color: #7d7d7d;">
                        ' . $currentOrderDate . '                
                     </p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="border-bottom: 1px solid #CCCCCC;"></div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">
                    <h2 style="font-size: 24px; font-weight: bold; text-align: center; margin-top: 40px;">¡Gracias por
                        su compra!</h2>
                    <p style="text-align: center; font-size: 16px;  margin-bottom: 40px;">
                        Hemos recibido su pedido y le informaremos sobre este pedido en futuras actualizaciones. Su
                        pedido es: <span
                            style="text-decoration: underline; line-height: 16.8px;"><strong><em>' . $data->uoi . '</em></strong></span>
                    </p>
                </td>
            </tr>
            <tr style="background: #000;">
                <td style=" background: #000; padding:10px 20px;">
                    <p style="color: #fff; font-size: 14px; margin: 0;">Detalles Perfil</p>
                </td>
                <td style="background: #000; padding:10px 20px;">
                    <p style="color: #fff; font-size: 14px; margin: 0;">Detalles Envio</p>
                </td>
            </tr>
            <tr>
                <td style="padding:10px 20px;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                <li>
                    <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Nombre:</strong> ' . $data->nombre . '</p>
                </li>
                <li>
                    <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Email:</strong>' . $data->email . '</p>
                </li>
                <li>
                    <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Telefono:</strong>' . $data->telefono . '</p>
                </li>';
    $repareFrase = json_decode($data->frase, true);
    if (is_array($repareFrase)) {
        $fraseCount = count($repareFrase);
    } else {
        $fraseCount = 0;
    }

    $email .= '<li>
                    <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Frases: (' . $fraseCount . ')</strong></p>
                </li> ';
    if (is_array($repareFrase)) {
        foreach ($repareFrase as $frase) {
            $email .= '<li><p style="font-size: 14px; line-height: 120%; color: #000;">' . htmlspecialchars($frase) . '</p></li>';
        }
    }

    $email .= '<li>
                    <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Mensaje:</strong><br>
                    ' . $data->message . '    
                    </p>
                </li>
            </ul>
                        </td>

                <td style="padding:10px 20px;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Direccion:</strong>
                            ' . $data->direccion . '
                            </p>
                        </li>
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Ciudad:</strong>
                            ' . $data->ciudad . '
                            </p>
                        </li>
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Provincia:</strong>
                                ' . $data->province . '
                            </p>
                        </li>
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Codigo Postal:</strong>
                                ' . $data->cp . '
                            </p>
                        </li>
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Fecha de
                                    Entrega:</strong> ' . $data->fechaEntrega . '
                            </p>
                        </li>
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Pagado:</strong> 
                            ' . $data->payment . '
                            </p>
                        </li>';
    if ($data->coupon) {
        $email .= '<li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;"><strong>Cupón:</strong>
                            ' . $data->coupon . '
                            </p>
                        </li>';
    }
    $email .= '</ul>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="margin: 20px 0;"></div>
                </td>
            </tr>
            <tr style="background: #000;">
                <td style=" background: #000; padding:10px 20px;">
                    <p style="color: #fff; font-size: 14px; margin: 0;">Elementos</p>
                </td>
                <td style="background: #000; padding:10px 20px;">
                    <p style="color: #fff; font-size: 14px; margin: 0; text-align: right;">Precio</p>
                </td>
            </tr>
            <tr>
                <td style="padding:10px 20px;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;">Frases - ' . $fraseCount . '</p>
                        </li>';
                        if ($data->express === 'on') {
                            $email .= '<li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;">Envío Express</p>
                        </li>';
                        } else{
                        $email .= '<li>
                            <p style="font-size: 14px; line-height: 120%; color: #000;">Envío Normal</p>
                        </li>';
                        }
    //              if ($data->coupon) {
    //     $email .= '<li>
    //                         <p style="font-size: 14px; line-height: 120%; color: #000;">Cupón</p>
    //                     </li>';
    // }
    $email .= '</ul>
                </td>

                <td style="padding:10px 20px;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000; text-align: right;">€' . $data->precio . '</p>
                        </li>
                        <li>
                            <p style="font-size: 14px; line-height: 120%; color: #000; text-align: right;">';
                            if ($data->express === 'on') {
                                $email .= '€' . get_option('expressShiping');
                            } else{
                                $email .= '€' . get_option('precEnvio');
                            }
                            $email .= '</p>
                        </li>';
    // if ($data->coupon) {
    //     $email .= '<li>
    //                         <p style="font-size: 14px; line-height: 120%; color: #000; text-align: right;">- €10</p>
    //                     </li> ';
    // }

    $email .= ' </ul>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="border-bottom: 1px dotted #CCCCCC;"></div>
                </td>
            </tr>
            <tr>
                <td style="padding:0px 20px;">
                    <p style="font-size: 14px; line-height: 120%; color: #000;">TOTAL</p>
                </td>

                <td style="padding:0px 20px;">
                    <p style="font-size: 14px; line-height: 120%; color: #000; text-align: right;">€' . $data->precio . '</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="border-bottom: 1px dotted #CCCCCC;"></div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p style="text-align: center; font-size: 16px;  margin: 40px 0;">
                        Gracias por comprar en Crea Tu Brownie, suscríbase a nuestro boletín y manténgase actualizado con
                        nuestros descuentos y ofertas.
                    </p>
                </td>
            </tr>
        </tbody>
        <tfoot style="background: #000; padding: 20px;">
            <tr>
                <td colspan="2">
                    <p style="text-align: center; margin: 25px 0;">
                        <span style="color: #ffffff; line-height: 1; font-size: 14px;">
                            <a rel="noopener" href="https://creatubrownie.com/choco-store/" target="_blank"
                                style="color: #ffffff;">Tienda</a> |
                            <a rel="noopener" href="https://creatubrownie.com/crea-tu-frase/" target="_blank"
                                style="color: #ffffff;">Frase</a> |
                            <a rel="noopener" href="https://creatubrownie.com/my-account/" target="_blank"
                                style="color: #ffffff;">Cuenta </a>|
                            <a rel="noopener" href="https://creatubrownie.com/about/" target="_blank"
                                style="color: #ffffff;">Quienes somos </a>|
                            <a rel="noopener" href="https://creatubrownie.com/contact-us/" target="_blank"
                                style="color: #ffffff;">Contacto</a>
                        </span>
                    </p>
                    <p style="font-size: 14px; line-height: 1; text-align: center; color: #fff; margin-bottom: 30px;">Copyright © 2024 <span
                            style="color: #ffffff; line-height: 1;"><a rel="noopener"
                                href="https://creatubrownie.com/" target="_blank"
                                style="color: #ffffff;">Chocoletra</a>.</span></p>
                </td>
            </tr>
        </tfoot>
    </table>
    ';

    return $email;
}


function typeproceso()
{
    $currentOrderDate = date('d F Y');

    $email = '
    
    <table cellspacing="0"
        style="max-width: 650px; width: 100%; margin: 0 auto; border: 1px solid #CCCCCC; padding: 15px; font-family: Arial, Helvetica, sans-serif; background:#fff;">
        <thead>
            <tr style="border-bottom: 2px solid #CCCCCC;">
                <td>
                    <a style="max-width: 150px;" href="https://creatubrownie.com/" target="_blank">
                        <img style="max-width: 150px;"
                            src="https://creatubrownie.com/wp-content/uploads/2023/10/cropped-brownie-personalizado-chocoleta.png"
                            alt="Crea Tu Brownie">
                    </a>
                </td>
                <td>
                    <p style="text-align: right; color: #7d7d7d;">
                        ' . $currentOrderDate . '                
                     </p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="border-bottom: 1px solid #CCCCCC;"></div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2" style="text-align:center;">
                <img style="max-width: 150px; margin:auto;"
                            src="https://chocoletra.com/wp-content/uploads/2022/01/on-process.jpg"
                            alt="Crea Tu Brownie">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <h2 style="font-size: 24px; font-weight: bold; text-align: center; margin-top: 40px;">Enhorabuena, tu producto ha cambiado a estado:<br> En Proceso!</h2>
                    <p style="text-align: center; font-size: 16px;  margin-bottom: 40px;">Pronto recibirás más actualizaciones de tu pedido.</p>
                </td>
            </tr>
        </tbody>
        <tfoot style="background: #000; padding: 20px;">
            <tr>
                <td colspan="2">
                    <p style="text-align: center; margin: 25px 0;">
                        <span style="color: #ffffff; line-height: 1; font-size: 14px;">
                            <a rel="noopener" https://creatubrownie.com/choco-store/" target="_blank"
                                style="color: #ffffff;">Tienda</a> |
                            <a rel="noopener" https://creatubrownie.com/crea-tu-frase/" target="_blank"
                                style="color: #ffffff;">Frase</a> |
                            <a rel="noopener" https://creatubrownie.com/my-account/" target="_blank"
                                style="color: #ffffff;">Cuenta </a>|
                            <a rel="noopener" https://creatubrownie.com/about/" target="_blank"
                                style="color: #ffffff;">Quienes somos </a>|
                            <a rel="noopener" https://creatubrownie.com/contact-us/" target="_blank"
                                style="color: #ffffff;">Contacto</a>
                        </span>
                    </p>
                    <p style="font-size: 14px; line-height: 1; text-align: center; color: #fff; margin-bottom: 30px;">Copyright © 2024 <span
                            style="color: #ffffff; line-height: 1;"><a rel="noopener"
                                href="https://creatubrownie.com/" target="_blank"
                                style="color: #ffffff;">Crea Tu Brownie</a>.</span></p>
                </td>
            </tr>
        </tfoot>
    </table>';

    return $email;
}


function typeEnviado()
{

    $currentOrderDate = date('d F Y');

    $email = '
    
    <table cellspacing="0"
        style="max-width: 650px; width: 100%; margin: 0 auto; border: 1px solid #CCCCCC; padding: 15px; font-family: Arial, Helvetica, sans-serif; background:#fff;">
        <thead>
            <tr style="border-bottom: 2px solid #CCCCCC;">
                <td>
                    <a style="max-width: 150px;" href="https://creatubrownie.com/" target="_blank">
                        <img style="max-width: 150px;"
                            src="https://creatubrownie.com/wp-content/uploads/2023/10/cropped-brownie-personalizado-chocoleta.png"
                            alt="Crea Tu Brownie">
                    </a>
                </td>
                <td>
                    <p style="text-align: right; color: #7d7d7d;">
                        ' . $currentOrderDate . '                
                     </p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="border-bottom: 1px solid #CCCCCC;"></div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2" style="text-align:center;">
                <img style="max-width: 150px; margin:auto;"
                            src="https://chocoletra.com/wp-content/uploads/2022/01/enviado.jpg"
                            alt="Crea Tu Brownie">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <h2 style="font-size: 24px; font-weight: bold; text-align: center; margin-top: 40px;">Enhorabuena, tu producto ha cambiado a estado: Enviado o programado para la fecha escogida.</h2>
                    <p style="text-align: center; font-size: 16px;  margin-bottom: 40px;">Si tiene alguna pregunta? Cont&aacute;ctenos al siguiente email: <strong><em>info@chocoletra.com</em></strong></p>
                </td>
            </tr>
        </tbody>
        <tfoot style="background: #000; padding: 20px;">
            <tr>
                <td colspan="2">
                    <p style="text-align: center; margin: 25px 0;">
                        <span style="color: #ffffff; line-height: 1; font-size: 14px;">
                            <a rel="noopener" https://creatubrownie.com/choco-store/" target="_blank"
                                style="color: #ffffff;">Tienda</a> |
                            <a rel="noopener" https://creatubrownie.com/crea-tu-frase/" target="_blank"
                                style="color: #ffffff;">Frase</a> |
                            <a rel="noopener" https://creatubrownie.com/my-account/" target="_blank"
                                style="color: #ffffff;">Cuenta </a>|
                            <a rel="noopener" https://creatubrownie.com/about/" target="_blank"
                                style="color: #ffffff;">Quienes somos </a>|
                            <a rel="noopener" https://creatubrownie.com/contact-us/" target="_blank"
                                style="color: #ffffff;">Contacto</a>
                        </span>
                    </p>
                    <p style="font-size: 14px; line-height: 1; text-align: center; color: #fff; margin-bottom: 30px;">Copyright © 2024 <span
                            style="color: #ffffff; line-height: 1;"><a rel="noopener"
                                href="https://creatubrownie.com/" target="_blank"
                                style="color: #ffffff;">Crea Tu Brownie</a>.</span></p>
                </td>
            </tr>
        </tfoot>
    </table>';


    return $email;
}
