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

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class Access
{
    public static function check($path)
    {
//        if (in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'], true) || PHP_SAPI === 'cli-server') {
//            return;
//        }

        self::checkPublicAccess($path);
    }

    public static function blockAccess()
    {
        header('HTTP/1.0 403 Forbidden');
        exit('You are not allowed to access!!!');
    }

    private static function checkPublicAccess($path)
    {
        $cachePath = rtrim($path, '/').'/dev_access_data/';
        $filename = $cachePath.static::getFilename();

        if (!is_file($filename)) {
            static::blockAccess();
        }

        $lastModified = filemtime($filename);

        if ((time() - $lastModified) >= 1200) {
            static::blockAccess();
        }
    }

    private static function getFilename()
    {
        $ip = @$_SERVER['REMOTE_ADDR'];
        $clientInfo = @$_SERVER['HTTP_USER_AGENT'];

        $filename = hash('sha256', sha1($ip).$clientInfo.__DIR__);

        return $filename;
    }
}