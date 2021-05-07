<?php


use PHPUnit\Framework\TestCase;
use Roberto\api\M_portal_customreport_ExportedReport;
use Roberto\api\Template;

class TemplateTest extends TestCase
{

    public function testGetReportJsonFromCustomReport()
    {
        # Acá mockeamos el reporte que deserializamos desde el jsonWrapper
        $returnValue = [
            'resto' => 1,
            'alfajor de maizena' => 'hola',
            'merengue' => 2
        ];
        # Construimos nuestro jsonWrapper mockeado
        $jsonWrapper = new RichJsonMock($returnValue);
        # Le pasamos el json wrapper mockeado a nuestro Template
        $template = new Template($jsonWrapper);
        # Mockeamos nuestro reporte
        $report = new M_portal_customreport_ExportedReport();
        # Y ahora, llamamos al getReport de nuestro template pasandolé un $report mockeado
        $expected = false;
        $actual = $template->getReportJsonFromCustomReport($report);

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