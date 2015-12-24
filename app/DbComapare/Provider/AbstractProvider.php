<?php
    namespace App\DbCompare\Provider;

    use App\DbCompare\DbCompareProvider as ProviderContract;
    abstract class AbstractProvider implements ProviderContract
    {
        abstract public function getTable($db);
    }