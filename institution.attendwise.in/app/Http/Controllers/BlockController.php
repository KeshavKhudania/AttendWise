<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class BlockController extends Controller
{
    function index(Request $req){
        
        $data = [
            "blocks"=>Block::all(),
            // "adminal_id"=>,
            "title"=>"Manage Blocks"
        ];
        return view("block.index", $data);
    }
    function formView(Request $req){
        if ($req->segment(3)) {
            $details = Block::find(Crypt::decrypt($req->segment(3)));
            if (!$details) {
                return abort(404,"Page Not Found");
            }
            $fields = $details;
            $data = [
                "title"=>"Edit Block",
                "type"=>"EDIT",
                "action"=>route("institution.blocks.update", ["id"=>$req->segment(3)]),
                "block"=>$fields,
            ];
        }else{
            $fields = [];
            
            foreach (Schema::getColumnListing("institution_blocks") as $value) {
                $fields[$value] = null;
            }
            $data = [
                "title"=>"Add Block",
                "type"=>"ADD",
                "action"=>route("institution.blocks.create"),
                "block"=>$fields,
            ];
        }
        return view("block.form", $data);
    }
    function form(Request $req){
        if ($req->segment(3)) {
            $data = [];
            foreach (Schema::getColumnListing('institution_blocks') as $value) {
                if (in_array($value, ['id','created_at','updated_at','deleted_at'])) continue;
                if ($req->has($value)) {
                 if ($value == 'latlng' && $req->post($value) != null) {
                        $data[$value] = serialize($req->post($value));
                    }else{
                        $data[$value] = $req->input($value);
                    }   
                }
            }
            if(Block::find(Crypt::decrypt($req->segment(3)))->update($data)){
                return json_encode(["msg"=>"Block Updated.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }else{
            $data = [];
            // $data['ulid'] = Str::ulid();
            // return ;
            foreach (Schema::getColumnListing('institution_blocks') as $value) {
                if (in_array($value, ['id','created_at','updated_at','deleted_at'])) continue;
                if ($req->has($value)) {
                    if ($value == 'latlng' && $req->post($value) != null) {
                        $data[$value] = serialize($req->post($value));
                    }else{
                        $data[$value] = $req->input($value);
                    }
                }
            }
            // return;

            $block = Block::create($data); // uses casts to encrypt
            if($block){
                return json_encode(["msg"=>"Block Created.", "color"=>"success", "icon"=>"check-circle"]);
            }
            return abort("403", json_encode(["msg"=>"Something went wrong.", "color"=>"danger", "icon"=>"exclamation-circle"]));
        }
    }
    function delete(Request $req){
        try {
            if ($id = Crypt::decrypt($req->segment(3))) {
                Block::findOrFail($id)->delete();
                return json_encode(["msg"=>"Block Deleted.", "color"=>"success", "icon"=>"check-circle"]);
            }
        } catch (\Throwable $th) {
            return abort(401);
        }
    }
}
