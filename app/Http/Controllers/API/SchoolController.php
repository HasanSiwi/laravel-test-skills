<?php

namespace App\Http\Controllers\API;

use App\School;
use App\Http\Helpers\CustomResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class SchoolController extends Controller
{
    private $result = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $schools = School::all();

        return CustomResponse::customResponse(
            $schools,
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
            $logo_name = 'new_logo'.$request->name.'.jpg';
            $request->file('logo')->move(public_path('/img'), $logo_name);

            $photo_url = url('/img/'.$logo_name);
            $validated['logo'] = $photo_url;

            School::create($validated);

            DB::commit();

            return CustomResponse::customResponse(
                $validated,
                CustomResponse::$successCode,
                'the school has been created successfully'
            );
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return CustomResponse::customResponse(
                null,
                CustomResponse::$successCode,
                'the school has NOT been created successfully'
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
        $schools = School::find($id);

        if ($schools)
        {
            return CustomResponse::customResponse(
                $schools,
                CustomResponse::$successCode,
                'successfully got the school'
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
        $school = School::find($id);

        if ($school == null)
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
            School::where('id', $id)->delete();

            $this->result = $school->delete();
            DB::commit();
            return CustomResponse::customResponse(
                null,
                CustomResponse::$successCode,
                "the school has been deleted successfully"
            );
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return CustomResponse::customResponse(
                null,
                CustomResponse::$errorCode,
                "the school has NOT been deleted successfully"
            );
        }
    }

    public function rules()
    {
        return [
            'name'      => 'required|max:255',
            'email'     => 'required|email',
            'logo'      => 'required|image|dimensions:min_width=100,min_height=100',
            'website'   => '',
        ];
    }
}
