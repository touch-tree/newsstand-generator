<?php

namespace App\Http\Controllers;

use Framework\Support\Str;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Component\View;
use Framework\Routing\Controller;
use Framework\Routing\Generator\RouteUrlGenerator;
use Framework\Support\Facades\File;

class DefaultController extends Controller
{
    /**
     * Default view.
     *
     * @param Request $request
     * @return View
     */
    public function default(Request $request): View
    {
        $page_id = $request->get('page_id');
        $article_id = $request->get('article_id');

        if (!$article_id) {
            return view('default', [
                'article_id' => null,
                'pages' => []
            ]);
        }

        $pages = [];

        $images_path = public_path('images/' . $article_id);

        foreach (File::all_files($images_path) as $file) {
            $filename = pathinfo($file->getPathname(), PATHINFO_FILENAME);

            if (Str::starts_with($filename, 'page_medium')) {
                $id = Str::replace(['page_medium' => ''], $filename);
                $pages[$id]['image']['medium'] = $file->getPathname();

                continue;
            }

            if (Str::starts_with($filename, 'page_thumb')) {
                $id = Str::replace(['page_thumb' => ''], $filename);
                $pages[$id]['image']['small'] = $file->getPathname();

                continue;
            }

            $id = Str::replace(['page' => ''], $filename);

            $pages[$id] = [
                'image' => [
                    'large' => $file->getPathname()
                ],
                'file' => [
                    'path' => '',
                    'content' => ''
                ],
            ];
        }

        $contents_path = public_path('html/' . $article_id);

        foreach (File::all_files($contents_path) as $file) {
            $filename = pathinfo($file->getPathname(), PATHINFO_FILENAME);

            $id = Str::replace(['page' => ''], $filename);

            $pages[$id]['file'] = [
                'path' => $file->getPathname(),
                'content' => file_get_contents($file->getPathname())
            ];
        }

        return view('default', [
            'article_id' => $article_id,
            'pages' => $pages
        ]);
    }

    /**
     * Content view.
     *
     * @param Request $request
     * @return string|null
     */
    public function content(Request $request): ?string
    {
        $article_id = $request->get('article_id');
        $id = $request->get('id');

        $path = public_path('html/' . $article_id . '/page' . $id . '.html');

        return File::read($path);
    }
}
