<?php

namespace Feliciencorbat\Mycocarto\Service;

use TCPDF;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PdfReport extends TCPDF
{
    public function generatePdfReport(string $author, array $observations): void
    {
        // create new PDF document
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator($author);
        $pdf->SetAuthor($author);
        $pdf->SetTitle('Rapport PDF Mycocarto');
        $pdf->SetKeywords('rapport, mycocarto');

        $storageRepository = GeneralUtility::makeInstance(StorageRepository::class);
        $defaultStorage = $storageRepository->getDefaultStorage();

        // set default header data
        $pdf->SetHeaderData('', 0, 'Rapport Mycocarto', date("d.m.Y"));

        // set margins
        $pdf->SetMargins(10, 25, 10);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(15);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 20);


        $pdf->SetFont('helvetica', '', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // generate observations in table
        $observationsTable = '<table>
                                    <tr>
                                        <th><strong>Date</strong></th>
                                        <th><strong>Espèce</strong></th>
                                        <th><strong>Coordonnées</strong></th>
                                        <th><strong>Ecologie</strong></th>
                                    </tr>';
        foreach($observations as $observation) {
            $observationsTable .= '<tr>
                                        <td>' . $observation->getDate()->format('d.m.Y') . '</td>
                                        <td><i>' . $observation->getSpecies()->getCanonicalName() . '</i> ' . $observation->getSpecies()->getAuthor() . '</td>
                                        <td>' . $observation->getLatitude() . ' / ' . $observation->getLongitude() . '</td>  
                                        <td>' . $observation->getEcology()->getName() . '</td> 
                                    </tr>';
        }
        $observationsTable .= '</table>';

        // Set some content to print
        $html = '
        <h1>Rapport Mycocarto du ' . date("d.m.Y") . '</h1>
        <h2>Liste des observations</h2>
        ' . $observationsTable;

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('rapport_mycocarto.pdf', 'I');
    }
}