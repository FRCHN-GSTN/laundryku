<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Handlers\BaseHandler;
use CodeIgniter\Session\Handlers\FileHandler;

class Session extends BaseConfig
{
    /**
     * Session Driver
     *
     * @var class-string<BaseHandler>
     */
    public string $driver = FileHandler::class;

    /**
     * Session Cookie Name
     */
    public string $cookieName = 'ci_session';

    /**
     * Session Expiration (seconds)
     */
    public int $expiration = 7200;

    /**
     * Session Save Path
     */
    public string $savePath = '';

    public function __construct()
    {
        parent::__construct();

        $sessionDir = WRITEPATH . 'session';
        if (!is_dir($sessionDir)) {
            @mkdir($sessionDir, 0775, true);
        }

        $this->savePath = is_dir($sessionDir) ? $sessionDir : sys_get_temp_dir();
    }

    /**
     * Session Match IP
     */
    public bool $matchIP = false;

    /**
     * Session Time to Update (seconds)
     */
    public int $timeToUpdate = 300;

    /**
     * Session Regenerate Destroy
     */
    public bool $regenerateDestroy = true;

    /**
     * Session Database Group
     */
    public ?string $DBGroup = null;

    /**
     * Lock Retry Interval (microseconds)
     */
    public int $lockRetryInterval = 100_000;

    /**
     * Lock Max Retries
     */
    public int $lockMaxRetries = 300;

    /**
     * Session Cookie HTTPOnly
     */
    public bool $cookieHTTPOnly = true;

    /**
     * Session Cookie Secure
     */
    public bool $cookieSecure = false;

    /**
     * Session Cookie SameSite
     *
     * @var 'Lax'|'Strict'|'None'|''
     */
    public string $cookieSameSite = 'Lax';
}
