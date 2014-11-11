<?php
/**
 * Bootstrapping for PHPUnit-Tests
 *
 * @copyright   final gene
 * @package     PHING commons
 * @author      Frank Giesecke <frank.giesecke@final-gene.de>
 */

error_reporting(-1);
ini_set('memory_limit', '128M');

date_default_timezone_set('Europe/Berlin');

/**
 * Files will be created as -rw-rw-r--
 * Directories will be creates as drwxrwxr-x
 */
umask(0002);

chdir(dirname(__DIR__));

/**
 * @var \Composer\Autoload\ClassLoader $autoloader
 */
$autoloader = require 'vendor/autoload.php';
$autoloader->addClassMap(["src"]);

require 'vendor/phing/phing/test/bootstrap.php';
