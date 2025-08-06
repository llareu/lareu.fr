<?php
namespace Core\Session;

class FlashService
{
    /**
     * @var SessionInterface
     */
    private $session;

    private $sessionKey = 'flash';

    private $msg;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    public function success(string $msg)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['success'] = $msg;
        $this->session->set($this->sessionKey, $flash);
    }

    public function warning(string $msg)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['warning'] = $msg;
        $this->session->set($this->sessionKey, $flash);
    }

    public function error(string $msg)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['error'] = $msg;
        $this->session->set($this->sessionKey, $flash);
    }

    public function get(string $type): ?string
    {
        if (is_null($this->msg)) {
            $this->msg = $this->session->get($this->sessionKey, []);
            $this->session->delete($this->sessionKey);
        }

        if (array_key_exists($type, $this->msg)) {
            return $this->msg[$type];
        }
        return null;
    }
}
