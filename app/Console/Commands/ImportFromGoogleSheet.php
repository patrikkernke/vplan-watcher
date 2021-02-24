<?php

namespace App\Console\Commands;

use App\Models\GoogleSheet\Connector;
use App\Models\GoogleSheet\GuestTalksSheet\Importer;
use App\Models\GoogleSheet\Normalizer;
use Illuminate\Console\Command;
use Storage;

class ImportFromGoogleSheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:googlesheet {--D|dummy} {--f|dummyfilename=googlesheets-response}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from Google Sheet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param \App\Models\GoogleSheet\Connector $connector
     *
     * @return int
     */
    public function handle(Connector $connector)
    {

        $data = $connector
            ->forSpreadsheet('1eqwBV8UuYmWQLmpZrgElr0QL2iiBQetjrHDgikSg1CY')
            ->fromSheet('Gastvorträge', 'C:K')
            ->load();


        if (empty($data)) {
            $this->info('Keine Daten zum importieren vorhanden. Ist das Sheet auch erreichbar?');
            return 0;
        }

        if ($this->option('dummy')) {
            $this->info('Datei mit dem Response-Inhalt (für Tests) wird geschrieben ...');

            $dataAsString = '<?php' . PHP_EOL . 'return ' . var_export($data, true) . ';';
            Storage::disk('testdummies')->put($this->option('dummyfilename') . '.php', $dataAsString);

            $this->info('Datei wurde erstellt.');
        }

        $normalizedData = Normalizer::cleanup($data);
        (new Importer($normalizedData))->import();

        return 0;
    }
}
