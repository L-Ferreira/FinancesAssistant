<?php

namespace App\Http\Controllers;

use App\Account;
use App\Document;
use App\Http\Requests\DocumentRequest;
use App\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use PhpParser\Comment\Doc;


class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function document(Movement $movement){

        $count  = Movement::query()->where('id','=',$movement->id)->count();


        $documents = Movement::join('documents','documents.id','=','movements.document_id')
            ->select('documents.original_name','documents.description')
            ->where('documents.id','=',$movement->document_id)->get();


        return view('documents.documents_form',compact('movement','documents'));
    }

    public function associateDocument(DocumentRequest $request, Movement $movement){

        $account = $movement->account->user;


        if(Auth::user()->id != $account->id){
            return response(view('errors.403'),403);
        }

//        if($movement->document_id){
//           return $this->replace($request,$movement);
//        }
        $data = $request->validated();

        if($request->hasFile('document_file') && $request->file('document_file')->isValid()){
            $type = $data['document_file']->getClientOriginalExtension();
            $document = new \App\Document();
            $document->type =$type;
            $document->original_name = $request->file('document_file')->getClientOriginalName();
            //$filename = $request->file('document_file')->getClientOriginalName();
            $document->description = $data['document_description'];

            if ($movement->document_id != null) {
                Storage::delete('documents/'.$movement->account_id . $movement->id);
            }
            Storage::putFileAs('documents/'.$movement->account_id, $request->file('document_file'), $movement->id.'.'.$type);

            $document->save();
        }



        $movement->document_id = $document->id;

        $movement->save();


        return redirect()
            ->route('account.movement', $movement->account_id)
            ->with('success', 'Document associate');
    }





    public function viewDocument(Document $document){

        $id = $document->id;

        $movement = Movement::query()->where('document_id','=',$id)->first();



        $account = Account::query()->where('id','=',$movement->account_id)->first();
        $movements = Movement::query()->where('account_id','=',$account->id)->get();
        if(Auth::user()->id == $account->owner_id || Auth::user()->isAssociateOf($account->owner_id)){
            return Storage::download('documents/'.$movement->account_id.'/'.$movement->id.'.'.$document->type,$document->original_name);
        }

    $error = "Forbidden";

        return Response::make(view('movements.movements_index',compact('movements','error')),403);
    }

//    public function destroy(Document $document){
//
//        dd("cona");
//        $movement = Movement::query()->where('document_id','=',$document->id)->first();
//        $account = Account::query()->where('id','=',$movement->account_id)->first();
//        $movements = Movement::query()->where('account_id','=',$account->id)->get();
////
////        $movement->document_id = NULL;
////
////        $this->authorize('delete',$document);
////
////        $count = Movement::query()->where('account_id','=',$account->id)->count();
////
////        if(!Auth::user()->admin || Auth::user()->admin) {
////            if ($count == 0 && is_null($account->last_movement_date)) {
////
////                $account->delete();
////            } else {
////                return response(view('errors.403'), 403);
////            }
////        }else {
////            return response(view('errors.403'),403);
////
////        }
////
////        if(Auth::user()->id == $account->owner_id){
////            Storage::delete('documents/'.$movement->account_id . $movement->id);
////        }
//
//
//
//        return view('movements.movements_index',compact('movements'));
//    }

}
