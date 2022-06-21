<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'blog';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '123456';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    /**
     * Secret key for hashing
     * @var boolean
     */
    const SECRET_KEY = 'mysecretkey';

    /**
     * Mailgun API key
     *
     * @var string
     */
    const MAILGUN_API_KEY = '22801e60fc293d96535fa026f68f1295-4f207195-5dbce79f';

    /**
     * Mailgun domain
     *
     * @var string
     */
    const MAILGUN_DOMAIN = 'sandboxd90e1b4de75540a0a3b0bac51e662993.mailgun.org';
}
