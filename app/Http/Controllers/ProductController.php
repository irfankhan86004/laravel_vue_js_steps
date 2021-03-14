<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        //$products = Product::all()->toArray();
		
        //return array_reverse($products);     
		//return $request->ajax() ? Product::paginate(2) : abort(404);		
		
		$key = \Request::get('q');
		$type = \Request::get('type');
		
		if (!empty($key)) {
			
			if (!empty($type)) { 
				$unit = Product::where($type,'LIKE',"%{$key}%")->paginate(2);	
			} else {
				$unit = Product::where('name','LIKE',"%{$key}%")->paginate(2);	
			}
			
			
			return response()->json([ 'unit' => $unit ],Response::HTTP_OK);
			
		} else {
			$unit = Product::paginate(2);
		}
        
		return $unit;
		
    	
		
    }
	
	public function search_unit_by_key()
    {
    	$key = \Request::get('q');
        $unit = Product::where('name','LIKE',"%{$key}%")
                                    ->get();

    	return response()->json([ 'unit' => $unit ],Response::HTTP_OK);
    }

    public function store(Request $request)
    {	
		$this->validate($request, [
            'name' => 'required',
            'detail' => 'required'
        ]);
		
        $product = new Product([
            'name' => $request->input('name'),
            'detail' => $request->input('detail')
        ]);
        $product->save();

        return response()->json('Product created!');
    }

    public function show($id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);
        $product->update($request->all());

        return response()->json('Product updated!');
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response()->json('Product deleted!');
    }
}