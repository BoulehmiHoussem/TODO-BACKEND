<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; 
use App\Note;

class NotesController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //check if user authorized to do an action
    public function is_mine($id_note, $id_user){
         $note = Note::where('created_by', $id_user)->where('id', $id_note)->count();
         return (bool)$note;
    }

    public function index(Request $request)
    {

        $note = Note::where('created_by', $request->user()->id)->get();
        return response()->json($note);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
        'task' => 'required|string|max:250',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        


        $note = Note::create([
            'task' => $request->task,
            'created_by' => $request->user()->id
        ]);

        return response(['success'=> "Note added succesfully", 'note' => $note], 200);
        

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */



    // I Know it's not in the task pdf but made it just in case xD 
    public function edit($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
        'task' => 'required|string|max:250',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $note = Note::find($id);
        if(!empty($note))
        {
            $note->task = $request->task;
            $note->save();
            return response(['success'=> "Success"], 200);


        }
        else{
            return response(['errors'=> "Note not found"], 422);

        }

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {   
        if ($this->is_mine($id, $request->user()->id))
        {
            $note = Note::find($id);
            if(!empty($note))
            {
                $note->delete();
                return response(['success'=> "Success"], 200);
            }
            else{
                return response(['errors'=> "Note not found"], 422);
            }
        }
        else
        {
            return response(['errors'=> "You are not allowed to do this action"], 422);
        }
    }
}
