<?php

    namespace App\Http\Middleware;

    use Config;
    use Closure;

    class DbCompareConnect
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  \Closure $next
         * @return mixed
         */
        public function handle($request, Closure $next) {
            if ($request->has('server1') && $request->has('server2')) {
                $connection1 = array(
                    'driver' => $request->input('driver1'),
                    'host' => $request->input('server1'),
                    'database' => $request->input('database1'),
                    'username' => $request->input('user1'),
                    'password' => $request->input('password1'),
                    'charset' => 'utf8',
                    'collation' => 'utf8_general_ci',
                    'prefix' => $request->input('prefix1'),
                    'schema'   => 'public',
                );

                $connection2 = array(
                    'driver' => $request->input('driver2'),
                    'host' => $request->input('server2'),
                    'database' => $request->input('database2'),
                    'username' => $request->input('user2'),
                    'password' => $request->input('password2'),
                    'charset' => 'utf8',
                    'collation' => 'utf8_general_ci',
                    'prefix' => $request->input('prefix2'),
                    'schema'   => 'public',
                );

                session(['connection1' => $connection1]);
                session(['connection2' => $connection2]);
            } else if (session('connection1') && session('connection2')) {
                $connection1 = session('connection1');
                $connection2 = session('connection2');
            } else {
                return redirect(route('home'));
            }

            Config::set('database.connections.connection1', $connection1);
            Config::set('database.connections.connection2', $connection2);

            return $next($request);
        }
    }
