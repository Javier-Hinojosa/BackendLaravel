<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class BackendController extends Controller
{
    private $names =[
       1=> ['name'=> "Ana", "age"=> 28],
       2=> ['name'=> "Beto", "age"=> 34],
       3=> ['name'=> "Carlos", "age"=> 25],
    ];

    public function getAll(){
        return response()->json($this->names);
    }

    public function get(int $id = 0) {
        if(isset($this->names[$id])){
            return response()->json($this->names[$id]);
        }
        return response()->json(["message"=> "id not found"], Response::HTTP_NOT_FOUND);
    }

    public function create(Request $request) {
        $person =[
            "id"=> count($this->names) +1,
            "name"=> $request->input("name"),
            "age"=> $request->input("age"),
        ];

        $this ->names[$person["id"]] = $person;
        return response()->json(
            [
                "message"=> "Person has created successfully",
                "person"=> $person
            ], Response::HTTP_CREATED);
        
    }

    public function update(int $id, Request $request) {
         if(isset($this->names[$id])){
            $this->names[$id]= [
                "name"=> $request->input("name", "None"),
                "age"=> $request->input("age", $this->names[$id]["age"]),
            ];

            return response()->json(
                [
                    "message"=> "Person with id $id has updated successfully",
                    "person"=> $this->names[$id]
                ], Response::HTTP_OK);

         }

         return response()->json(["message"=> "id not found"], Response::HTTP_NOT_FOUND); 
    }
    
    public function delete(int $id) {
        if(isset($this->names[$id])){
            unset($this->names[$id]);
            return response()->json(
                [
                    "message"=> "Person with id $id has deleted successfully",
                    "persons"=> $this->names
                ], Response::HTTP_OK);
         }

         return response()->json(["message"=> "id not found"], Response::HTTP_NOT_FOUND);

    }

}
