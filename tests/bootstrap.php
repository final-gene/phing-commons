<?php
/**
 * Bootstrapping for PHPUnit-Tests
 *
 * @copyright   final gene
 * @package     PHING commons
 * @author      Frank Giesecke <frank.giesecke@final-gene.de>
 */

error_reporting(-1);

date_default_timezone_set('Europe/Berlin');

/**
 * Files will be created as -rw-rw-r--
 * Directories will be creates as drwxrwxr-x
 */
umask(0002);

chdir(dirname(__DIR__));

require 'vendor/autoload.php';
require 'vendor/phing/phing/test/bootstrap.php';
