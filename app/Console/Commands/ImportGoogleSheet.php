<?php

namespace App\Console\Commands;

use App\GoogleSheet\Connector;
use App\GoogleSheet\GuestTalksSheet\SheetImporter as GuestTalksSheetImporter;
use App\GoogleSheet\Normalizer;
use App\GoogleSheet\ServiceMeetingsSheet\SheetImporter as ServiceMeetingsSheetImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use ReflectionClass;
use Storage;

class ImportGoogleSheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:googlesheet
                            {sheet : Sheetname in Spreadsheet document}
                            {--D|dummy : Store response in dummy file instead of importing}';

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
     * @param Connector $connector
     *
     * @return int
     */
    public function handle(Connector $connector): int
    {
        $this->info('Starting import ...');
        $sheetNameOfImporter = $this->argument('sheet');

        $importers = [
            GuestTalksSheetImporter::SHEETNAME => GuestTalksSheetImporter::class,
            ServiceMeetingsSheetImporter::SHEETNAME => ServiceMeetingsSheetImporter::class,
        ];

        $this->line('- search Importer');
        if (! array_key_exists($sheetNameOfImporter, $importers)) {
            $this->warn('Could not find Importer.');
            $this->line('Check if you provide the correct name of the sheet.');
            $this->line('For following sheets an importer exists:');
            foreach ($importers as $key => $importer) {
                $this->line('- '.$key);
            }
            return 0;
        }

        $importer = $importers[$sheetNameOfImporter];

        $this->line('- connect to Google Sheets and ask for content');
        $data = $connector
            ->forSpreadsheet($importer::SPREADSHEET_ID)
            ->fromSheet($importer::SHEETNAME, $importer::RANGE)
            ->load();

        if (empty($data)) {
            $this->warn('There is no data to import. Check if Google Sheet is available.');
            return 0;
        }

        if ($this->option('dummy')) {
            $this->line('- store file with the response as content');
            $filename = 'googlesheets-response-' . Str::of($importer::SHEETNAME)->slug('-') .'.php';

            $dataAsString = '<?php' . PHP_EOL . 'return ' . var_export($data, true) . ';';
            Storage::disk('testdummies')->put($filename, $dataAsString);

            $this->info('Dummy file ' . $filename . ' was created.');
            return 0;
        }

        $this->line('- normalize response data');
        $normalizedData = Normalizer::cleanup($data);

        $this->line('- import into database');
        (new $importer($normalizedData))
            ->cleanUpDatabase()
            ->import();

        $this->info('Sheet "'.$importer::SHEETNAME.'" was successfully imported.');

        return 0;
    }
}
