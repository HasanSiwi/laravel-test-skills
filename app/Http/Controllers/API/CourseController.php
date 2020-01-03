<?php

namespace App\Http\Controllers\API;

use App\Course;
use App\Http\Helpers\CustomResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CourseController extends Controller
{
    private $result = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $courses = Course::all();

        return CustomResponse::customResponse(
            $courses,
            CustomResponse::$successCode,
            'successfully got the result'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails())
        {
            return CustomResponse::customResponse(
                $validator->messages(),
                CustomResponse::$unprocessableEntity
            );
        }

        $validated = $validator->validated();

        DB::beginTransaction();
        try
        {
            Course::create($validated);

            DB::commit();

            return CustomResponse::customResponse(
                $validated,
                CustomResponse::$successCode,
                'the Course has been created successfully'
            );
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return CustomResponse::customResponse(
                null,
                CustomResponse::$successCode,
                'the Course has NOT been created successfully'
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $courses = Course::find($id);

        if ($courses)
        {
            return CustomResponse::customResponse(
                $courses,
                CustomResponse::$successCode,
                'successfully got the Course'
            );
        }

        return CustomResponse::customResponse(null, CustomResponse::$notFound,'nothing found' );
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $course = Course::find($id);

        if ($course == null)
        {
            return CustomResponse::customResponse(
                null,
                CustomResponse::$notFound,
                'the Course is not found!'
            );
        }
        DB::beginTransaction();
        try
        {
            Course::where('id', $id)->delete();

            $this->result = $course->delete();
            DB::commit();
            return CustomResponse::customResponse(
                null,
                CustomResponse::$successCode,
                "the Course has been deleted successfully"
            );
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return CustomResponse::customResponse(
                null,
                CustomResponse::$errorCode,
                "the Course has NOT been deleted successfully"
            );
        }
    }

    public function rules()
    {
        return [
            'name'           => 'required|max:255',
            'campus_id'      => 'required',
            'course_type_id' => 'required',
            'price'          => 'required',
        ];
    }
}
