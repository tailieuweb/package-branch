<?php namespace Foostart\Branch\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use URL,
    Route,
    Redirect;
use Foostart\Branch\Models\Branch;

class BranchFrontController extends Controller
{
    public $data = array();
    public function __construct() {
        $this->obj_item = new Branch(array('perPage' => 5));
    }
    public function index(Request $request)
    {

        //params
        $params = [];
       //get companies
        $items = $this->obj_item->selectItems($params);
        $this->data = array(
            'request' => $request,
            'items' => $items
        );
        return view('package-branch::front.branch-items', $this->data);
    }

    public function show($slug){

        $item = $this->obj_item->where('branch_slug', $slug)->first();
        $this->data = array(
            'slug' => $slug,
            'item' => $item
        );
        return view('package-branch::front.branch-item', $this->data);
    }

    public function search(Request $request){
        $keyword=  $request->key;
        $items = $this->obj_item->where('branch_name', 'LIKE', '%' . $keyword . '%')->paginate(10);
        $this->data = array(
            'request' => $request,
            'items' => $items
        );
        return view('package-branch::front.branch-search', $this->data);
    }
}