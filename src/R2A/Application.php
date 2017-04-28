<?php

namespace R2A;

use R2A\Command\Export;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Yaml\Yaml;

class Application extends BaseApplication
{
    const VERSION = '0.0.1';

    /** @var array */
    protected $config;

    public function __construct()
    {
        parent::__construct('Repository2Anki', self::VERSION);
    }

    /**
     * Setup the app.
     *
     * @param $appPath
     */
    public function setup($appPath)
    {
        $this->config = [];
        $this->config['appPath'] = $appPath;
        $this->config['configurationPath'] = '/config/parameters.yml';

        $configPath = $appPath.$this->config['configurationPath'];
        if (!file_exists($configPath)) {
            throw new \LogicException(sprintf(
                'You need a "%s" configuration file',
                basename($configPath)
            ));
        }

        $this->config['parameters'] = Yaml::parse(file_get_contents($configPath));

        $this->registerCommands();
    }

    /**
     * Add commands.
     */
    public function registerCommands()
    {
        $this->add(new Export());
    }

    /**
     * Application path
     *
     * @return mixed
     */
    public function getAppPath()
    {
        return $this->config['appPath']?:null;
    }

    /**
     * Simple parameter management.
     *
     * @param $name
     *
     * @return mixed
     */
    public function getParameter($name)
    {
        if (!isset($this->config['parameters'][$name])) {
            throw new \LogicException(sprintf(
                'The parameters "%s" doesn\'t exists',
                $name
            ));
        }

        return $this->config['parameters'][$name];
    }

    /**
     * Gets the Bundle namespace.
     *
     * @return string The Bundle namespace
     */
    public function getNamespace()
    {
        $class = get_class($this);

        return substr($class, 0, strrpos($class, '\\'));
    }
}
