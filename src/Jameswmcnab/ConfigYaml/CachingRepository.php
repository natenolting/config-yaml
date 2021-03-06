<?php namespace Jameswmcnab\ConfigYaml;

use Illuminate\Cache\Repository as Cache;

class CachingRepository implements RepositoryInterface {

    /**
     * The time to cache items in minutes.
     *
     * @type int
     */
    protected $cacheAgeMinutes = 60;

    /**
     * The repository instance.
     *
     * @type \Jameswmcnab\ConfigYaml\RepositoryInterface
     */
    protected $repository;

    /**
     * The cache repository instance.
     *
     * @type \Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * @param  Repository                    $repository
     * @param  \Illuminate\Cache\Repository  $cache
     */
    public function __construct(Repository $repository, Cache $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string $key
     * @return bool
     */
    public function has($key)
    {
        return $this->cache->has($key) || $this->repository->has($key);
    }

    /**
     * Determine if a configuration group exists.
     *
     * @param  string $key
     * @return bool
     */
    public function hasGroup($key)
    {
        return $this->cache->has($key) || $this->repository->hasGroup($key);
    }

    /**
     * Get a single item or group of items by key.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return string|array
     */
    public function get($key, $default = null)
    {
        return $this->cache->remember($key, $this->cacheAgeMinutes, function(Repository $repository) use ($key, $default)
        {
           return $repository->get($key, $default);
        });
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param  string $namespace
     * @param  string $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->repository->addNamespace($namespace, $hint);
    }

    /**
     * Returns all registered namespaces with the config
     * loader.
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->repository->getNamespaces();
    }

    /**
     * Get the loader implementation.
     *
     * @return \Jameswmcnab\ConfigYaml\LoaderInterface
     */
    public function getLoader()
    {
        return $this->repository->getLoader();
    }

    /**
     * Set the loader implementation.
     *
     * @param  \Jameswmcnab\ConfigYaml\LoaderInterface $loader
     * @return void
     */
    public function setLoader(LoaderInterface $loader)
    {
        $this->repository->setLoader($loader);
    }

}