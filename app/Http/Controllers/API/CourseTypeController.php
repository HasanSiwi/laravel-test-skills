<?php

namespace App\Http\Controllers\API;

use App\CourseType;
use App\Http\Helpers\CustomResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CourseTypeController extends Controller
{
    private $result = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $course_types = CourseType::all();

        return CustomResponse::customResponse(
            $course_types,
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
            CourseType::create($validated);

            DB::commit();

            return CustomResponse::customResponse(
                $validated,
                CustomResponse::$successCode,
                'the Course Type has been created successfully'
            );
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return CustomResponse::customResponse(
                null,
                CustomResponse::$successCode,
                'the Course Type has NOT been created successfully'
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
        $course_types = CourseType::find($id);

        if ($course_types)
        {
            return CustomResponse::customResponse(
                $course_types,
                CustomResponse::$successCode,
                'successfully got the Course Type'
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
        $course_type = CourseType::find($id);

        if ($course_type == null)
        {
            return CustomResponse::customResponse(
                null,
                CustomResponse::$notFound,
                'the school is not found!'
            );
        }
        DB::beginTransaction();
        try
        {
            CourseType::where('id', $id)->delete();

            $this->result = $course_type->delete();
            DB::commit();
            return CustomResponse::customResponse(
                null,
                CustomResponse::$successCode,
                "the Course Type has been deleted successfully"
            );
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return CustomResponse::customResponse(
                null,
                CustomResponse::$errorCode,
                "the Course Type has NOT been deleted successfully"
            );
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
        ];
    }
}
