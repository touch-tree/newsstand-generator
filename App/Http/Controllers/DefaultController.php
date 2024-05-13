<?php

namespace App\Http\Controllers;

use App\Http\Requests\DefaultRequest;
use Framework\Component\View;
use App\Services\PdfService;
use Framework\Routing\Controller;
use Framework\Http\Request;
use Framework\Http\Response;

class DefaultController extends Controller
{
    /**
     * PdfService instance.
     *
     * @var PdfService
     */
    private PdfService $pdf_service;

    /**
     * DashboardController constructor.
     *
     * @param PdfService $pdf_service PdfService instance.
     */
    public function __construct(PdfService $pdf_service)
    {
        $this->pdf_service = $pdf_service;
    }

    /**
     * Default view.
     *
     * @param DefaultRequest $request
     * @return View
     */
    public function default(DefaultRequest $request): View
    {
        return view('home');
    }

    /**
     * Handles the uploading and conversion of a PDF file to HTML.
     *
     * @param Request $request The HTTP request object.
     * @return Response Returns a response with HTML content or an error message.
     */
    public function upload_pdf(Request $request): Response
    {
        $file = $request->file('pdfFile');

        if ($file !== null) {
            $path = $file->path();
            $outputHtmlPath = tempnam(sys_get_temp_dir(), 'pdfToHtml') . '.html';
            
            $html = $this->pdf_service->to_html($path, $outputHtmlPath, 1);

            return response($html);
        }

        return response('File upload error', 400);
    }
}
