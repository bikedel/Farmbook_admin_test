<?php

namespace App\Http\Controllers;

use App\Complex;
use App\farmbook;
use App\Http\Controllers\Controller;
use App\Note;
use App\Owner;
use App\Property;
use App\Street;
use App\User;
use Auth;
use Carbon;
use Illuminate\Http\Request;
use Storage;

class VuePropertyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function manageVue()
    {
        return view('manage-properties');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // set database
        $database = Auth::user()->getDatabase();

        //change database
        $property = new Property;
        $property->changeConnection($database);

        $items = Property::on($database)->latest()->paginate(10);

        $items->load('owner', 'note');

        //  $streets = Street::on($database)->select('id', 'strStreetName')->get();

        //  $complexes = Complex::on($database)->select('id', 'strComplexName')->get();

        //  $owners = Owner::on($database)->select('id', 'strIDNumber')->get();

        //array_unshift($users, ['name' => 'Select ']);
        $response = [
            'pagination' => [
                'total'        => $items->total(),
                'per_page'     => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'from'         => $items->firstItem(),
                'to'           => $items->lastItem(),
            ],
            'data'       => $items,
            //   'streets'    => $streets,
            //   'complexes'  => $complexes,
            //  'owners'     => $owners,
        ];

        return response()->json($response);
    }

    /**
     * Get the select dropdown info
     *
     * @return \Illuminate\Http\Response
     */
    public function selects(Request $request)
    {

        // set database
        $database = Auth::user()->getDatabase();

        //change database
        $property = new Property;
        $property->changeConnection($database);

        $streets = Street::on($database)->select('id', 'strStreetName')->get();

        $complexes = Complex::on($database)->select('id', 'strComplexName')->get();

        $owners = Owner::on($database)->select('id', 'strIDNumber')->get();

        //array_unshift($users, ['name' => 'Select ']);
        $response = [
            'streets'   => $streets,
            'complexes' => $complexes,
            'owners'    => $owners,
        ];

        return response()->json($response);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $search)
    {

        // set database
        $database = Auth::user()->getDatabase();

        //change database
        $property = new Property;
        $property->changeConnection($database);

        $items = Property::on($database)->where('numErf', 'like', $search . '%')->latest()->paginate(10);

        $items->load('owner', 'note');

        //array_unshift($users, ['name' => 'Select ']);
        $response = [
            'pagination' => [
                'total'        => $items->total(),
                'per_page'     => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'from'         => $items->firstItem(),
                'to'           => $items->lastItem(),
            ],
            'data'       => $items,

        ];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // set database
        $database = Auth::user()->getDatabase();

        //change database
        $property = new Property;
        $property->changeConnection($database);

        $rules = array(
            'numErf'         => 'required',
            'numPortion'     => 'required',
            'strStreetNo'    => 'required',
            'strStreetName'  => 'required',
            'strSqMeters'    => 'Numeric',
            'strComplexNo'   => 'required',
            'strComplexName' => 'required',
            'dtmRegDate'     => 'date',
            'strAmount'      => 'Numeric',
            //'strBondHolder'  => 'required',
            'strBondAmount'  => 'Numeric',
            //'strIdentity'    => 'required|digits:13',
            'strIdentity'    => 'required',
            //'strSellers'     => 'required',
            //'strTitleDeed'   => 'required',
        );

        $messsages = array(
            'numErf.required'         => 'This field is required',
            'numPortion.required'     => 'This field is required',
            'strStreetNo.required'    => 'This field is required',
            'strStreetName.required'  => 'This field is required',
            'strSqMeters.required'    => 'This field is required',
            'strSqMeters.numeric'     => 'This field must be numeric',
            'strComplexNo.required'   => 'This field is required',
            'strComplexName.required' => 'This field is required',
            'dtmRegDate.required'     => 'This field is required',
            'dtmRegDate.date'         => 'This field must be a valid date YYYY/MM/DD',
            'strAmount.required'      => 'This field is required',
            'strAmount.numeric'       => 'This field must be numeric',
            'strBondHolder.required'  => 'This field is required',
            'strBondAmount.required'  => 'This field is required',
            'strBondAmount.numeric'   => 'This field must be numeric',
            //'strIdentity'    => 'required|digits:13',
            'strIdentity.required'    => 'This field is required',
            'strSellers.required'     => 'This field is required',
            'strTitleDeed.required'   => 'This field is required',
        );

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            // send back to the page with the input data and errors
            //$request->merge(array('strStreetNo' => '999'));
            return response()->json($validator->errors()->getMessages(), 422);

        }

        // check if the id number and erf already exist in properties
        $numErf     = $request->input('numErf');
        $identity   = $request->input('strIdentity');
        $properties = Property::on($database)->where('numErf', $numErf)->where('strIdentity', $identity)->get();

        if ($properties->count()) {
            return response()->json(['test' => 'The Id Number is already associated with this Erf.'], 422);
        }

        // add new record
        //$property = Property::on($database)->create($request->all());
        //$property = new Property;
        // $property->changeConnection($database);
        //change database
        $property = new Property;
        $property->changeConnection($database);
        //  $edit = Property::on($database)->find($id);

        //get suburb
        $suburb = Property::on($database)->first();

        // remove id from the form request
        $tosave = $request->except(['id']);

        // update strOwners
        $ownerId = $request->input('strIdentity');
        $owner   = Owner::on($database)->where('strIDNumber', '=', $ownerId)->first();

        // update poperties owner with owner name ? name or first and last
        if ($owner->count()) {

            //  $tosave['strOwners'] = $owner->NAME;
            $tosave['strOwners'] = $owner->strFirstName . ' ' . $owner->strSurname;
        }

        // update request inputs and insert property
        $tosave['strSuburb']    = $suburb->strSuburb;
        $tosave['numStreetNo']  = $request->input('strStreetNo');
        $tosave['numComplexNo'] = $request->input('strComplexNo');
        $tosave['strKey']       = $request->input('numErf') . '-' . $request->input('numPortion');
        $tosave['created_at']   = \Carbon\Carbon::now()->toDateTimeString();
        $tosave['updated_at']   = \Carbon\Carbon::now()->toDateTimeString();
        $property               = Property::on($database)->insert($tosave);

        // create notes record
        $note['strKey']     = $request->input('numErf') . '-' . $request->input('numPortion');
        $note['numErf']     = $request->input('numErf');
        $note['created_at'] = \Carbon\Carbon::now()->toDateTimeString();

        // check if notes already exists - if not then add
        $exists = Note::on($database)->where('strKey', $note['strKey'])->first();
        if (!count($exists)) {
            $notes = Note::on($database)->insert($note);
        }

        //log
        $id          = Auth::user()->id;
        $currentuser = User::find($id);
        $oldfarmbook = $currentuser->farmbook;
        $email       = $currentuser->email;
        $olddbname   = Farmbook::select('name')->where('id', $oldfarmbook)->first();
        $action      = 'New Property';
        $comment     = $olddbname->name . " - Key: " . $tosave['strKey'] . " Id Number: " . $tosave['strIdentity'];
        $append      = \Carbon\Carbon::now('Africa/Johannesburg')->toDateTimeString() . ',          ' . trim($email) . ',          ' . $action . ',' . $comment;
        Storage::append('logfile.txt', $append);

        return response()->json($property);
        //return response()->json(['test' => 'all data ok so far.'], 422);
        //return Redirect::back()->withInput()->withErrors(['test' => 'Your error message.'], 400);

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

        // set database
        $database = Auth::user()->getDatabase();

        //change database
        $property = new Property;
        $property->changeConnection($database);

        $rules = array(
            'numErf'         => 'required',
            'numPortion'     => 'required',
            'strStreetNo'    => 'required',
            'strStreetName'  => 'required',
            'strSqMeters'    => 'Numeric',
            'strComplexNo'   => 'required',
            'strComplexName' => 'required',
            'dtmRegDate'     => 'date',
            'strAmount'      => 'Numeric',

            //'strBondHolder'  => 'required',
            'strBondAmount'  => 'Numeric',
            //'strIdentity'    => 'required|digits:13',
            'strIdentity'    => 'required',
            //'strSellers'     => 'required',
            //'strTitleDeed'   => 'required',
        );

        $messsages = array(
            'numErf.required'         => 'This field is required',
            'numPortion.required'     => 'This field is required',
            'strStreetNo.required'    => 'This field is required',
            'strStreetName.required'  => 'This field is required',
            'strSqMeters.numeric'     => 'This field must be numeric',
            'strSqMeters.required'    => 'This field is required',
            'strComplexNo.required'   => 'This field is required',
            'strComplexName.required' => 'This field is required',
            'dtmRegDate.required'     => 'This field is required',
            'dtmRegDate.date'         => 'This field must be a valid date YYYY/MM/DD',
            'strAmount.required'      => 'This field is required',
            'strAmount.numeric'       => 'This field must be numeric',
            'strBondHolder.required'  => 'This field is required',
            'strBondAmount.required'  => 'This field is required',
            'strBondAmount.numeric'   => 'This field must be numeric',
            //'strIdentity'    => 'required|digits:13',
            'strIdentity.required'    => 'This field is required',
            'strSellers.required'     => 'This field is required',
            'strTitleDeed.required'   => 'This field is required',
        );

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            // send back to the page with the input data and errors

            return response()->json($validator->errors()->getMessages(), 422);

        }

        $edit = Property::on($database)->find($id);

        // check if the id number and erf already exist in properties - excluding this id
        $numErf     = $request->input('numErf');
        $identity   = $request->input('strIdentity');
        $properties = Property::on($database)->where('numErf', $numErf)->where('strIdentity', $identity)->where('id', '!=', $id)->get();

        if ($properties->count()) {
            return response()->json(['test' => 'The Id Number is already associated with this Erf.'], 422);
        }

        // remove id from the form request
        $tosave = $request->except(['id', 'strOwners', 'followup', 'note']);
        //  $tosave = $request->except(['strOwners']);
        //  $tosave = $request->except(['followup']);
        //  $tosave = $request->except(['note']);

        //find owner and update strOwners - using name from owners table
        $ownerId = $request->input('strIdentity');
        $owner   = Owner::on($database)->where('strIDNumber', '=', $ownerId)->first();

        if ($owner->count()) {
            // $edit->strOwners = $owner->NAME;
            $edit->strOwners = $owner->strFirstName . ' ' . $owner->strSurname;
            $edit->save();
        }

        // update properties
        // set the numeric fields as they are not in the form
        $tosave['numStreetNo']  = $request->input('strStreetNo');
        $tosave['numComplexNo'] = $request->input('strComplexNo');
        $edit->update($tosave);

        // update all properties with the same key - excluding the owner
        $editall = Property::on($database)->where('strKey', $edit->strKey)->get();
        $counter = 0;
        foreach ($editall as $row) {
            $counter             = $counter + 1;
            $row->strStreetNo    = $edit->strStreetNo;
            $row->numStreetNo    = $edit->numStreetNo;
            $row->strStreetName  = $edit->strStreetName;
            $row->strSqMeters    = $edit->strSqMeters;
            $row->strComplexNo   = $edit->strComplexNo;
            $row->numComplexNo   = $edit->numComplexNo;
            $row->strComplexName = $edit->strComplexName;
            $row->dtmRegDate     = $edit->dtmRegDate;
            $row->strAmount      = $edit->getOriginal('strAmount');
            $row->strBondHolder  = $edit->strBondHolder;
            $row->strBondAmount  = $edit->getOriginal('strBondAmount');
            $row->strSellers     = $edit->strSellers;
            $row->strTitleDeed   = $edit->strTitleDeed;

            $row->update();
        }

        //update notes
        $strKey = $request->input('strKey');
        $note   = Note::on($database)->where('strKey', '=', $strKey)->first();
        if ($note->count()) {
            // $edit->strOwners = $owner->NAME;
            $note->memNotes = $request->input('note');
            $note->followup = $request->input('followup');
            $note->save();
        }

        //log
        $id          = Auth::user()->id;
        $currentuser = User::find($id);
        $oldfarmbook = $currentuser->farmbook;
        $email       = $currentuser->email;
        $olddbname   = Farmbook::select('name')->where('id', $oldfarmbook)->first();
        $action      = 'Update Property';
        $comment     = $olddbname->name . " - Key: " . $tosave['strKey'] . " Id Number: " . $tosave['strIdentity'];
        $append      = \Carbon\Carbon::now('Africa/Johannesburg')->toDateTimeString() . ',          ' . trim($email) . ',          ' . $action . ',' . $comment;
        Storage::append('logfile.txt', $append);

        return response()->json($edit);
        //  return response()->json(['test' => 'all data ok so far.'], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // set database
        $database = Auth::user()->getDatabase();

        //change database
        $property = new Property;
        $property->changeConnection($database);

        $prop = Property::on($database)->find($id);

        $key    = $prop->strKey;
        $propid = $prop->strIdentity;

        $prop->delete();

        //log
        $id          = Auth::user()->id;
        $currentuser = User::find($id);
        $oldfarmbook = $currentuser->farmbook;
        $email       = $currentuser->email;
        $olddbname   = Farmbook::select('name')->where('id', $oldfarmbook)->first();
        $action      = 'Delete Property';
        $comment     = $olddbname->name . " - Key: " . $key . " Id Number: " . $propid;
        $append      = \Carbon\Carbon::now('Africa/Johannesburg')->toDateTimeString() . ',          ' . trim($email) . ',          ' . $action . ',' . $comment;
        Storage::append('logfile.txt', $append);

        return response()->json(['done']);
    }

    public function export()
    {
/*
$now    = \Carbon\Carbon::now();
$buyers = Buyer::select('*')->get();

//$buyers->load('users');

Excel::create('Buyers_' . $now, function ($excel) use ($buyers) {

$excel->setTitle('Exported Buyers ');
$excel->setCreator('Buyers')->setCompany('Sothebys');
$excel->setDescription('Buyers');

$excel->sheet('Sheet 1', function ($sheet) use ($buyers) {
$sheet->fromArray($buyers, null, 'A1', true, true);

// Add as very first
//   $sheet->prependRow(2, array(
//       '', '',
//   ));

// Sets all borders
//$sheet->setAllBorders('thin');

// Set border for cells
//$sheet->setBorder('A1', 'thin');

// Set border for range
//$sheet->setBorder('A1:E1', 'thin');
// Freeze first row
$sheet->freezeFirstRow();

// Set height for a single row
$sheet->setHeight(1, 20);

$sheet->cells('A1:U1', function ($cells) {

// manipulate the range of cells
// Set black background
$cells->setBackground('#008DB7');

// Set with font color
$cells->setFontColor('#ffffff');

// Set font
$cells->setFont(array(
'family' => 'Verdana',
'size'   => '12',
'bold'   => false,

));

//$cells->setBorder('solid', 'solid', 'solid', 'solid');

});

});
})->export('xls');
 */
    }
}
