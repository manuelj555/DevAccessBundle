<?php
/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manuel\Bundle\DevAccessBundle\Security;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AccessConfig
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    private $filePath;

    public function __construct(Filesystem $filesystem, $filePath)
    {
        $this->filesystem = $filesystem;
        $this->filePath = $filePath;
    }

    public function start(Request $request)
    {
        $filename = $this->createFilename($request);

        $this->filesystem->dumpFile($filename, '');
    }

    public function isStarted(Request $request)
    {
        $filename = $this->createFilename($request);

        return $this->filesystem->exists($filename);
    }

    public function finish(Request $request)
    {
        $filename = $this->createFilename($request);

        return $this->filesystem->remove($filename);
    }

    public function finishForAll()
    {
        return $this->filesystem->remove($this->getPrefixPath());
    }

    /**
     * @param Request $request
     * @return string
     */
    private function createFilename(Request $request)
    {
        $ip = $request->getClientIp();
        $clientInfo = $request->server->get('HTTP_USER_AGENT');
        $scriptName = dirname($request->server->get('SCRIPT_NAME'));

        $filename = hash('sha256', sha1($ip).$clientInfo.$scriptName);

        return $this->getPrefixPath().$filename;
    }

    /**
     * @return string
     */
    private function getPrefixPath()
    {
        return rtrim($this->filePath, '/').'/dev_access_data/';
    }
}