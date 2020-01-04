<?php

namespace App\Http\Controllers\API;

use App\Campus;
use App\School;
use App\Mail\CampusEntered;
use Illuminate\Http\Request;
use App\Http\Helpers\CustomResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CampusController extends Controller
{
    private $result = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $Campuses = Campus::all();

        return CustomResponse::customResponse(
            $Campuses,
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
            Campus::create($validated);

            DB::commit();

            $this->sendEmail($request->all());

            return CustomResponse::customResponse(
                $validated,
                CustomResponse::$successCode,
                'the campus has been created successfully'
            );
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return CustomResponse::customResponse(
                null,
                CustomResponse::$successCode,
                'the campus has NOT been created successfully, '.$e->getMessage()
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
        $campuses = Campus::find($id);

        if ($campuses)
        {
            return CustomResponse::customResponse(
                $campuses,
                CustomResponse::$successCode,
                'successfully got the campus'
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
        $campus = Campus::find($id);

        if ($campus == null)
        {
            return CustomResponse::customResponse(
                null,
                CustomResponse::$notFound,
                'the campus is not found!'
            );
        }
        DB::beginTransaction();
        try
        {
            Campus::where('id', $id)->delete();
            $this->result = $campus->delete();
            DB::commit();
            return CustomResponse::customResponse(
                null,
                CustomResponse::$successCode,
                "the campus has been deleted successfully"
            );
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return CustomResponse::customResponse(
                null,
                CustomResponse::$errorCode,
                "the campus has NOT been deleted successfully"
            );
        }
    }

    public function sendEmail($data)
    {
        $school = School::where('id', $data['school_id'])->first();

        return Mail::to($school->email)->send(new CampusEntered($data));
    }

    public function rules()
    {
        return [
            'name'      => 'required|max:255',
            'school_id' => 'required',
            'email'     => 'email',
            'phone'     => 'max:10',
            'address'   => 'max:255',
        ];
    }
}
