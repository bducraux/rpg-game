<?php
namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController{
    /**
     * Return a JSON response for success.
     *
     * @param  array  $data
     * @param  string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data, $code){
        return response()->json(['data' => $data], $code);
    }
    /**
     * Return a JSON response for error.
     *
     * @param  array  $message
     * @param  string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($message, $code){
        return response()->json(['message' => $message], $code);
    }

    /**
     * Get the requested action method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getAction(Request $request){
        return explode('@', $request->route()[1]["uses"], 2)[1];
    }

    /**
     * Get the parameters in route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getArgs(Request $request){
        return $request->route()[2];
    }
}