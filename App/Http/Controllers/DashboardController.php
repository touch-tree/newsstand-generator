<?php

namespace App\Http\Controllers;

use App\Http\Requests\DefaultRequest;
use Framework\Component\View;
use App\Services\PdfService;
use Framework\Routing\Controller;

class DashboardController extends Controller
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
    public function home(DefaultRequest $request): View
    {
        return view('home');
    }
}
