<?php


namespace Roberto\api;


class Template
{
    private $richJsonWrapper;

    public function __construct($jsonWrapper)
    {
        $this->richJsonWrapper = $jsonWrapper;
    }


    /** @return array|null */
    public function getReportJsonFromCustomReport(
        M_portal_customreport_ExportedReport $report,
        int $recordsPerPage = null
    )
    {
        # Acá estamos haciendo un parche sobre un problema de modelado. Ya que si la información del reporte está en
        # data, data a lo sumo tendrá0 que venir vacío pero nunca nulo

        # jsonReport, que es el array/mapa con el que trabajamos se obtiene desde un des serializador.
        # Serializar un objeto es convertir un objeto cualquiera (un string, un array, un object) en binario.
        # También se le dice "serializar" al convertirlo en un objeto de tipo JSON que puede ser un string.
        # Esto se debe a que el serializador y des-serializador (o unserializador) va a interpretar a eso que reciba
        # como un json.
        # Espera que reciba algo así:
        # '{
        #         "hola": "soy un JSON"
        # }';
        # El deserializador que usamos, toma como base al getContents del reporte que es un objeto de tipo
        # M_portal_customreport_ExportedReport
        # Entonces, hacemos un mock de M_portal_customreport_ExportedReport propio que devuelva algo para pasarle a
        # nuestro richJsonWrapper.
        # Nuestro RichJsonWrapper está mockeado en el test

        $jsonReport = $this->richJsonWrapper->unserialize($report->getContents());
        if (!isset($jsonReport['data'])) {
            $jsonReport['data'] = [];
        }

        $jsonReport['hasMore'] = $recordsPerPage && sizeof($jsonReport['data']) > $recordsPerPage;
        if ($jsonReport['hasMore']) {
            array_pop($jsonReport['data']);
        }

        return $jsonReport;
    }

    /** @return array|null */
    public function getReportJsonFromCustomReportDos(
        M_portal_customreport_ExportedReport $report,
        int $recordsPerPage = null
    )
    {
        $dataSize = 0;
        $jsonReport = $this->richJsonWrapper->unserialize($report->getContents());

        if (isset($jsonReport['data']) && $jsonReport !== null) {
            $dataSize = sizeof($jsonReport['data']);
        }
        $jsonReport['hasMore'] = $recordsPerPage && $dataSize > $recordsPerPage;

        if ($jsonReport['hasMore']) {
            array_pop($jsonReport['data']);
        }

        return $jsonReport;
    }


    /** @return array|null */
    public function getReportJsonFromCustomReportVersionTres(
        M_portal_customreport_ExportedReport $report,
        int $recordsPerPage = null
    )
    {
        $jsonReport = $this->richJsonWrapper->unserialize($report->getContents());
        $jsonReport['hasMore'] = $recordsPerPage && sizeof($jsonReport['data']) > $recordsPerPage;
        if ($jsonReport->hasMore($recordsPerPage)) {
            array_pop($jsonReport['data']);
        }
        return $jsonReport;
    }
}

class M_portal_customreport_ExportedReport
{
    public function getContents()
    {
        return 1;
    }
}