<?php

namespace App\Console\Commands;

use GuzzleHttp\Psr7\Response;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AnalyseNovel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novel:analyse {url=https://www.qu.la/book/} {start=1} {stop=1} {skip=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $host = parse_url($this->argument('url'), PHP_URL_HOST);
        $start = intval($this->argument('start'));
        $stop = intval($this->argument('stop'));
        $skip = intval($this->argument('skip'));

        for ($i = $start; $i <= $stop; $i += $skip) {
            $path = 'novel/' . $host  .'/' . intval($i / 1000) . '/' . $i . '.html';
        }
    }
}
