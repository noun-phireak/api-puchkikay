<?php

namespace App\Api\V1\Controllers\CP;
use Illuminate\Http\Request;
use DB; 

use Dingo\Api\Routing\Helpers;

use App\Api\V1\Controllers\ApiController;
use App\CamCyber\FileUpload;
use App\Model\Product\Category;
use App\Model\Product\Product;
use App\Model\Supplier\Supplier;
use App\Model\Review\Review;
use Carbon\Carbon;

class ProductController extends ApiController{
    
    use Helpers;

    function listing(Request $req){

        $data       = Product::select('*')
        ->with([
            'category',
            'supplier', 
        ])
        ->withCount([
            'details as n_of_orders', 
            'details as total_order' => function($query) {
                $query->select(DB::raw("SUM(unit_price)"));
            }, 
            'reviews as n_of_reviews'
            
        ])
        ;
        if($req->param == "mobile"){

            $data       = Product::select('*')
            ->with([
                'category',
                'supplier', 
            ])
            ->withCount([
                'details as n_of_orders', 
                'details as total_order' => function($query) {
                    $query->select(DB::raw("SUM(unit_price)"));
                }, 
                'reviews as n_of_reviews'
                
            ])->get()
            ;
          
            return response()->json($data,200);
        }else{

            if($req->name){
                $data->where('name','like','%'.$req->name.'%');
            }

            if($req->type){
                $data->where('category_id', $req->type);
            }

            $data = $data->orderBy('id', 'desc')->paginate( $req->limit ? $req->limit : 10); 
            return response()->json($data, 200);
        }
        
    }

    function view($id){

        $data       = Product::select('*')
        ->with([
            'category',
            'supplier', 
            'rate'
        ])
        ->withCount([
            'details as n_of_orders', 
            'details as total_order' => function($query) {
                $query->select(DB::raw("SUM(unit_price)"));
            }, 
            'reviews as n_of_reviews'
            
        ])
        ->find($id);
        
        if($data){

            return response()->json([
               'overview' => $data,
               'reviews' => Review::with(['customer', 'customer.user:id,name,phone', 'rate:id,name,color'])->where('product_id', $data->id)->orderBy('id', 'DESC')->get(), 
               'stock' => []
            ], 200);

        }else{
            return response()->json([
                'product'    => $data,
                'message'    => 'Product has been successfully created.'
            ], 400);
        }
        
       
    }

    function getCategory(){
        $data = Category::select("id","name")
        ->with([
            'products:id'
        ])
        ->get();
        return $data;
    }

    function getSupplier(){
        $data = Supplier::select("id","name")
        ->with([
            'products:id'
        ])
        ->get();
        return $data;
    }

    function create(Request $req){
        
        $this->validate($req,[
            'name'              => 'required|max:20',
            'category_id'       => 'required|exists:categories,id',
            'unit_price'        => 'required|max:20',
            'discount'          => 'required|max:20',
            'supplier_id'       => 'required|exists:supplier,id'          
        ],
        [
            'name.required'     => 'Please enter the name.',
            'name.max'          => 'Name cannot be more than 20 characters.',
            'category_id'       => 'Please select correct category.',
            'discount'          => 'Please enter discount.',
            'supplier_id.exists'=> 'Please select supplier.'
        ]);

        $product                = New Product;
        $product->name          = $req->name;
        $product->category_id   = $req->category_id;
        $product->unit_price    = $req->unit_price;
        $product->discount      = $req->discount;
        $product->supplier_id   = $req->supplier_id;

        $date_today = Carbon::today()->format('d').'-'.Carbon::today()->format('M').'-'.Carbon::today()->format('Y'); 

        if($req->image){
       
            $image     = FileUpload::uploadFileV2( $req->image, 'asset/product'.$date_today , '');
            if($image['url']){
                $product->image = $image['url'];
            }
        }
        
        $product ->save();

        return response()->json([
            'product'    => $product,
            'message'    => 'Product has been successfully created.'
        ], 200);
    }

    function update(Request $req, $id = 0){

        $this->validate($req,[
            'name'           => 'required|max:20',
            'category_id'    => 'required|exists:categories,id',
            'unit_price'     => 'required|max:20',
            'discount'       => 'required|max:20',
            'supplier_id'    => 'required|exists:supplier,id'
        ],
        [
            'name.required'  => 'Please enter product name.',
            'name.max'       => 'Name cannot be more than 20 characters.',
            'discount'       => 'Please enter discount.',
            'supplier_id'       => 'Please select supplier' 
        ]);

        $product             = Product::find($id);
        
        if($product){
            $product->name              =$req->input('name');
            $product->category_id       =$req->input('category_id');
            $product->unit_price        =$req->input('unit_price');
            $product->discount          =$req->input('discount');
            $product->supplier_id       = $req->supplier_id;

            $product->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Product has been updated Successfully',
                'product' => $product,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Invalid data.',
            ], 400);
        }

    }

    function delete($id = 0 ){
        $data = Product::find($id);

        if($data){

            $data->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data has been deleted',
            ], 200);

        }else{

            return response()->json([
                'message' => 'Invalid data.',
            ], 400);

        }
    }
}