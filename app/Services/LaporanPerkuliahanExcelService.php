<?php

namespace App\Services;

use Carbon\CarbonInterface;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use RuntimeException;
use ZipArchive;

class LaporanPerkuliahanExcelService
{
    private const FIRST_DATA_ROW = 16;

    private const LAST_TEMPLATE_DATA_ROW = 30;

    public function generate(
        Collection $ringkasanPerkuliahan,
        ?string $semester,
        ?string $tahunAkademik,
        string $programStudi,
        CarbonInterface $tanggalLaporan,
    ): string {
        $templatePath = resource_path('templates/Template Laporan Pelaksanaan Perkuliahan FST.xlsx');

        if (! File::exists($templatePath)) {
            throw new RuntimeException('Template laporan pelaksanaan perkuliahan tidak ditemukan.');
        }

        $directory = storage_path('app/private/laporan');
        File::ensureDirectoryExists($directory);

        $outputPath = $directory.'/laporan-pelaksanaan-perkuliahan-'.uniqid().'.xlsx';
        File::copy($templatePath, $outputPath);

        $zip = new ZipArchive;

        if ($zip->open($outputPath) !== true) {
            throw new RuntimeException('File laporan Excel tidak dapat dibuat.');
        }

        try {
            $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');

            if ($sheetXml === false) {
                throw new RuntimeException('Sheet laporan tidak ditemukan di dalam template.');
            }

            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false;
            $dom->loadXML($sheetXml);

            $xpath = new DOMXPath($dom);
            $xpath->registerNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $centeredStyles = $this->createCenteredStyles($zip);

            $extraRows = max(0, $ringkasanPerkuliahan->count() - $this->templateDataRowCount());

            if ($extraRows > 0) {
                $this->insertAdditionalRows($xpath, $extraRows);
            }

            $title = 'LAPORAN PELAKSANAAN PERKULIAHAN PROGRAM STUDI '.
                ($programStudi !== '' ? mb_strtoupper($programStudi) : '................................');
            $subtitle = 'SEMESTER '.mb_strtoupper($semester ?: '........').' '.($tahunAkademik ?: '........');

            $this->setText($dom, $xpath, 'A11', $title);
            $this->setText($dom, $xpath, 'A12', $subtitle);
            $this->setText(
                $dom,
                $xpath,
                'G'.(33 + $extraRows),
                'Kupang, '.$tanggalLaporan->locale('id')->translatedFormat('d F Y')
            );

            foreach ($ringkasanPerkuliahan->values() as $index => $item) {
                $row = self::FIRST_DATA_ROW + $index;
                $dosen = $item->perkuliahan?->pengajars?->pluck('dosen.nama_dosen')->filter()->join(', ');

                $this->setNumber($dom, $xpath, 'A'.$row, $index + 1);
                $this->setText($dom, $xpath, 'B'.$row, $item->perkuliahan?->mataKuliah?->nama_mk ?? '-');
                $this->setText($dom, $xpath, 'C'.$row, $item->perkuliahan?->kelas?->nama_kelas ?? '-');
                $this->setText($dom, $xpath, 'D'.$row, $dosen ?: '-');
                $this->setNumber($dom, $xpath, 'E'.$row, $item->jumlah_pertemuan);
                $this->setText($dom, $xpath, 'F'.$row, $item->kesesuaian_materi === 'sesuai' ? '√' : '');
                $this->setText($dom, $xpath, 'G'.$row, $item->kesesuaian_materi !== 'sesuai' ? '√' : '');
                $this->setText($dom, $xpath, 'H'.$row, $item->keterangan ?: '-');

                foreach (['C', 'E', 'F', 'G'] as $column) {
                    $this->applyCenteredStyle($xpath, $column.$row, $centeredStyles);
                }
            }

            $dimension = $xpath->query('//x:dimension')->item(0);
            $dimension?->setAttribute('ref', 'A1:K'.(35 + $extraRows));

            $zip->addFromString('xl/worksheets/sheet1.xml', $dom->saveXML());
            $this->extendPrintArea($zip, $extraRows);
        } finally {
            $zip->close();
        }

        return $outputPath;
    }

    private function insertAdditionalRows(DOMXPath $xpath, int $extraRows): void
    {
        $rowsToShift = [];

        foreach ($xpath->query('//x:sheetData/x:row') as $row) {
            if ((int) $row->getAttribute('r') > self::LAST_TEMPLATE_DATA_ROW) {
                $rowsToShift[] = $row;
            }
        }

        foreach ($rowsToShift as $row) {
            $this->renumberRow($row, (int) $row->getAttribute('r') + $extraRows);
        }

        $templateRow = $xpath->query('//x:sheetData/x:row[@r="'.self::LAST_TEMPLATE_DATA_ROW.'"]')->item(0);
        $firstShiftedRow = $rowsToShift[0] ?? null;

        if (! $templateRow || ! $firstShiftedRow) {
            throw new RuntimeException('Struktur baris pada template laporan tidak valid.');
        }

        for ($i = 1; $i <= $extraRows; $i++) {
            $newRow = $templateRow->cloneNode(true);
            $this->renumberRow($newRow, self::LAST_TEMPLATE_DATA_ROW + $i);
            $firstShiftedRow->parentNode->insertBefore($newRow, $firstShiftedRow);
        }
    }

