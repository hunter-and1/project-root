<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'c_id';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'c_created_at';
    protected $updatedField = 'c_updated_at';
    protected $deletedField = 'c_deleted_at';
    protected $protectFields    = false;
    protected $returnType = 'object';

    protected $beforeInsert = ['generateUniqueSlug','parentCategoryNull'];
    protected $beforeUpdate = ['generateUniqueSlug','parentCategoryNull'];


    protected function parentCategoryNull(array $data)
    {
        if(isset($data['data']['c_parent_id']) ) 
        {
            $data['data']['c_parent_id'] = (intval($data['data']['c_parent_id']) == 0)?null:intval($data['data']['c_parent_id']);
        }
        return $data;
    }

    protected function generateUniqueSlug($data)
    {
        if(! isset($data['data']['c_name']) ) return $data;
        if(isset($data['id']))
        {
            $builder = $this->builder();
            $oldData = $builder->where('c_id',$data['id'])->get()->getRow();

            if($oldData->c_name == $data['data']['c_name']) return $data;            
        }

        helper('text');
        $count = 0;
        $name = strtolower(url_title(convert_accented_characters($data['data']['c_name']), '-', TRUE));
        $slug_name = $name;
        while(true) 
        {
            $builder = $this->builder();
            $builder->where('c_slug', $slug_name);
            $query = $builder->get()->getRow();
            if ($query == false) 
                break;
            $slug_name = $name . '-' . (++$count);
        }
        $data['data']['c_slug'] = $slug_name;
        return $data;
    }


    public function getParentCategory($categoryId)
    {
        return $this->find($categoryId);
    }

    public function getChildCategories($parentId = null)
    {
        if ($parentId === null) {
            return $this->where('c_parent_id', null)->findAll();
        }
        return $this->where('c_parent_id', $parentId)->findAll();
    }
}
