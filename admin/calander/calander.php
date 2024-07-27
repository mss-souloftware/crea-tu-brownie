<?php
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
 */

function calanderOutput() {
    if (isset($_POST['calendar_settings_nonce']) && wp_verify_nonce($_POST['calendar_settings_nonce'], 'save_calendar_settings')) {
        if (isset($_POST['disable_dates'])) {
            update_option('disable_dates', array_map('sanitize_text_field', $_POST['disable_dates']));
        }
        if (isset($_POST['disable_days'])) {
            update_option('disable_days', array_map('sanitize_text_field', $_POST['disable_days']));
        }
        if (isset($_POST['disable_months_days'])) {
            update_option('disable_months_days', [
                'months' => array_map('sanitize_text_field', $_POST['disable_months_days']['months']),
                'days' => array_map('sanitize_text_field', $_POST['disable_months_days']['days'])
            ]);
        }
    }

    $disable_dates = get_option('disable_dates', []);
    $disable_days = get_option('disable_days', []);
    $disable_months_days = get_option('disable_months_days', ['months' => [], 'days' => []]);

    // Ensure that disable_months_days is an array with 'months' and 'days' keys
    if (!is_array($disable_months_days)) {
        $disable_months_days = ['months' => [], 'days' => []];
    } else {
        if (!isset($disable_months_days['months']) || !is_array($disable_months_days['months'])) {
            $disable_months_days['months'] = [];
        }
        if (!isset($disable_months_days['days']) || !is_array($disable_months_days['days'])) {
            $disable_months_days['days'] = [];
        }
    }

    ?>

    <div class="wrap">
        <h2>Configure Calendar Settings</h2>
        <form method="post" action="" id="calendarSettingsStyling">
            <?php wp_nonce_field('save_calendar_settings', 'calendar_settings_nonce'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Deshabilitar múltiples fechas</th>
                    <td>
                        <input type="text" name="disable_dates[]" value="<?php echo esc_attr(implode(',', $disable_dates)); ?>" placeholder="Select dates" id="disable_dates" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Deshabilitar varios días con todos los Meses/Años</th>
                    <td>
                        <select name="disable_days[]" multiple>
                            <option value="0" <?php echo in_array("0", $disable_days) ? 'selected' : ''; ?>>Sunday</option>
                            <option value="1" <?php echo in_array("1", $disable_days) ? 'selected' : ''; ?>>Monday</option>
                            <option value="2" <?php echo in_array("2", $disable_days) ? 'selected' : ''; ?>>Tuesday</option>
                            <option value="3" <?php echo in_array("3", $disable_days) ? 'selected' : ''; ?>>Wednesday</option>
                            <option value="4" <?php echo in_array("4", $disable_days) ? 'selected' : ''; ?>>Thursday</option>
                            <option value="5" <?php echo in_array("5", $disable_days) ? 'selected' : ''; ?>>Friday</option>
                            <option value="6" <?php echo in_array("6", $disable_days) ? 'selected' : ''; ?>>Saturday</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Desactivar días en meses seleccionados</th>
                    <td>
                        <select name="disable_months_days[months][]" multiple>
                            <option value="0" <?php echo in_array("0", $disable_months_days['months']) ? 'selected' : ''; ?>>January</option>
                            <option value="1" <?php echo in_array("1", $disable_months_days['months']) ? 'selected' : ''; ?>>February</option>
                            <option value="2" <?php echo in_array("2", $disable_months_days['months']) ? 'selected' : ''; ?>>March</option>
                            <option value="3" <?php echo in_array("3", $disable_months_days['months']) ? 'selected' : ''; ?>>April</option>
                            <option value="4" <?php echo in_array("4", $disable_months_days['months']) ? 'selected' : ''; ?>>May</option>
                            <option value="5" <?php echo in_array("5", $disable_months_days['months']) ? 'selected' : ''; ?>>June</option>
                            <option value="6" <?php echo in_array("6", $disable_months_days['months']) ? 'selected' : ''; ?>>July</option>
                            <option value="7" <?php echo in_array("7", $disable_months_days['months']) ? 'selected' : ''; ?>>August</option>
                            <option value="8" <?php echo in_array("8", $disable_months_days['months']) ? 'selected' : ''; ?>>September</option>
                            <option value="9" <?php echo in_array("9", $disable_months_days['months']) ? 'selected' : ''; ?>>October</option>
                            <option value="10" <?php echo in_array("10", $disable_months_days['months']) ? 'selected' : ''; ?>>November</option>
                            <option value="11" <?php echo in_array("11", $disable_months_days['months']) ? 'selected' : ''; ?>>December</option>
                        </select>
                        <select name="disable_months_days[days][]" multiple>
                            <option value="0" <?php echo in_array("0", $disable_months_days['days']) ? 'selected' : ''; ?>>Sunday</option>
                            <option value="1" <?php echo in_array("1", $disable_months_days['days']) ? 'selected' : ''; ?>>Monday</option>
                            <option value="2" <?php echo in_array("2", $disable_months_days['days']) ? 'selected' : ''; ?>>Tuesday</option>
                            <option value="3" <?php echo in_array("3", $disable_months_days['days']) ? 'selected' : ''; ?>>Wednesday</option>
                            <option value="4" <?php echo in_array("4", $disable_months_days['days']) ? 'selected' : ''; ?>>Thursday</option>
                            <option value="5" <?php echo in_array("5", $disable_months_days['days']) ? 'selected' : ''; ?>>Friday</option>
                            <option value="6" <?php echo in_array("6", $disable_months_days['days']) ? 'selected' : ''; ?>>Saturday</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
