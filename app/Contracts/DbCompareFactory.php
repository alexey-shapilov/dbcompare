<?php
    namespace App\Contracts;
    interface DbCompareFactory
    {
        /**
         * Get an OAuth provider implementation.
         *
         * @param  string  $driver
         * @return \App\Contracts\DbCompareProvider
         */
        public function driver($driver = null);
    }