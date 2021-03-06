<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestOracle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:oracle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test oracle';

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
        //
        # $res = \DB::select('select count(*) from stu.ST_PPTN_R');
        
        $res = \DB::table('stu.ST_PPTN_R')->first();
        // dd($res);


        \DB::table('stu.ST_PPTN_R')->orderBy('gdwr_mddt')
            ->where('gdwr_mddt', '>=', date('Y-m-d H:i:s', time() - 3600))
            ->chunk(10, function($list){
                foreach($list as $v){
                    var_dump($v);
                    echo PHP_EOL;
                }
            }
        );
    }
}






