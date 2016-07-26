<?php
/*
 * This file is part of the Arara\Process package.
 *
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arara\Process\Handler;

/**
 * Handles the SIGCHLD signal.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
class SignalChild extends SignalAbstract
{
    /**
     * {@inheritDoc}
     */
    public function __invoke($signal)
    {
        $status = 0;
        while ($this->control->wait($status, (WNOHANG | WUNTRACED)) > 0) {
            usleep(1000);
        }
    }
}
