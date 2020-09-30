<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Router\Route;
use Joomla\CMS\User\User;
use YOOtheme\Config;
use YOOtheme\Http\Exception;
use YOOtheme\Http\Request;

class SystemListener
{
    public static function expiresHeader($request, callable $next)
    {
        // no cache
        return $next($request)
            ->withHeader('Expires', 'Mon, 1 Jan 2001 00:00:00 GMT')
            ->withHeader('Cache-Control', 'no-cache, must-revalidate, max-age=0');
    }

    public static function checkPermission(User $user, $request, callable $next)
    {
        // check user permissions
        if (!$request->getAttribute('allowed') && !($user->authorise('core.edit', 'com_content') || $user->authorise('core.edit.own', 'com_content'))) {
            $request->abort(403, 'Insufficient User Rights.');
        }

        return $next($request);
    }

    public static function redirectLogin(Config $config, User $user, $response, $exception)
    {
        // redirect to user login
        if ($exception instanceof Exception && $exception->getCode() === 403 && $user->guest && !strpos($response->getContentType(), 'json')) {

            if ($config('app.isAdmin')) {
                // Let Joomla handle the request
                return $response->setAttribute('send', false);
            }

            return $response->withRedirect(Route::_('index.php?option=com_users&view=login', false));
        }

        return $response;
    }
}
