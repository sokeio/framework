<?php

namespace Sokeio\Components\Concerns;

use Sokeio\Form;

trait WithEditiInTable
{
    public Form $data;

    public function LoadDataRow($id){
        $query = $this->getQuery();
        if ($id) {
            $query =  $query->where('id',$id);
            $data = $query->first();
            if (!$data) return abort(404);
            if (method_exists($this, 'loadDataRowBefore')) {
                call_user_func([$this, 'loadDataRowBefore'], $data,$id);
            }
            foreach($data->toArray() as $key=>$value){
                // data_set($this, $column->getFormFieldEncode(), $column->getValueDefault());
                $this->data->{$key} = $value;
            }
            if (method_exists($this, 'loadDataRowAfter')) {
                call_user_func([$this, 'loadDataRowAfter'], $data,$id);
            }
        }
    }
    public function SaveDataRow($id){

    }
}