<?php

namespace App\Http\Controllers;

use App\Complex;
use App\Note;
use App\Owner;
use App\Property;
use App\Street;
use App\User;
use Auth;
use Carbon;
use Illuminate\Http\Request;
use Redirect;
use Session;

class PropertyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //  edit property
    public function edit($id)
    {
        try {

            // set database
            $database = Auth::user()->getDatabase();

            //change database
            $property = new Property;
            $property->changeConnection($database);

            $properties = Property::on($database)->where('id', $id)->paginate(1);
            $properties->load('owner', 'note');

            // get all streets
            $streets = Street::on($database)->orderBy('strStreetName', 'ASC')->lists('strStreetName', 'strStreetName');

            // pass searched string
            $search = $id;

            $count = 1;

        } catch (exception $e) {
            dd($e->getMessage());
        }

        return view('property', compact('properties', 'count', 'search', 'streets'));

    }

    // update the owners data and the notes
    public function update(Request $request)
    {

        // set database
        $database = Auth::user()->getDatabase();

        //change database
        $owner = new Owner;
        $owner->changeConnection($database);

        //change database
        $note = new Note;
        $note->changeConnection($database);

        // get logged in user
        $user = Auth::user()->name;
        $now  = Carbon\Carbon::now('Africa/Cairo')->toDateTimeString();

        // get inputs
        $strKey      = $request->input('strKey');
        $strIdentity = $request->input('strIdentity');
        $strOwners   = $request->input('strOwners');

        $homePhone = $request->input('strHomePhoneNo');
        $workPhone = $request->input('strWorkPhoneNo');
        $cellPhone = $request->input('strCellPhoneNo');
        $email     = $request->input('EMAIL');
        $note      = $request->input('note');
        $newnote   = $request->input('newnote');

        $strStreetNo   = $request->input('strStreetNo');
        $strStreetName = $request->input('strStreetName');

        $followup = $request->input('followup');
        $date     = "";
        if (strLen($followup) > 0) {
            $date = Carbon\Carbon::createFromFormat('Y-m-d', $followup);
        }

        try {

            // update personal details
            //   $owner = Owner::on( $database )->where('strIDNumber', $strIdentity)->update(array('strCellPhoneNo' => $cellPhone,
            //      'strHomePhoneNo' => $homePhone,
            //      'strWorkPhoneNo' => $workPhone,
            //      'EMAIL' => $email,
            //      'updated_at'=> $now
            //      ));

            $properties = Property::on($database)->where('strKey', $strKey)->update(array('strStreetNo' => $strStreetNo, 'numStreetNo' => $strStreetNo, 'strStreetName' => $strStreetName));

//dd($properties);

            //update owner details

            $owner = Owner::on($database)->where('strIDNumber', $strIdentity)->first();

            $owner->strHomePhoneNo = $homePhone;
            $owner->strCellPhoneNo = $cellPhone;
            $owner->strWorkPhoneNo = $workPhone;
            $owner->EMAIL          = $email;

            $owner->save();

            // check if there is a new note
            if (strlen($newnote) > 0) {
                // if a previous note exists add a carrige return and new note
                if (strlen($note) > 0) {
                    $updatednote = ltrim(rtrim($note)) . "\n" . $now . " " . $user . " wrote: " . "\n" . $newnote;
                } else {
                    // add just the new note
                    $updatednote = $now . " " . $user . " wrote: " . "\n" . $newnote;
                }

                // update the note
                $affected = Note::on($database)->where('strKey', $strKey)->update(array('memNotes' => $updatednote, 'followup' => $date, 'updated_at' => $now));
            }

            Note::on($database)->where('strKey', $strKey)->update(array('followup' => $date, 'updated_at' => $now));

        } catch (exception $e) {

            Session::flash('flash_message', 'Error ' . $e->getMessage());
            Session::flash('flash_type', 'alert-danger');

            return Redirect::back();

        }

        Session::flash('flash_message', 'Updated ' . $strOwners . ' at ' . $now);
        Session::flash('flash_type', 'alert-success');

        return Redirect::back();

    }

    //  crud property
    public function crud()
    {

        // set database
        $database = Auth::user()->getDatabase();

        //change database
        $property = new Property;
        $property->changeConnection($database);

        // get all streets
        $streets   = Street::on($database)->orderBy('strStreetName', 'ASC')->lists('strStreetName', 'strStreetName');
        $complexes = Complex::on($database)->orderBy('strComplexName', 'ASC')->lists('strComplexName', 'strComplexName');

        return view('crud_property', compact('streets', 'complexes'));

    }

    public function crudupdate(Request $request)
    {

        // set database
        $database = Auth::user()->getDatabase();

        //change database
        $property = new Property;
        $property->changeConnection($database);

        $numErf   = $request->input('numErf');
        $identity = $request->input('strIDNumber');

        $regdate = $request->input('regdate');
        if (strLen($regdate) > 0) {
            $date = Carbon\Carbon::createFromFormat('Y-m-d', $regdate);
        }

        $rules = [
            //'numErf'   => 'required|integer|unique:' . $database . '.properties,numErf,strIDNumber',
            'numErf'      => 'required|integer',
            'Portion'     => 'required|integer',
            'StreetNo'    => 'required|integer',
            // 'strIDNumber' => 'required|integer|exists:' . $database . '.owners',
            'strIDNumber' => 'required|integer',
        ];

        $messages = [
            'numErf.required'      => 'The Erf field is required',
            'numErf.integer'       => 'The Erf field must be an integer',
            'numErf.unique'        => 'The Erf and Id must be unique',
            'Portion.required'     => 'The Portion field is required',
            'Portion.integer'      => 'The Portion field must be an integer',
            // 'strIDNumber.exists'   => 'The Id does not exist',
            'strIDNumber.required' => 'The Id is required',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            return redirect()->back()->withInput()->withErrors($validator);
        }

        // check if the id number and erf already exist in properties
        $properties = Property::on($database)->where('numErf', $numErf)->where('strIdentity', $identity)->get();

        if ($properties->count()) {

            Session::flash('flash_message', 'The id number is already associated with this erf.');
            Session::flash('flash_type', 'alert-danger');

            //StreetNo = 999;
            //$request->input('StreetNo') = 999;

            return Redirect::back()->withInput();

        }

        // no errors - add property,owner and notes
        Session::flash('flash_message', 'Property has been added.');
        Session::flash('flash_type', 'alert-success');

        return Redirect::back()->withInput();

    }
}
