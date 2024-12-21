<?php

// PDF Generator - Genera documenti PDF da input form
use Dompdf\Dompdf;
use Dompdf\Exception;

function generatePDF($htmlContent, $outputFile) {
    require 'vendor/autoload.php';

    if (empty(trim($htmlContent))) {
        echo "Errore: Il contenuto HTML è vuoto. Impossibile generare il PDF.";
        return false;
    }

    $outputDir = dirname($outputFile);
    if (!is_dir($outputDir) || !is_writable($outputDir)) {
        echo "Errore: La directory di destinazione non esiste o non è scrivibile: $outputDir";
        return false;
    }

    $dompdf = new Dompdf();

    try {
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();
        if (file_put_contents($outputFile, $pdfOutput) === false) {
            throw new Exception("Impossibile scrivere il PDF nel file: $outputFile");
        }

        echo "PDF generato con successo: $outputFile";
        return true;

    } catch (Exception $e) {
        echo "Si è verificato un errore durante la generazione del PDF: " . $e->getMessage();
        return false;
    }
}

?>
