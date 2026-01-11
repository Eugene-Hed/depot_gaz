<?php

namespace App\Exports;

use Illuminate\View\View;

class PDFExport
{
    protected $view;
    protected $data;
    protected $filename;

    public function __construct($view, $data, $filename)
    {
        $this->view = $view;
        $this->data = $data;
        $this->filename = $filename;
    }

    public function render()
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadView($this->view, $this->data);
        return $pdf->download($this->filename . '.pdf');
    }
}
