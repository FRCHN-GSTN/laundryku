<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Security extends BaseConfig
{
    /**
     * CSRF Protection Method
     *
     * @var string 'cookie' or 'session'
     */
    public string $csrfProtection = 'cookie';

    /**
     * CSRF Token Randomization
     */
    public bool $tokenRandomize = false;

    /**
     * CSRF Token Name
     */
    public string $tokenName = 'csrf_test_name';

    /**
     * CSRF Header Name
     */
    public string $headerName = 'X-CSRF-TOKEN';

    /**
     * CSRF Cookie Name
     */
    public string $cookieName = 'csrf_cookie_name';

    /**
     * CSRF Expires (seconds)
     */
    public int $expires = 7200;

    /**
     * CSRF Regenerate
     */
    public bool $regenerate = true;

    /**
     * CSRF Redirect on failure
     */
    public bool $redirect = (ENVIRONMENT === 'production');
}
