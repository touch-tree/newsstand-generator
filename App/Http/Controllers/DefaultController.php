<?php

namespace App\Http\Controllers;

use Framework\Component\View;
use Framework\Routing\Controller;
use Framework\Http\Request;
use Framework\Support\Facades\File;
use Framework\Support\Str;

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
        $images = File::all_files('C:\Users\JoshAgripo\Desktop\687');
        $contents = File::all_files('C:\Users\JoshAgripo\Desktop\htmlversion');

        $pages = [];

        foreach ($images as $file) {
            $filename = pathinfo($file->getPathname(), PATHINFO_FILENAME);

            if (Str::starts_with($filename, 'page_medium')) {
                $id = Str::replace(['page_medium' => ''], $filename);

                File::copy($file->getPathname(), $path = public_path('temp/page_medium_' . $id . '.jpg'));

                $pages[$id]['image']['medium'] = $path;

                continue;
            }

            if (Str::starts_with($filename, 'page_thumb')) {
                $id = Str::replace(['page_thumb' => ''], $filename);

                File::copy($file->getPathname(), $path = public_path('temp/page_thumb_' . $id . '.jpg'));

                $pages[$id]['image']['small'] = $path;

                continue;
            }

            $id = Str::replace(['page' => ''], $filename);

            File::copy($file->getPathname(), $path = public_path('temp/page_' . $id . '.jpg'));

            $pages[$id] = [
                'image' => [
                    'large' => $path
                ],
                'file' => [
                    'path' => '',
                    'content' => ''
                ],
            ];
        }

        foreach ($contents as $file) {
            $filename = pathinfo($file->getPathname(), PATHINFO_FILENAME);

            $id = Str::replace(['page' => ''], $filename);

            $pages[$id]['file'] = [
                'path' => $file->getPathname(),
                'content' => file_get_contents($file->getPathname())
            ];
        }

        return view('default', [
            'pages' => $pages
        ]);
    }
}
