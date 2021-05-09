<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Appkita\CIRestful;
/**
 * ComposerScripts
 *
 * These scripts are used by Composer during installs and updates
 * to move files to locations within the system folder so that end-users
 * do not need to use Composer to install a package, but can simply
 * download
 *
 * @codeCoverageIgnore
 */
use Composer\Script\Event;

class ComposerScripts
{
	/**
	 * Base path to use.
	 *
	 * @var string
	 */
	protected static $basePath = '../app/';
    
    private function logs($log_msg)
    {
        $log_filename = "log";
        if (!file_exists($log_filename)) 
        {
            // create directory/folder uploads.
            mkdir($log_filename, 0777, true);
        }
        $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
        // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
        file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
    } 
	/**
	 * After composer install/update, this is called to move
	 * the bare-minimum required files for our dependencies
	 * to appropriate locations.
	 *
	 * @throws ReflectionException
	 */
	public static function postUpdate(Event $event)
	{
        $projectDir = $event->getComposer()->getConfig()->get('archive-dir');
        self::createConfigCI($folder);
        self::createConfigCI('../../../app');
	}

    
	public static function postPackageInstall(PackageEvent $event)
    {
        $installedPackage = $event->getOperation()->getPackage();
        return self::createConfigCI('../../../app');
        // do stuff
    }

    public static function createConfigCI($folder) {
        self::logs('Copy folder '. self::$basePath .' to '. $folder);
        return self::copyDir(self::$basePath, $folder);
    }
	//--------------------------------------------------------------------

	/**
	 * Move a file.
	 *
	 * @param string $source
	 * @param string $destination
	 *
	 * @return boolean
	 */
	protected static function moveFile(string $source, string $destination): bool
	{
		$source = realpath($source);

		if (empty($source))
		{
			// @codeCoverageIgnoreStart
			die('Cannot move file. Source path invalid.');
			// @codeCoverageIgnoreEnd
		}

		if (! is_file($source))
		{
			return false;
		}

		return copy($source, $destination);
	}

	//--------------------------------------------------------------------

	/**
	 * Determine file path of a class.
	 *
	 * @param string $class
	 *
	 * @return string
	 * @throws ReflectionException
	 */
	protected static function getClassFilePath(string $class)
	{
		$reflector = new ReflectionClass($class);

		return $reflector->getFileName();
	}

	//--------------------------------------------------------------------

	/**
	 * A recursive remove directory method.
	 *
	 * @param string $dir
	 */
	protected static function removeDir($dir)
	{
		if (is_dir($dir))
		{
			$objects = scandir($dir);
			foreach ($objects as $object)
			{
				if ($object !== '.' && $object !== '..')
				{
					if (filetype($dir . '/' . $object) === 'dir')
					{
						static::removeDir($dir . '/' . $object);
					}
					else
					{
						unlink($dir . '/' . $object);
					}
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}

	protected static function copyDir($source, $dest)
	{
		$dir = opendir($source);
		@mkdir($dest);

		while (false !== ($file = readdir($dir)))
		{
			if (($file !== '.') && ($file !== '..'))
			{
				if (is_dir($source . '/' . $file))
				{
					static::copyDir($source . '/' . $file, $dest . '/' . $file);
				}
				else
				{
					copy($source . '/' . $file, $dest . '/' . $file);
				}
			}
		}

		closedir($dir);
	}

}
