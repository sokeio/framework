<?php

namespace Sokeio\Models;
use Illuminate\Support\Facades\Cache;

class Permalink extends \Sokeio\Model
{
    public const KEY_CACHE='SOKEIO_KEY_PERMALINK_CACHE';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'key',
        'lang',
        'value',
        'status',
    ];
    public static function boot()
    {
        parent::boot();

        self::saved(function($model){
            // ... code here
            $model->saveCache();
        });

        self::created(function($model){
            // ... code here
            $model->saveCache();
        });


        self::updated(function($model){
            // ... code here
            $model->saveCache();
        });


        self::deleted(function($model){
            // ... code here
            $model->removeCache();
        });
    }
    public static function getPermalinkCache(){
        return Cache::rememberForever(self::KEY_CACHE, function () {
            $all = Permalink::query()->where('status',true)->get();
            $values=[];
            foreach ($all as $item) {
                if(!isset($values[$item->key])) $values[$item->key]=[];
                $values[$item->key][]=[
                    'key'=>$item->key,
                    'lang'=>$item->lang,
                    'value'=>$item->value,
                    'status'=>$item->status,
                ];
            }
            return $values;
        })
        
    }
    private function saveCache(){
        $values = Cache::get(self::KEY_CACHE,[]);
        $values[$item->key]=[
            ...collect($values[$item->key])->where(function($item){
                return $item['key']!=$this->key;
            })->toArray(),
            [
                'key'=>$this->key,
                'lang'=>$this->lang,
                'value'=>$this->value,
                'status'=>$this->status,
            ]
        ]
        Cache::forever(self::KEY_CACHE,$values)
    }
    private function removeCache(){
        Cache::forget(self::KEY_CACHE);
    }
}