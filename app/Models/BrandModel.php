<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'brands';
    protected $primaryKey = 'b_id';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'b_created_at';
    protected $updatedField = 'b_updated_at';
    protected $deletedField = 'b_deleted_at';
    protected $protectFields    = false;
    protected $returnType = 'object';

    protected $beforeInsert = ['generateUniqueSlug'];
    protected $beforeUpdate = ['generateUniqueSlug'];


    protected function generateUniqueSlug($data)
    {
        if(! isset($data['data']['b_name']) ) return $data;
        if(isset($data['id']))
        {
            $builder = $this->builder();
            $oldData = $builder->where('b_id',$data['id'])->get()->getRow();

            if($oldData->b_name == $data['data']['b_name']) return $data;            
        }

        helper('text');
        $count = 0;
        $name = strtolower(url_title(convert_accented_characters($data['data']['b_name']), '-', TRUE));
        $slug_name = $name;
        while(true) 
        {
            $builder = $this->builder();
            $builder->where('b_slug', $slug_name);
            $query = $builder->get()->getRow();
            if ($query == false) 
                break;
            $slug_name = $name . '-' . (++$count);
        }
        $data['data']['b_slug'] = $slug_name;
        return $data;
    }
}
