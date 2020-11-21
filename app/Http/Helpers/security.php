<?php

/**
 * Generate encryption for email verification
 * @param type $id
 * @param type $hash
 * @return type
 */
function encrypt_for_verification($id, $hash)
{
    return encrypt("$id $hash");
}

/**
 * Decrypt encryption for email verification
 * @param type $encryption
 * @return array
 */
function decrypt_for_verification($encryption)
{
    $d = explode(' ', decrypt($encryption));
    if (isset($d[0]) && isset($d[1])) {
        $result['id'] = $d[0];
        $result['hash'] = $d[1];
        return $result;
    } else {
        throw new Exception('String could not be decrypted');
    }
}

function random_chars($length = 8)
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $result = '';
    $strlen = strlen($chars);
    for ($i = 1; $i <= $length; $i++) {
        $pos = rand(0, $strlen - 1);
        $result .= substr($chars, $pos, 1);
    }
    return $result;
}

function remove_scripts($html)
{
    return preg_replace('#<\\s*/*\\s*script\\s*>#', '', $html);
}
