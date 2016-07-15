<?php

/**
 * Flashes a message to the alert status, with a default level of 'info'
 * @param  string $message
 * @param  string $level   can be 'info', 'success', 'warning'
 */
function flash($message, $level = 'info')
{
    session()->put('flash_message', $message);
    session()->put('flash_message_level', $level);
}

/**
 * Generate a random string with a specified number of characters
 * @param  string $no_of_chars no of characters in random string
 * @return string              a random string
 */
function generate_random_str($no_of_chars) 
{
   return substr(md5(microtime()),rand(0,26),$no_of_chars);
}

/**
 * Checks if the authenticated user can perform one of the abilities listed
 * @param  array $abilities  list of abilities (e.g. show, update, destroy)
 * @return boolean
 */
function canAny($abilities, $model) 
{
    foreach ($abilities as $ability) {
        if (\Gate::allows($ability, $model)) {
            return true;
        }
    }

    return false;
}

/**
 * Return a substring containing the characters after the last slash character
 * If there is no slash character, returns the entire string
 * @param  string $str
 * @return string
 */
function getSubstrAfterLastSlash($str)
{
    $slashPos = strrpos($str, '/');
    $substr = $slashPos === false ? $str : substr($str, $slashPos + 1);

    return $substr;
}

/**
 * Generate file path to thumbnail version of image
 * @param  string $imagePath
 * @return string
 */
function generateThumbnailImagePath($imagePath)
{
    $path_parts = pathinfo($imagePath);

    if (array_key_exists('extension', $path_parts)) {
        return $path_parts['dirname'] . '/' .$path_parts['filename'] .
            config('constants.thumbnail_suffix') . '.' . $path_parts['extension'];
    }

    return $path_parts['dirname'] . '/' .$path_parts['filename'] .
            config('constants.thumbnail_suffix');
}