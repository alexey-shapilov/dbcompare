<?php

    namespace App\DbCompare;

    use App\Contracts\DbCompareFactory;
    use InvalidArgumentException;
    use Illuminate\Support\Manager;
    use App\DbCompare\Provider;

    class DbCompareManager extends Manager implements DbCompareFactory
    {
        /**
         * Get a driver instance.
         *
         * @param  string  $driver
         * @return mixed
         */
        public function with($driver)
        {
            return $this->driver($driver);
        }

        /**
         * Create an instance of the specified driver.
         *
         * @return \App\DbCompare\Provider\AbstractProvider
         */
        protected function createMysqlDriver()
        {
            $config = $this->app['config']['services.github'];
            return $this->buildProvider(
                'App\DbCompare\Provider\MysqlProvider', $config
            );
        }

        /**
         * Create an instance of the specified driver.
         *
         * @return \App\DbCompare\Provider\AbstractProvider
         */
        protected function createPgsqlDriver()
        {
            $config = $this->app['config']['services.github'];
            return $this->buildProvider(
                'App\DbCompare\Provider\PostgresProvider', $config
            );
        }

        /**
         * Create an instance of the specified driver.
         *
         * @return \App\DbCompare\Provider\AbstractProvider
         */
        protected function createSqliteDriver()
        {
            $config = $this->app['config']['services.github'];
            return $this->buildProvider(
                'App\DbCompare\Provider\SqliteProvider', $config
            );
        }

        /**
         * Create an instance of the specified driver.
         *
         * @return \App\DbCompare\Provider\AbstractProvider
         */
        protected function createSqlsrvDriver()
        {
            $config = $this->app['config']['services.github'];
            return $this->buildProvider(
                'App\DbCompare\Provider\SqlsrvProvider', $config
            );
        }



        /**
         * Build an OAuth 2 provider instance.
         *
         * @param  string  $provider
         * @param  array  $config
         * @return \App\DbCompare\Provider\AbstractProvider
         */
        public function buildProvider($provider, $config)
        {
            return new $provider(
                $this->app['request'], $config['client_id'],
                $config['client_secret'], $config['redirect']
            );
        }

        /**
         * Format the server configuration.
         *
         * @param  array  $config
         * @return array
         */
        public function formatConfig(array $config)
        {
            return array_merge([
                'identifier' => $config['client_id'],
                'secret' => $config['client_secret'],
                'callback_uri' => $config['redirect'],
            ], $config);
        }

        /**
         * Get the default driver name.
         *
         * @throws \InvalidArgumentException
         *
         * @return string
         */
        public function getDefaultDriver()
        {
            throw new InvalidArgumentException('No DbCompare driver was specified.');
        }
    }