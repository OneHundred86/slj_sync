<?php

namespace App\Console\Commands\Slj;

use Illuminate\Console\Command;
use App\Lib\SljSync as SljSyncLib;
use DB;
use Log;

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
        $this->sync_river();
        $this->sync_shuiku();
        // $this->sync_chaoxi();
        $this->sync_station();
        $this->sync_river_fh();
        $this->sync_shuiku_fh();
        $this->sync_shuiku_xx();
    }

    private function sync_rain(){
        $table = 'rain';
        $from_time = SljSyncLib::get_lastest_modtime($table);
        // dd($from_time);

        echo sprintf('sync table %s from %s', $table, $from_time) . PHP_EOL;
        Log::info("同步 $table 表开始...");

        $sljTable = SljSyncLib::toSljTable($table);
        DB::table($sljTable)->orderBy('gdwr_mddt')
            ->where('gdwr_mddt', '>=', $from_time)
            ->chunk(1000, function($list) use ($table){
                echo sprintf('  post %d条 ', $list->count());

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

                if(SljSyncLib::sync_data($table, $data))
                    echo '成功';
                else
                    echo '失败';

                echo PHP_EOL;
            }
        );

        Log::info("同步 $table 表完成");
    }

    private function sync_river(){
        $table = 'river';
        $from_time = SljSyncLib::get_lastest_modtime($table);
        // dd($from_time);

        echo sprintf('sync table %s from %s', $table, $from_time) . PHP_EOL;
        Log::info("同步 $table 表开始...");

        $sljTable = SljSyncLib::toSljTable($table);
        DB::table($sljTable)->orderBy('gdwr_mddt')
            ->where('gdwr_mddt', '>=', $from_time)
            ->chunk(1000, function($list) use ($table){
                echo sprintf('  post %d条 ', $list->count());

                $data = [];
                foreach($list as $v){
                    $data[] = [
                        'stcd' => $v->stcd,
                        'time' => strtotime($v->tm),
                        'mod_time' => strtotime($v->gdwr_mddt),
                        'z' => $v->z,
                        'q' => $v->q,
                    ];
                }

                if(SljSyncLib::sync_data($table, $data))
                    echo '成功';
                else
                    echo '失败';

                echo PHP_EOL;
            }
        );

        Log::info("同步 $table 表完成");
    }

    private function sync_shuiku(){
        $table = 'shuiku';
        $from_time = SljSyncLib::get_lastest_modtime($table);
        // dd($from_time);

        echo sprintf('sync table %s from %s', $table, $from_time) . PHP_EOL;
        Log::info("同步 $table 表开始...");
        
        $sljTable = SljSyncLib::toSljTable($table);
        DB::table($sljTable)->orderBy('gdwr_mddt')
            ->where('gdwr_mddt', '>=', $from_time)
            ->chunk(1000, function($list) use ($table){
                echo sprintf('  post %d条 ', $list->count());

                $data = [];
                foreach($list as $v){
                    $data[] = [
                        'stcd' => $v->stcd,
                        'time' => strtotime($v->tm),
                        'mod_time' => strtotime($v->gdwr_mddt),
                        'rz' => $v->rz,
                        'blrz' => $v->blrz,
                        'inq' => $v->inq,
                        'otq' => $v->otq,
                    ];
                }

                if(SljSyncLib::sync_data($table, $data))
                    echo '成功';
                else
                    echo '失败';

                echo PHP_EOL;
            }
        );

        Log::info("同步 $table 表完成");
    }

    private function sync_chaoxi(){
        $table = 'chaoxi';
        $from_time = SljSyncLib::get_lastest_modtime($table);
        // dd($from_time);

        echo sprintf('sync table %s from %s', $table, $from_time) . PHP_EOL;
        Log::info("同步 $table 表开始...");
        
        $sljTable = SljSyncLib::toSljTable($table);
        DB::table($sljTable)->orderBy('gdwr_mddt')
            ->where('gdwr_mddt', '>=', $from_time)
            ->chunk(1000, function($list) use ($table){
                echo sprintf('  post %d条 ', $list->count());

                $data = [];
                foreach($list as $v){
                    $data[] = [
                        'stcd' => $v->stcd,
                        'time' => strtotime($v->tm),
                        'mod_time' => strtotime($v->gdwr_mddt),
                        'tdz' => $v->tdz,
                        'airp' => $v->airp,
                        'tdptn' => $v->tdptn,
                    ];
                }

                if(SljSyncLib::sync_data($table, $data))
                    echo '成功';
                else
                    echo '失败';

                echo PHP_EOL;
            }
        );

        Log::info("同步 $table 表完成");
    }

    private function sync_station(){
        $table = 'station';
        $from_time = SljSyncLib::get_lastest_modtime($table);
        // dd($from_time);

        echo sprintf('sync table %s from %s', $table, $from_time) . PHP_EOL;
        Log::info("同步 $table 表开始...");
        
        $sljTable = SljSyncLib::toSljTable($table);
        DB::table($sljTable)->orderBy('gdwr_mddt')
            ->where('gdwr_mddt', '>=', $from_time)
            ->chunk(1000, function($list) use ($table){
                echo sprintf('  post %d条 ', $list->count());

                $data = [];
                foreach($list as $v){
                    $data[] = [
                        'stcd' => $v->stcd,
                        'stnm' => $v->stnm,
                        'stlc' => $v->stlc,
                        'sttp' => $v->sttp,
                        'rvnm' => $v->rvnm,
                        'hnnm' => $v->hnnm,
                        'bsnm' => $v->bsnm,
                        'mod_time' => strtotime($v->gdwr_mddt),
                    ];
                }

                if(SljSyncLib::sync_data($table, $data))
                    echo '成功';
                else
                    echo '失败';

                echo PHP_EOL;
            }
        );

        Log::info("同步 $table 表完成");
    }

    private function sync_river_fh(){
        $table = 'river_fh';
        $from_time = SljSyncLib::get_lastest_modtime($table);
        // dd($from_time);

        echo sprintf('sync table %s from %s', $table, $from_time) . PHP_EOL;
        Log::info("同步 $table 表开始...");
        
        $sljTable = SljSyncLib::toSljTable($table);
        DB::table($sljTable)->orderBy('gdwr_mddt')
            ->where('gdwr_mddt', '>=', $from_time)
            ->chunk(1000, function($list) use ($table){
                echo sprintf('  post %d条 ', $list->count());

                $data = [];
                foreach($list as $v){
                    $data[] = [
                        'stcd' => $v->stcd,
                        'wrz' => $v->wrz,
                        'mod_time' => strtotime($v->gdwr_mddt),
                    ];
                }

                if(SljSyncLib::sync_data($table, $data))
                    echo '成功';
                else
                    echo '失败';

                echo PHP_EOL;
            }
        );

        Log::info("同步 $table 表完成");
    }

    private function sync_shuiku_fh(){
        $table = 'shuiku_fh';
        $from_time = SljSyncLib::get_lastest_modtime($table);
        // dd($from_time);

        echo sprintf('sync table %s from %s', $table, $from_time) . PHP_EOL;
        Log::info("同步 $table 表开始...");
        
        $sljTable = SljSyncLib::toSljTable($table);
        DB::table($sljTable)->orderBy('gdwr_mddt')
            ->where('gdwr_mddt', '>=', $from_time)
            ->chunk(1000, function($list) use ($table){
                echo sprintf('  post %d条 ', $list->count());

                $data = [];
                foreach($list as $v){
                    $data[] = [
                        'stcd' => $v->stcd,
                        'rsvrtp' => $v->rsvrtp,
                        'normz' => $v->normz,
                        'mod_time' => strtotime($v->gdwr_mddt),
                    ];
                }

                if(SljSyncLib::sync_data($table, $data))
                    echo '成功';
                else
                    echo '失败';

                echo PHP_EOL;
            }
        );

        Log::info("同步 $table 表完成");
    }

    // 水库测站汛限水位表
    private function sync_shuiku_xx(){
        $table = 'shuiku_xx';
        $from_time = SljSyncLib::get_lastest_modtime($table);
        // dd($from_time);

        echo sprintf('sync table %s from %s', $table, $from_time) . PHP_EOL;
        Log::info("同步 $table 表开始...");
        
        $sljTable = SljSyncLib::toSljTable($table);
        DB::table($sljTable)->orderBy('gdwr_mddt')
            ->where('gdwr_mddt', '>=', $from_time)
            ->chunk(1000, function($list) use ($table){
                echo sprintf('  post %d条 ', $list->count());

                $data = [];
                foreach($list as $v){
                    $data[] = [
                        'stcd' => $v->stcd,
                        'fsltdz' => $v->fsltdz,
                        'mod_time' => strtotime($v->gdwr_mddt),
                    ];
                }

                if(SljSyncLib::sync_data($table, $data))
                    echo '成功';
                else
                    echo '失败';

                echo PHP_EOL;
            }
        );

        Log::info("同步 $table 表完成");
    }
}






