<?php 

namespace App\Api\V1\Controllers\CP;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Dingo\Api\Routing\Helpers;
use Tymon\JWTAuth\JWTAuth;

use App\Api\V1\Controllers\ApiController;
use App\CamCyber\FileUpload;
use App\Model\Product\Category;
use App\Model\Product\Product;
use Carbon\Carbon;

class CategoryController extends ApiController{

    use Helpers;
    function inCategory($id){
        $data  = Category::where([
            'id'   => $id
        ])
        ->with(['products'])
        ->get();
        return $data;
    }

    function listing(){
        
        $data           = Category::select('*')
        ->withCount(['products'])
        ->orderBy('id', 'DESC')
        ->get();
        return $data;
    }

    function create(Request $req){

        $this->validate($req,[
            'name'             =>'required|max:20',
        ],
        [
        'name.required'        =>'Please enter the name.',
        'name.max'             =>'Total canot be more than 20 characters',
        ]);
        $date_today = Carbon::now();
        $product_category          = New Category;
        $product_category->name    = $req->name;

        if($req->image){

            $image = FileUpload::uploadFileV2($req->image,'asset/product'.$date_today,'');

            if($image['url']){

                $product_category->image = $image['url'];

            }

        }

        $product_category ->save();

        return response()->json([
            'product_category'  =>$product_category,
            'message'           =>'Category has been successfully created.'
        ],200);
        
    }

    function createsubcategory(Request $req){

        $this->validate($req,[
            'name'             =>'required|max:20', 
        ],
        [
        'name.required'        =>'Please enter the name.',
        'name.max'             =>'Total canot be more than 20 characters',
        ]);

        $subcategory                 = New Category;
        $subcategory->name           = $req->name;
        $subcategory->parent_id      = $req->parent_id;

        $subcategory ->save();

        return response()->json([
            'product_category'  =>$subcategory,
            'message'           =>'Category has been successfully created.'
        ],200);
        
    }

    function update(Request $req, $id=0){

        $this->validate($req,[
            'name'           =>'required|max:20',
        ],[
            'name.required' => 'please Enter the name',
            'name.max'      => 'Name cannot be more than 20 characters.',
        ]);

        $product_category   = Category::find($id);
        $date = Carbon::now();
        if($product_category){
            $product_category->name       =$req->input('name');
            
            if($req->image){

                $image = FileUpload::uploadFileV2($req->image, 'asset/category'.$date, '');

                $product_category->image = $image['url'];
            }

            $product_category->save();

            return response()->json([
                'status'     =>'success',
                'message'    =>'Product has been succesfully updated',
                'product_category' => $product_category,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Invalid data.',
            ],400);
        }
    }

    function delete($id = 0 ){
        $data   = Category::find($id);

        if ($data){
            $data-> delete();
            return response()->json([
                'status'=> 'success',
                'message'=> 'Data has been deleted',
            ], 200);
        }else{
            return response()->json([
                'message'=> 'Invalid data.'
            ],400);
        }
    }
}