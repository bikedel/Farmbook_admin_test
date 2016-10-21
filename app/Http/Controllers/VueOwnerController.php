<?php

namespace App\Http\Controllers;

use App\Complex;
use App\Farmbook;
use App\Http\Controllers\Controller;
use App\Owner;
use App\Property;
use App\Street;
use App\User;
use Auth;
use Carbon;
use Illuminate\Http\Request;
use Storage;

class VueOwnerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function manageVue()
    {
        return view('manage-owners');
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
        $owner = new Property;
        $owner->changeConnection($database);

        $items = Owner::on($database)->latest()->paginate(10);

        //$items->load('owner', 'note');

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
        $owner = new Property;
        $owner->changeConnection($database);

        $items = Owner::on($database)->where('strIDNumber', 'like', $search . '%')->latest()->paginate(10);

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
        $owner = new Owner;
        $owner->changeConnection($database);

        $rules = array(
            'strIDNumber'    => 'required|unique:' . $database . '.owners',
            // 'TITLE'          => 'required',
            // 'INITIALS'       => 'required',
            // 'NAME'           => 'required',
            // 'strSurname'     => 'required',
            // 'strFirstName'   => 'required',
            'strHomePhoneNo' => 'Numeric',
            'strWorkPhoneNo' => 'Numeric',
            'strCellPhoneNo' => 'Numeric',
            // 'EMAIL'          => 'required|email',

        );

        $messsages = array(
            'strIDNumber.required'    => 'This field is required',
            'strIDNumber.unique'      => 'This field must be unique',
            'TITLE.required'          => 'This field is required',
            'INITIALS.required'       => 'This field is required',
            'NAME.required'           => 'This field is required',
            'strSurname.required'     => 'This field is required',
            'strFirstName.required'   => 'This field is required',
            'strHomePhoneNo.required' => 'This field is required',
            'strWorkPhoneNo.required' => 'This field is required',
            'strCellPhoneNo.required' => 'This field is required',
            'strHomePhoneNo.numeric'  => 'This field must be numeric',
            'strWorkPhoneNo.numeric'  => 'This field must be numeric',
            'strCellPhoneNo.numeric'  => 'This field must be numeric',
            'EMAIL.required'          => 'This field is required',
            'EMAIL.email'             => 'This field must be a valid email address',
        );

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            // send back to the page with the input data and errors
            return response()->json($validator->errors()->getMessages(), 422);

        }

        // add new record
        //$property = Property::on($database)->create($request->all());
        //$property = new Property;
        // $property->changeConnection($database);
        //change database
        $owner = new Owner;
        $owner->changeConnection($database);
        //  $edit = Property::on($database)->find($id);

        // remove id from the form request
        $tosave               = $request->except(['id']);
        $tosave['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
        $tosave['updated_at'] = \Carbon\Carbon::now()->toDateTimeString();
        // save new owner
        $owner = Owner::on($database)->insert($tosave);

        //log
        $id          = Auth::user()->id;
        $currentuser = User::find($id);
        $oldfarmbook = $currentuser->farmbook;
        $email       = $currentuser->email;
        $olddbname   = Farmbook::select('name')->where('id', $oldfarmbook)->first();
        $action      = 'New Owner';
        $comment     = $olddbname->name . " - Id Number: " . $tosave['strIDNumber'];
        $append      = \Carbon\Carbon::now('Africa/Johannesburg')->toDateTimeString() . ',          ' . trim($email) . ',          ' . $action . ',' . $comment;
        Storage::append('logfile.txt', $append);

        return response()->json($owner);

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
        $owner = new Owner;
        $owner->changeConnection($database);

        $rules = array(
            'strIDNumber'    => 'required',
            'TITLE'          => 'required',
            'INITIALS'       => 'required',
            'NAME'           => 'required',
            'strSurname'     => 'required',
            'strFirstName'   => 'required',
            'strHomePhoneNo' => 'required|Numeric',
            'strWorkPhoneNo' => 'required|Numeric',
            'strCellPhoneNo' => 'required|Numeric',
            'EMAIL'          => 'required|email',

        );

        $messsages = array(
            'strIDNumber.required'    => 'This field is required',
            'TITLE.required'          => 'This field is required',
            'INITIALS.required'       => 'This field is required',
            'NAME.required'           => 'This field is required',
            'strSurname.required'     => 'This field is required',
            'strFirstName.required'   => 'This field is required',
            'strHomePhoneNo.required' => 'This field is required',
            'strWorkPhoneNo.required' => 'This field is required',
            'strCellPhoneNo.required' => 'This field is required',
            'strHomePhoneNo.numeric'  => 'This field must be numeric',
            'strWorkPhoneNo.numeric'  => 'This field must be numeric',
            'strCellPhoneNo.numeric'  => 'This field must be numeric',
            'EMAIL.required'          => 'This field is required',
            'EMAIL.email'             => 'This field must be a valid email address',
        );

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messsages);

        if ($validator->fails()) {
            // send back to the page with the input data and errors

            return response()->json($validator->errors()->getMessages(), 422);

        }

        $edit = Owner::on($database)->find($id);

        // remove id from the form request
        $tosave = $request->except(['id']);

        $edit->update($tosave);

        //log
        $id          = Auth::user()->id;
        $currentuser = User::find($id);
        $oldfarmbook = $currentuser->farmbook;
        $email       = $currentuser->email;
        $olddbname   = Farmbook::select('name')->where('id', $oldfarmbook)->first();
        $action      = 'Update Owner';
        $comment     = $olddbname->name . " - Id Number: " . $tosave['strIDNumber'];
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
        $owner = new Property;
        $owner->changeConnection($database);

        $owner = Owner::on($database)->find($id);

        $idnumber = $owner->strIDNumber;

        $property = Property::on($database)->where('strIdentity', $idnumber)->get();

        if ($property->count()) {

            return response()->json(['test' => 'Cant delete as it is in properties'], 422);
        }

        $owner->delete();

        //log
        $id          = Auth::user()->id;
        $currentuser = User::find($id);
        $oldfarmbook = $currentuser->farmbook;
        $email       = $currentuser->email;
        $olddbname   = Farmbook::select('name')->where('id', $oldfarmbook)->first();
        $action      = 'Delete Owner';
        $comment     = $olddbname->name . " - Id Number: " . $idnumber;
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
