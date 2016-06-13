<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Merchant;
use App\Statistical;
class UpdateStatical extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:statical';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update statical of merchant';

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
        
        //update 
        $merchant_info = Merchant::where('active', 1)->get();
    
        if($merchant_info) {

            foreach ($merchant_info as $key => $value) {
                #code...
                // Statistical::create(['merchants_id'=>$value['id'], 'year'=>date('Y')]);
                $messages = [];
                $feedbacks = [];
                for($i = 5; $i >= 0; $i--){
                    $messages['Tháng '.(6 - $i)] = getCountMessageInMonthOfMerchant($value['id'], $i);
                    Statistical::where('merchants_id',$value['id'])->update(['messages' => json_encode($messages)]);
                    $feedbacks['Tháng '.(6 - $i)] = getCountFeedbackInMonthOfMerchant($value['id'], $i);
                    Statistical::where('merchants_id',$value['id'])->update(['feedbacks' => json_encode($feedbacks)]);
                }
                // die();
            }
        }
    }
}
