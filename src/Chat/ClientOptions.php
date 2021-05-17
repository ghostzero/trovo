<?php


namespace GhostZero\Trovo\Chat;


class ClientOptions
{
    private array $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }
}