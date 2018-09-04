<?php
/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manuel\Bundle\DevAccessBundle\Security\Listener;

use Manuel\Bundle\DevAccessBundle\Security\AccessConfig;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class ActiveSessionListener
{
    /**
     * @var AccessConfig
     */
    private $accessConfig;
    private $debug;
    private $environment;
    private $expectedEnvironment;

    public function __construct(
        AccessConfig $accessConfig,
        $debug,
        $environment,
        $expectedEnvironment
    ) {
        $this->accessConfig = $accessConfig;
        $this->debug = $debug;
        $this->environment = $environment;
        $this->expectedEnvironment = $expectedEnvironment;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$this->debug) {
            return;
        }

        if ($this->environment !== $this->expectedEnvironment) {
            return;
        }

        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$this->accessConfig->isStarted($request)) {
            return;
        }

        $this->accessConfig->start($request);
    }
}