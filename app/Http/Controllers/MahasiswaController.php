<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use DataTables;
use \PDF;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mahasiswa');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addStudent(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'student_name'=>'required|unique:mahasiswas',
            'student_major'=>'required',
        ]);
        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
            $student = new Mahasiswa();
            $student->student_name = $request->student_name;
            $student->student_major = $request->student_major;
            $query = $student->save();

            if(!$query){
                return response()->json(['code'=>0,'msg'=>'Something went wrong']);
            }else{
                return response()->json(['code'=>1,'msg'=>'New Student has been successfully saved!']);
            }
        }
    }
    public function studentsList(Request $request){
        if($request->ajax()){
            // $getData = DB::table('mahasiswas')->select('id', 'student_name', 'student_major');
            // console.log($getData);
            // return DataTables::of($getData)->make(true);
            $students = Mahasiswa::all();
            // info($students);
            
            return DataTables::of($students)
                ->addIndexColumn()
                ->addColumn('actions', function($row){
                    return '<div class="btn-group">
                                <a href="/downloadPdf" class="btn btn-sm btn-info" data-id="'.$row['id'].'" id="pdfStudentBtn">Download Letter</a>
                                <button class="btn btn-sm btn-primary" data-id="'.$row['id'].'" id="editStudentBtn">Update</button>
                                <button class="btn btn-sm btn-danger" data-id="'.$row['id'].'" id="deleteStudentBtn">Delete</button>
                            </div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    // Students details
    public function studentsDetails(Request $request){
        $student_id = $request->student_id;
        $studentDetails = Mahasiswa::find($student_id);
        return response()->json(['details'=>$studentDetails]);
    }

    // Update Student Details
    public function updateStudentsDetails(Request $request){
        $student_id = $request->cid;

        $validator = \Validator::make($request->all(),[
            'student_name'=>'required|unique:mahasiswas,student_name,'.$student_id,
            'student_major'=>'required'
        ]);

        if(!$validator->passes()){
               return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{
             
            $student = Mahasiswa::find($student_id);
            $student->student_name = $request->student_name;
            $student->student_major = $request->student_major;
            $query = $student->save();

            if($query){
                return response()->json(['code'=>1, 'msg'=>'Student Details have Been updated']);
            }else{
                return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
            }
        }
    }

    // Delete student
    public function deleteStudent(Request $request){
        $student_id = $request->student_id;
        $query = Mahasiswa::find($student_id)->delete();

        if($query){
            return response()->json(['code'=>1, 'msg'=>'Student has been deleted']);
        }else{
            return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
        }
    }

    public function downloadPdf(){
        $pdf = PDF::loadView('letter');
        // return $pdf->download('letter.pdf');
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
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
