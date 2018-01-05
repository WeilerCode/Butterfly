<?php

namespace Weiler\Butterfly\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Weiler\Butterfly\Models\AdminLog;

class RecordLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userID;            //用户ID
    private $address;           //IP/地址信息
    private $event;             //事件名
    private $time;              //事件发生时间
    private $type;              //时间类型
    private $origin;            //事件发生前的数据
    private $ending;            //时间发生后的数据

    /**
     * Create a new job instance.
     *
     * @param $userID
     * @param $address
     * @param $event
     * @param $time
     * @param string $type
     * @param null $origin
     * @param null $ending
     */
    public function __construct($userID, $address, $event, $time, $type = 'other', $origin = NULL, $ending = NULL)
    {
        $this->userID   =   $userID;
        $this->address  =   $address;
        $this->event    =   $event;
        $this->time     =   $time;
        $this->type     =   $type;
        $this->origin   =   $origin;
        $this->ending   =   $ending;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        AdminLog::create([
            'type'      =>      $this->type,
            'uid'       =>      $this->userID,
            'origin'    =>      $this->origin,
            'ending'    =>      $this->ending,
            'event'     =>      $this->event,
            'ip'        =>      $this->address['ip'] ? $this->address['ip'] : 'unknown',
            'iso_code'  =>      $this->address['iso_code'] ? $this->address['iso_code'] : 'unknown',
            'city'      =>      $this->address['city'] ? $this->address['city'] : 'unknown',
            'created_at'=>      $this->time
        ]);
    }
}
