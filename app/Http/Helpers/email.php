<?php

/**
 * @param $email
 *
 * @return bool
 */
function is_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) === $email;
}

function normalize_email($email)
{
    if (is_email($email)) {
        $parts = explode('@', strtolower($email));
        switch ($parts['1']) {
            case 'gmail.com' : {
                    $parts[0] = str_replace('.', '', $parts[0]);
                }
                break;
        }

        return implode('@', $parts);
    }
    throw new \Exception("Invalid email: {$email}");
}