    private function renumberRow(DOMElement $row, int $newNumber): void
    {
        $row->setAttribute('r', (string) $newNumber);

        foreach ($row->childNodes as $cell) {
            if ($cell instanceof DOMElement && $cell->localName === 'c') {
                $column = preg_replace('/\d+$/', '', $cell->getAttribute('r'));
                $cell->setAttribute('r', $column.$newNumber);
            }
        }
    }

    private function setText(DOMDocument $dom, DOMXPath $xpath, string $coordinate, string $value): void
    {
        $cell = $this->cell($xpath, $coordinate);
        $this->clearCell($cell);
        $cell->setAttribute('t', 'inlineStr');

        $inlineString = $dom->createElementNS($cell->namespaceURI, 'is');
        $text = $dom->createElementNS($cell->namespaceURI, 't');
        $text->setAttribute('xml:space', 'preserve');
        $text->appendChild($dom->createTextNode($value));
        $inlineString->appendChild($text);
        $cell->appendChild($inlineString);
    }

    private function setNumber(DOMDocument $dom, DOMXPath $xpath, string $coordinate, int $value): void
    {
        $cell = $this->cell($xpath, $coordinate);
        $this->clearCell($cell);
        $cell->removeAttribute('t');
        $cell->appendChild($dom->createElementNS($cell->namespaceURI, 'v', (string) $value));
    }

    private function cell(DOMXPath $xpath, string $coordinate): DOMElement
    {
        $cell = $xpath->query('//x:c[@r="'.$coordinate.'"]')->item(0);

        if (! $cell instanceof DOMElement) {
            throw new RuntimeException("Sel {$coordinate} tidak ditemukan pada template laporan.");
        }

        return $cell;
    }

    private function clearCell(DOMElement $cell): void
    {
        while ($cell->firstChild) {
            $cell->removeChild($cell->firstChild);
        }
    }

    private function extendPrintArea(ZipArchive $zip, int $extraRows): void
    {
        if ($extraRows === 0) {
            return;
        }

        $workbook = $zip->getFromName('xl/workbook.xml');

        if ($workbook !== false) {
            $workbook = str_replace('$H$35', '$H$'.(35 + $extraRows), $workbook);
            $zip->addFromString('xl/workbook.xml', $workbook);
        }
    }

    private function createCenteredStyles(ZipArchive $zip): array
    {
        $stylesXml = $zip->getFromName('xl/styles.xml');

        if ($stylesXml === false) {
            throw new RuntimeException('Format tampilan Excel tidak ditemukan pada template.');
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($stylesXml);

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        $cellXfs = $xpath->query('//x:cellXfs')->item(0);
        $styles = $xpath->query('//x:cellXfs/x:xf');
        $mapping = [];

        foreach ([10, 11, 12, 13, 14, 15] as $styleId) {
            $source = $styles->item($styleId);

            if (! $source instanceof DOMElement) {
                continue;
            }

            $centered = $source->cloneNode(true);

            foreach (iterator_to_array($centered->childNodes) as $child) {
                if ($child instanceof DOMElement && $child->localName === 'alignment') {
                    $centered->removeChild($child);
                }
            }

            $alignment = $dom->createElementNS($source->namespaceURI, 'alignment');
            $alignment->setAttribute('horizontal', 'center');
            $alignment->setAttribute('vertical', 'center');
            $centered->appendChild($alignment);
            $centered->setAttribute('applyAlignment', '1');

            $mapping[$styleId] = $cellXfs->childNodes->length;
            $cellXfs->appendChild($centered);
        }

        $cellXfs->setAttribute('count', (string) $cellXfs->childNodes->length);
        $zip->addFromString('xl/styles.xml', $dom->saveXML());

        return $mapping;
    }

    private function applyCenteredStyle(DOMXPath $xpath, string $coordinate, array $centeredStyles): void
    {
        $cell = $this->cell($xpath, $coordinate);
        $styleId = (int) $cell->getAttribute('s');

        if (isset($centeredStyles[$styleId])) {
            $cell->setAttribute('s', (string) $centeredStyles[$styleId]);
        }
    }

    private function templateDataRowCount(): int
    {
        return self::LAST_TEMPLATE_DATA_ROW - self::FIRST_DATA_ROW + 1;
    }
}
