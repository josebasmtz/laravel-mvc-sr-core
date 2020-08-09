<?php
if (!function_exists('_i'))
{
    function _i(string $message, ...$params)
    {
        if (count($params) > 0)
        {
            return sprintf($message, ...$params);
        }
        return $message;
    }
}

if (!function_exists('errors_key'))
{
    function errors_key()
    {
        return 'errors';
    }
}

if (!function_exists('valid_str'))
{
    /**
     * @param string $str
     * @param mixed $default
     * @return null|mixed
     */
    function valid_str($str = null, $default = null)
    {
        $str = (string)$str;
        $response = $default;

        try {
            $str = preg_replace('/ {2,}/', ' ', $str);
            $str = trim($str);

            if ($str !== '')
            {
                $response = $str;
            }
        }
        catch (Exception $e){}

        return $response;
    }
}