<?php

namespace App\Http\Middleware;

use Closure;

class TelnumberNiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(strlen($request->input('inputNumber'))  > 13) {
            preg_match('/<(.\d*)>/', $request->input('inputNumber'), $matches);
            if (!empty($matches)) {
                $request['inputNumber'] = $matches[1];
                if (strlen($request->input('inputNumber')) == 13 && substr($request->input('inputNumber'), 0, 4) == '+380') {
                    $request['inputNumber'] = substr($request->input('inputNumber'), 1);
                } elseif (strlen($request->input('inputNumber')) == 12 && substr($request->input('inputNumber'), 0, 3) == '380') {
                    //okey
                } elseif (strlen($request->input('inputNumber')) == 11 && substr($request->input('inputNumber'), 0, 2) == '80') {
                    $request['inputNumber'] = '3' . $request->input('inputNumber') . '';
                } elseif (strlen($request->input('inputNumber')) == 10 && substr($request->input('inputNumber'), 0, 1) == '0') {
                    $request['inputNumber'] = '38' . $request->input('inputNumber') . '';
                } elseif (strlen($request->input('inputNumber')) < 10 && substr($request->input('inputNumber'), 0, 1) != '0') {
                    //okey
                } else {
                    $request['inputNumber'] = NULL;
                }
            } else {
                $request['inputNumber'] = NULL;
            }
            return $next($request);
        } else {
            if (strlen($request->input('inputNumber')) == 13 && substr($request->input('inputNumber'), 0, 4) == '+380') {
                $request['inputNumber'] = substr($request->input('inputNumber'), 1);
            } elseif (strlen($request->input('inputNumber')) == 12 && substr($request->input('inputNumber'), 0, 3) == '380') {
                //okey
            } elseif (strlen($request->input('inputNumber')) == 11 && substr($request->input('inputNumber'), 0, 2) == '80') {
                $request['inputNumber'] = '3' . $request->input('inputNumber') . '';
            } elseif (strlen($request->input('inputNumber')) == 10 && substr($request->input('inputNumber'), 0, 1) == '0') {
                $request['inputNumber'] = '38' . $request->input('inputNumber') . '';
            } elseif (strlen($request->input('inputNumber')) < 10 && substr($request->input('inputNumber'), 0, 1) == '0') {
                $request['inputNumber'] = NULL;
            } else {
                //okey
            }
        }
        return $next($request);
    }
}
