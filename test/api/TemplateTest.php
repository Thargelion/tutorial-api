<?php


use PHPUnit\Framework\TestCase;
use Roberto\api\M_portal_customreport_ExportedReport;
use Roberto\api\Template;

class TemplateTest extends TestCase
{

    /**
     * Si no el reporte no tiene 'Data'
     * HasMore va a ser Falso
     */
    public function testGetReportJsonSetsHasMoreToFalseWhenNoDataGiven()
    {
        # Acá mockeamos el reporte que deserializamos desde el jsonWrapper
        $reporteDeserializadoMockeado = [
            'resto' => 1,
            'alfajor de maizena' => 'hola',
            'merengue' => 2
        ];
        $expected = false;
        # Construimos nuestro jsonWrapper mockeado
        $jsonWrapper = new RichJsonMock($reporteDeserializadoMockeado);
        # Le pasamos el json wrapper mockeado a nuestro Template
        $template = new Template($jsonWrapper);
        # Mockeamos nuestro reporte serializado
        $reporteSerializado = new M_portal_customreport_ExportedReport();
        # Y ahora, llamamos al getReport de nuestro template pasandolé un $report mockeado
        $actual = $template->getReportJsonFromCustomReport($reporteSerializado);

        # Cuando el data viene nulo, no tiene que haber ruptura y hasMore tiene que volver falso.
        $this->assertEquals($expected, $actual['hasMore']);
    }

    /**
     * Given Data Array Size Bigger Than recordsPerPage
     * When getReportJsonFromCustomReport called with Given
     * Then Actual['hasMore'] is True
     */
    public function testGetReportJson_SetsHasMoreToTrue_WhenDataIsBigger_ThanRecordsPerPage()
    {
        # Acá mockeamos el reporte que deserializamos desde el jsonWrapper
        $reporteMockeado = [
            'data' => ['1', '2', '3', '4'],
            'resto' => 1,
            'alfajor de maizena' => 'hola',
            'merengue' => 2
        ];
        $expected = true;
        $givenRecordsPerPage = 2;
        # Construimos nuestro jsonWrapper mockeado
        $jsonWrapper = new RichJsonMock($reporteMockeado);
        # Le pasamos el json wrapper mockeado a nuestro Template
        $template = new Template($jsonWrapper);
        # Mockeamos nuestro reporte
        $report = new M_portal_customreport_ExportedReport();
        # Y ahora, llamamos al getReport de nuestro template pasandolé un $report mockeado
        $actual = $template->getReportJsonFromCustomReport($report, $givenRecordsPerPage);

        # Cuando el data viene nulo, no tiene que haber ruptura y hasMore tiene que volver falso.
        $this->assertEquals($expected, $actual['hasMore']);
    }

}

class Report
{
    public $data = [];
    public $hasMore = false;

    public function hasMore($recordsPerPage): bool
    {
        return $recordsPerPage > sizeof($this->data);
    }
}

# Nuestro RichJson va a devolver lo que le carguemos como valor de retorno. Esto nos permite, en defintiva, mockear
# qué cosa va a devolver nuestro deserializador que es lo que realmente nos interesa
class RichJsonMock
{
    public $return = [];

    public function __construct($returnValue)
    {
        $this->return = $returnValue;
    }

    public function unserialize(int $content)
    {
        return $this->return;
    }
}