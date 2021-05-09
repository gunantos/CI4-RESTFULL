<?php
require '../src/ComposerScripts.php';
require_once  '../vendor/composer/autoload_real.php';

return ComposerAutoloaderInit561b643eb6329baf152b5de06237413e::getLoader();
use Composer\Script\Event;
use Appkita\CIRestful;

$test = new Appkita\CIRestful\ComposerScripts();
#$test->createConfigCI('../apps');
$event = new Event();
        $projectDir = $event->getComposer()->getConfig()->get('vendor-dir');
echo $projectDir;

die(json_encode($event));