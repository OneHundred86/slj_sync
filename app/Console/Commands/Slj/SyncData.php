<?php

namespace App\Console\Commands\Slj;

use Illuminate\Console\Command;
use App\Lib\SljSync as SljSyncLib;
use DB;

class SyncData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slj:sync_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync slj data to remote server';

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
        $this->sync_rain();
    }

    private function sync_rain(){
        $table = 'rain';
        $from_time = SljSyncLib::get_lastest_modtime($table);
        // dd($from_time);

        echo sprintf('sync table %s from %s', $table, $from_time) . PHP_EOL;
        DB::table('stu.ST_PPTN_R')->orderBy('gdwr_mddt')
            ->where('gdwr_mddt', '>=', $from_time)
            ->chunk(100, function($list){
                echo sprintf('  %d条', $list->count()) . PHP_EOL;

                $data = [];
                foreach($list as $v){
                    $data[] = [
                        'stcd' => $v->stcd,
                        'time' => strtotime($v->tm),
                        'mod_time' => strtotime($v->gdwr_mddt),
                        'drp' => $v->drp,
                        'intv' => $v->intv,
                        'pdr' => $v->pdr,
                        'dyp' => $v->dyp,
                    ];
                }

                SljSyncLib::sync_data($table, $data);
            }
        );
    }
}






