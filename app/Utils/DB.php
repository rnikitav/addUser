<?php

namespace App\Utils;

use Closure;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB as DBFacade;
use Illuminate\Support\Facades\Log;
use Throwable;

class DB
{
    /**
     * @throws Throwable
     */
    public static function inTransaction(Closure $callback)
    {
        DBFacade::beginTransaction();

        try {
            $result = $callback();

            DBFacade::commit();


        } catch (Exception $exception) {

            DBFacade::rollBack();

            $channel = $exception instanceof QueryException ? 'database' : config('logging.default');
            Log::channel($channel)->error($exception->getMessage());

            abort(500, 'DB Exception');
        }

        return $result;
    }
}
