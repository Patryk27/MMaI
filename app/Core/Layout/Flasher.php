<?php

namespace App\Core\Layout;

use Illuminate\Session\Store as SessionStore;

class Flasher
{

    private const
        TYPE_SUCCESS = 'success',
        TYPE_INFORMATION = 'info',
        TYPE_WARNING = 'warning',
        TYPE_ERROR = 'danger';

    /**
     * @var SessionStore
     */
    private $session;

    /**
     * @param SessionStore $session
     */
    public function __construct(
        SessionStore $session
    ) {
        $this->session = $session;
    }

    /**
     * Flashes a success message.
     *
     * @param string $content
     * @return void
     */
    public function flashSuccess(string $content): void
    {
        $this->flash(self::TYPE_SUCCESS, $content);
    }

    /**
     * Flashes an information message.
     *
     * @param string $content
     * @return void
     */
    public function flashInformation(string $content): void
    {
        $this->flash(self::TYPE_INFORMATION, $content);
    }

    /**
     * Flashes a warning message.
     *
     * @param string $content
     * @return void
     */
    public function flashWarning(string $content): void
    {
        $this->flash(self::TYPE_WARNING, $content);
    }

    /**
     * Flashes an error message.
     *
     * @param string $content
     * @return void
     */
    public function flashError(string $content): void
    {
        $this->flash(self::TYPE_ERROR, $content);
    }

    /**
     * @param string $type
     * @param string $content
     * @return void
     */
    private function flash(string $type, string $content): void
    {
        $messages = $this->session->get('messages', []);

        $messages[] = [
            'type' => $type,
            'content' => $content,
        ];

        $this->session->flash('messages', $messages);
    }

}
