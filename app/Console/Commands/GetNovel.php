<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GetNovel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'novel:get {url=https://www.qu.la/book/} {start=1} {stop=1} {skip=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取小说';

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
        $client = new Client();

        $baseUrl = trim($this->argument('url'), '/') . '/';
        $start = intval($this->argument('start'));
        $stop = intval($this->argument('stop'));
        $skip = intval($this->argument('skip'));

        for ($i = $start; $i <= $stop; $i += $skip) {
            $t = 0;
            $response = $client->get($baseUrl. $i, [
                'progress' => function ($dt, $dn, $ut, $un) use ($i, &$t) {
                    if ($dn >= $t) {
                        $this->output->write("\r<info>正在获取编号为 $i 的小说：$dn/". ($dt == 0 ? '未知' : $dt) ."</info>");
                        $t = $dn;
                    }
                }
            ]);

            $path = 'novel/' . parse_url($baseUrl, PHP_URL_HOST)  .'/' . intval($i / 1000) . '/' . $i . '.html';

            if (!Storage::put($path, $response->getBody()->getContents())) {
                $this->warn(PHP_EOL . "编号为 $i 的小说保存失败，路径 $path");
            } else {
                $this->info(PHP_EOL . '小说保存成功');
            }
        }
    }
}
