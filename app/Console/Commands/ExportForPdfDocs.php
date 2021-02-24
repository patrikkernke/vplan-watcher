<?php

namespace App\Console\Commands;

use App\Actions\CreatePdfDataSource;
use Illuminate\Console\Command;

class ExportForPdfDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:source';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update data source for pdf generation (JSON file)';

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
     * @return int
     */
    public function handle()
    {
        CreatePdfDataSource::weekendMeetings();

        $this->info('Data source for WeekendMeetings was created.');

        return 0;
    }
}
