<?php

    namespace App\Facades;

    use Illuminate\Support\Facades\Facade;

    /**
     * @see \App\DbCompare\DbCompareManager
     */
    class DbCompare extends Facade
    {
        /**
         * Get the registered name of the component.
         *
         * @return string
         */
        protected static function getFacadeAccessor()
        {
            return 'App\Contracts\DbCompareFactory';
        }
    }