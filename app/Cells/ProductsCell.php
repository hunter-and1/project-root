<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class ProductsCell extends Cell
{
    public function list()
    {
        $categoryModel = model('CategoryModel');

        $C['categories'] = $categoryModel->findAll();

        return view('test_cell',$C);
    }
}
