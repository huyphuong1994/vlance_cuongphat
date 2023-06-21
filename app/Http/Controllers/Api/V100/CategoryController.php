<?php

namespace App\Http\Controllers\Api\V100;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\CategoryWithoutChildResource;
use App\Http\Resources\FeatureCategoryWithChild;
use App\Models\Category;
use App\Repositories\Interfaces\Admin\Product\CategoryInterface;
use App\Traits\ApiReturnFormatTrait;
use App\Traits\SlugTrait;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    use ApiReturnFormatTrait;  use SlugTrait;

    public $category;

    public function __construct(CategoryInterface $category)
    {
        $this->category = $category;
    }

    public function setSlugAutoCategory()
    {
        try {
            $data = CategoryResource::collection($this->category->mobileCategory(100000));

            $updateQuery = '';

            foreach ($data as $item) {
                $id = $item->id;
                $slug = $this->getSlug($item->title);

                $updateQuery .= "UPDATE categories SET slug = '$slug' WHERE id = $id;";
            }

            DB::unprepared($updateQuery);

            return $this->responseWithSuccess(__('Data Successfully Found'), $data, 200);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage(), [], null);
        }
    }

    public function allCategory()
    {
        try {
            $featured = [
                'title' => 'Featured Category',
                'icon' => 'mdi mdi-crowd',
                'banner' => settingHelper('featured_category_banner') ? getFileLink('240x100', settingHelper('featured_category_banner')['images']) : static_asset('images/default/130x95_no_bg.png'),
                'featured_sub_categories' => CategoryWithoutChildResource::collection(Category::where('is_featured', 1)->where('status', 1)->paginate(get_pagination('api_paginate'))),
            ];
            $data = [
                'featured_category' => $featured,
                'categories' => CategoryResource::collection($this->category->mobileCategory(get_pagination('api_paginate')))
            ];
            return $this->responseWithSuccess(__('Data Successfully Found'), $data, 200);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage(), [], null);
        }
    }

    public function featureCategory()
    {
        try {
            $data = [
                'featured_categories' => CategoryWithoutChildResource::collection(Category::where('is_featured', 1)->where('status', 1)->paginate(get_pagination('api_paginate'))),
            ];
            return $this->responseWithSuccess(__('Data Successfully Found'), $data, 200);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage(), [], null);
        }
    }
}
