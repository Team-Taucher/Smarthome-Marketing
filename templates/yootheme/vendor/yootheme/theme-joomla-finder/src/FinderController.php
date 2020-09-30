<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\File as JFile;
use Joomla\CMS\Filesystem\Path as JPath;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\User\User;
use YOOtheme\File;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use YOOtheme\Path;
use YOOtheme\Str;

class FinderController
{
    public static function index(Request $request, Response $response)
    {
        $base = JPATH_ADMINISTRATOR . '/components/com_media';
        $params = ComponentHelper::getParams('com_media');

        \JLoader::register('MediaHelper', "{$base}/helpers/media.php");

        define('COM_MEDIA_BASE', JPATH_ROOT . "/{$params->get('file_path')}");

        $files = [];

        foreach (BaseController::getInstance('Media', ['base_path' => $base])->getModel('list')->getList() as $type => $items) {
            foreach ($items as $item) {
                $files[] = [
                    'name' => $item->get('name'),
                    'path' => $item->get('path_relative'),
                    'url' => strtr(ltrim(substr($item->get('path'), strlen(JPATH_ROOT)), '/'), '\\', '/'),
                    'type' => $type == 'folders' ? 'folder' : 'file',
                    'size' => $item->get('size') ? \JHtml::_('number.bytes', $item->get('size')) : 0,
                ];
            }
        }

        return $response->withJson($files);
    }

    public static function rename(Request $request, Response $response, User $user)
    {
        $params = ComponentHelper::getParams('com_media');

        if (!$user->authorise('core.create', 'com_media') || !$user->authorise('core.delete', 'com_media')) {
            $request->abort(403, 'Insufficient User Rights.');
        }

        $newName = $request('newName');
        $allowed = "{$params->get('upload_extensions')},svg";
        $extension = File::getExtension($newName);
        $isValidFilename = !empty($newName)
            && (empty($extension) || in_array($extension, explode(',', $allowed)))
            && (defined('PHP_WINDOWS_VERSION_MAJOR')
                ? !preg_match('#[\\/:"*?<>|]#', $newName)
                : strpos($newName, '/') === false);

        if (!$isValidFilename) {
            $request->abort(400, 'Invalid file name.');
        }

        $root = Path::resolve(JPATH_ROOT, $params->get('file_path'));
        $oldFile = Path::resolve($root, JPath::clean($request('oldFile')));
        $path = dirname($oldFile);
        $newPath = Path::resolve($path, $newName);

        if (!Str::startsWith($path, $root) || $path !== dirname($newPath)) {
            $request->abort(400, 'Invalid path.');
        }

        if (!JFile::move($oldFile, $newPath)) {
            $request->abort(500, 'Error writing file.');
        }

        return $response->withJson('Successfully renamed.');
    }
}
