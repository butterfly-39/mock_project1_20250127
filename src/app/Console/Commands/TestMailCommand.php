<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\TransactionCompletedMail;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取引完了メールのテスト送信';

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
        $item = new Item();
        $item->name = 'テスト商品';
        $item->price = 1000;
        
        $seller = new User();
        $seller->name = 'テスト出品者';
        $seller->email = 'seller@example.com';
        
        $buyer = new User();
        $buyer->name = 'テスト購入者';
        $buyer->email = 'buyer@example.com';
        
        try {
            Mail::to($seller->email)->send(new TransactionCompletedMail($item, $seller, $buyer));
            
            $this->info('✅ メール送信テストが成功しました！');
            $this->info('送信先: ' . $seller->email);
            
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ メール送信テストが失敗しました: ' . $e->getMessage());
            return 1;
        }
    }
}
