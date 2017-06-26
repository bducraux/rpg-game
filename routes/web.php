<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return '<pre>
____________________  ________  __________         __    __  .__
\______   \______   \/  _____/  \______   \_____ _/  |__/  |_|  |   ____
 |       _/|     ___/   \  ___   |    |  _/\__  \\   __\   __\  | _/ __ \
 |    |   \|    |   \    \_\  \  |    |   \ / __ \|  |  |  | |  |_\  ___/
 |____|_  /|____|    \______  /  |______  /(____  /__|  |__| |____/\___  >
        \/                  \/          \/      \/                     \/

 __      __      ___.       _________                  .__
/  \    /  \ ____\_ |__    /   _____/ ______________  _|__| ____  ____
\   \/\/   // __ \| __ \   \_____  \_/ __ \_  __ \  \/ /  |/ ___\/ __ \
 \        /\  ___/| \_\ \  /        \  ___/|  | \/\   /|  \  \__\  ___/
  \__/\  /  \___  >___  / /_______  /\___  >__|    \_/ |__|\___  >___  >
       \/       \/    \/          \/     \/                    \/    \/

</pre>';
});
$app->group(['prefix' => 'api/v1'], function () use ($app) {

    $app->post('initiative','GameController@rollInitiative');

    $app->post('/attack','GameController@rollAttack');

});