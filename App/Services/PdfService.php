<?php

namespace App\Services;

use Framework\Component\Service;
use Framework\Console\Process;

class PdfService extends Service
{
    /**
     * Converts a range of pages from a PDF file to a single HTML file using pdftohtml.
     *
     * @param string $path The path to the PDF file.
     * @param string $output The path to save the HTML output.
     * @param int $start The starting page number.
     * @param int|null $end [optional] The ending page number.
     *
     * @return string|null The HTML output on success, or null on failure.
     */
    public function to_html(string $path, string $output, int $start, int $end = null): ?string
    {
        if (is_null($end)) {
            $end = $start;
        }

        $process = new Process(
            [
                'pdftohtml',
                '-c',
                '-f ' . $start,
                '-l ' . $end,
                $path,
                $output,
            ]
        );

        return $process->run()->get_output();
    }
}
