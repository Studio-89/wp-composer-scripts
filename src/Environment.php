<?php

namespace Studio89\WP\Composer;

use Composer\IO\IOInterface;
use Composer\Script\Event;

/**
 * Class Environment
 *
 * @package Studio89\WP\Composer
 */
class Environment
{
    public static function generateDbPrefix( Event $event )
    {
        $composer = $event->getComposer();
        $rootDir  = dirname( $composer->getConfig()->get( 'vendor-dir' ) );
        $IO       = $event->getIO();

        $IO->write( 'Generating new WPDB prefix variable...' );

        do {
            $prefix = static::generatePassword( 5, false, false );
        } while (!preg_match('/^[a-z]/', $prefix));

        $updates = [
            '/^[\s\t]*DB_PREFIX[\s\t]*=[\s\t]*"(.*)"[\s\t]*$/m' => $prefix . '_'
        ];

        static::updateFile( $IO, "{$rootDir}/.env", $updates ) && $IO->write( "\t.env has been updated." );

        sleep( 1 );
    }

    /**
     * @param Event $event
     *
     * @throws \Exception
     */
    public static function generateSalts( Event $event )
    {
        $composer = $event->getComposer();
        $rootDir  = dirname( $composer->getConfig()->get( 'vendor-dir' ) );
        $IO       = $event->getIO();

        $IO->write( 'Generating new auth salts variables...' );

        $updates = [];
        $names   = [
            'AUTH_KEY',
            'SECURE_AUTH_KEY',
            'LOGGED_IN_KEY',
            'NONCE_KEY',
            'AUTH_SALT',
            'SECURE_AUTH_SALT',
            'LOGGED_IN_SALT',
            'NONCE_SALT'
        ];

        foreach ( $names as $name ) {
            $updates[ '/^[\s\t]*' . $name . '[\s\t]*=[\s\t]*"(.*)"[\s\t]*$/m' ] = static::generatePassword( 64 );
        }

        static::updateFile( $IO, "{$rootDir}/.env", $updates ) && $IO->write( "\t.env has been updated." );

        sleep( 1 );
    }

    protected static function updateFile( IOInterface $io, $file, $patterns, $warning = true )
    {
        if ( ! is_writable( $file ) ) {
            $warning && $io->writeError( 'Unable to locate or write ' . basename( $file ) . ' file: ' . $file );

            return false;
        }

        $content = file_get_contents( $file );

        foreach ( $patterns as $search => $replace ) {
            if ( preg_match( $search, $content, $matches ) ) {
                $matches[1] = str_replace( $matches[1], $replace, $matches[0] );
                $content    = str_replace( $matches[0], $matches[1], $content );
            }
        }

        return file_put_contents( $file, $content );
    }

    /**
     * @param int $length
     * @param bool $specialChars
     * @param bool $extraChars
     *
     * @return string
     * @throws \Exception
     */
    protected static function generatePassword( $length = 32, $specialChars = true, $extraChars = true )
    {
        $password = '';
        $charMap  = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        if ( $specialChars ) {
            $charMap .= '!@#$%^&*()';
        }

        if ( $extraChars ) {
            $charMap .= '_- []{}<>~`+=,.:;/?|';
        }

        srand();

        for ( $i = 0; $i < $length; $i ++ ) {
            $password .= substr( $charMap, random_int( 0, strlen( $charMap ) - 1 ), 1 );
        }

        return $password;
    }
}