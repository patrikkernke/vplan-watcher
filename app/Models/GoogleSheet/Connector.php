<?php


namespace App\Models\GoogleSheet;


use Google_Client;
use Google_Service_Sheets;
use Illuminate\Support\Collection;

class Connector
{
    /**
     * @var \Google_Client
     */
    private $client;

    /**
     * @var \Google_Service_Sheets
     */
    private $service;

    /**
     * @var string
     */
    private $spreadsheetId;

    /**
     * @var string
     */
    private $range;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName('VPlan Manager');
        $this->client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $this->client->setAccessType('offline');
        $this->client->setAuthConfig(storage_path('google/vplan-manager-6a24f2cf97fe.json'));

        $this->service = new Google_Service_Sheets($this->client);

        $this->spreadsheetId = null;
        $this->range = null;
    }

    /**
     * @return array
     */
    public function load():array
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $this->range);
        $values = $response->getValues();

        return empty($values)
            ? []
            : $values;
    }

    /**
     * @param $spreadsheetId
     *
     * @return $this
     */
    public function forSpreadsheet($spreadsheetId):Connector
    {
        $this->spreadsheetId = $spreadsheetId;

        return $this;
    }

    public function fromSheet($sheetName = null, $cellsRange = null):Connector
    {
        $this->range = empty($cellsRange)
            ? $sheetName
            : $sheetName ."!". $cellsRange;

        return $this;
    }
}
