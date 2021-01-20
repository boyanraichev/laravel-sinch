<?php
namespace Boyo\Sinch\Exceptions;

class CouldNotSendMessage extends \Exception
{
    /**
     * Thrown when there is no phone provided.
     *
     * @return static
     */
    public static function unknownChannel()
    {
        return new static('The delivery channel specified is not valid.');
    }
    
    /**
     * Thrown when there is no phone provided.
     *
     * @return static
     */
    public static function telNotProvided()
    {
        return new static('Phone number was missing.');
    }
    
    /**
     * Thrown when there is no content provided.
     *
     * @return static
     */
    public static function contentNotProvided()
    {
        return new static('Content was not provided.');
    }
    
    /**
     * Thrown when there is no sender ID provided.
     *
     * @return static
     */
    public static function senderIdNotProvided()
    {
        return new static('Missing sender ID in config/services/sinch.');
    }
    
    /**
     * Thrown when the max length is exceeded.
     *
     * @return static
     */
    public static function maxLengthExceeded()
    {
        return new static('The 160 characters max length has been exceeded.');
    }
    
}