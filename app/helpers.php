<?php

/**
 * Flashes a message to the alert status, with a default level of 'info'
 * @param  string $message
 * @param  string $level   can be 'info', 'success', 'warning'
 */
function flash($message, $level = 'info')
{
    session()->flash('flash_message', $message);
    session()->flash('flash_message_level', $level);
}
