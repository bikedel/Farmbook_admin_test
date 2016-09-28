@extends('layouts.app')

   <link href="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.css" rel="stylesheet">

<style>
body {
font-family: 'Lato';
}
.tlabel{
color:#000000;
font-weight: 900;
border-style: none;
border-color: Transparent;
background-color: #f2f2f2
}
textarea{
border-style: none;
border-color: Transparent;
padding:0;
overflow: auto;
width: 100%;
-webkit-box-sizing: border-box; /* <=iOS4, <= Android  2.3 */
-moz-box-sizing: border-box; /* FF1+ */
box-sizing: border-box; /* Chrome, IE8, Opera, Safari 5.1*/
}
input[ type=text ]{
border-style: none;
border-color: Transparent;
padding:5;
width: 100%;
}
table td{padding:5px;}
.records{
padding:0px;
color:white;
margin-right: 6px;
font-weight:900;
float:right;
}
.links a{
background-color: orange;
border-color: red;
}
.street{
color:darkblue;
font-weight: 900;
}
.update{
float:right;
}
.backsearch {
float:left;
}
.id {
border-color: Transparent;
border:none;
}
.fa-btn {
margin-right: 6px;
}
.error{
border-color: Transparent;
color:red;
font-weight:900px;
}
.center {
position: absolute;
top: 55px; /* or whatever top you need */
left: 50%;
width: auto;
-webkit-transform: translateX(-50%);
-moz-transform: translateX(-50%);
-ms-transform: translateX(-50%);
-o-transform: translateX(-50%);
transform: translateX(-50%);
}
#dt {
text-indent: -500px;
height: 25px;
width: 200px;
}
</style>
@section('content')



<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
  <!--    <div>
        {{ link_to(url('/home'), 'Back to Search', ['class' => 'btn btn-default']) }}
        {{ link_to(url('/todo'), 'Back to Follow Ups', ['class' => 'btn btn-default']) }}
      </div>   -->
      <div class="panel panel-primary">
        <div class="panel-heading">Add Property

        </div>


        <div class="panel-body">

        @if ( Session::has('flash_message') )
          <div class="alert {{ Session::get('flash_type') }} ">
            <button type="button" class="form-group btn close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p>{{ Session::get('flash_message') }}</p>
          </div>
        @endif
        @if (count($errors) > 0)
          <div class="alert alert-danger">
           <button type="button" class="form-group btn close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
        @endif
          <div class='property'>
            <table class="table-bordered">
              {{ Form::open(array('method' =>'POST','url'=>'/crudupdate')) }}
              <tr>
                <td class='tlabel' width="200" contenteditable='true'>Erf  </td>
                <td width="600" contenteditable='true'><input type="text" name="numErf" value={{old('numErf')}}>
                  @if ($errors->has('numErf')) <p class="alert alert-danger">{{ $errors->first('numErf') }}</p> @endif
                  </td>
              </tr>
              <tr>
                <td class='tlabel' contenteditable='true'>Portion  </td>
                 <td width="600" contenteditable='true'><input type="text" name="Portion" value="{{old('Portion')}}"></td>
              </tr>
              <tr>
                <td class='tlabel' width="120">Street No  </td>
                <td  contenteditable='true'><input type="text" name="StreetNo" value="{{old('StreetNo')}}"></td>
              </tr>
              <tr>
                <td class='tlabel' >Street Name </td>
                @if (isset($streets))
                <td >   {!! Form::select('strStreetName', $streets, [],  ['class' => 'form-control ','style' =>'width:100%']) !!} </td>
                @else
                <td >  </td>
                @endif
              </tr>
              <tr>
                <td class='tlabel' width="100">Complex No  </td>
                 <td width="600" contenteditable='true'><input type="text" name="strComplexNo" value=""></td>
              </tr>
              <tr>
                <td class='tlabel' >Complex Name </td>
                @if (isset($complexes))
                <td class='street '>   {!! Form::select('strComplexName', $complexes, [],  ['class' => 'form-control ','style' =>'width:100%']) !!} </td>
                @else
                <td class='street'>  </td>
                @endif
              </tr>
              <tr>
                <td class='tlabel' >Sq Meters</td>
                 <td width="600" contenteditable='true'><input type="text" name="strSqMeters" value=""></td>
              </tr>
              <tr>
                <td class='tlabel' width="120">Reg Date  </td>
                <td>{{Form::date('regdate')}}Date format yyyy-mm-dd.</td>
              </tr>
              <tr>
                <td class='tlabel' >Amount </td>
                 <td width="600" contenteditable='true'><input type="text" name="strAmount" value=""></td>
              </tr>
              <tr>
                <td class='tlabel' >Bond Amount </td>
                 <td width="600" contenteditable='true'><input type="text" name="strBondAmount" value=""></td>
              </tr>
              <tr>
                <td class='tlabel' width="100">Bond Holder  </td>
                 <td width="600" contenteditable='true'><input type="text" name="strBondHolder" value=""></td>
              </tr>
              <tr>
                <td class='tlabel' >Owner</td>
                 <td width="600" contenteditable='true'><input type="text" name="strOwner" value=""></td>
              </tr>
              <tr>
                <td class='tlabel' >Surname</td>

                 <td width="600" contenteditable='true'><input type="text" name="strSurname" value=""></td>

              </tr>
              <tr>
                <td class='tlabel' >Identity</td>

               <td contenteditable='true'><input type="text" name="strIDNumber" value="{{old('strIDNumber')}}"></td>
              </tr>
              <tr>
                <td class='tlabel' >Home Phone</td>

                <td contenteditable='true'><input type="text" name="strHomePhoneNo" value=""></td>

              </tr>
              <tr>
                <td class='tlabel' >Work Phone</td>

                <td contenteditable='true'><input type="text" name="strWorkPhoneNo" value=""></td>

              </tr>
              <tr>
                <td class='tlabel' >Cell Phone</td>

                <td contenteditable='true'><input type="text" name="strCellPhoneNo" value=""></td>

              </tr>
              <tr>
                <td class='tlabel' >Email</td>

                <td min-width="600" contenteditable='true'><input type="text" name="EMAIL" value=""></td>

              </tr>

              <tr>
                <td class='tlabel' >Notes </td>
                <td ><textarea  rows="6" cols="160" name="newnote"></textarea></td>
              </tr>
              <tr>
                <td class='tlabel' >Followup Date</td>

                <td >

                  {{Form::date('followup')}}Date format yyyy-mm-dd.
                  </td>

                </tr>
              </table>

<!--
              <div class='backsearch'>
                <br>
                {{ link_to(url('/home'), 'Back to Search', ['class' => ' btn btn-default']) }}
                {{ link_to(url('/todo'), 'Back to Follow Ups', ['class' => 'btn btn-default']) }}
              </div>
-->

              <div class=' update'>
                <br>

                {{Form::submit('Add', array('class' => 'btn btn-danger update')) }}
                {{ Form::close() }}
              </div>


            </div>
          </div>
        </div>
        {{ Form::open(array("method" =>"POST","url"=>Session::get('controllerroute'))) }}
        <input type="text" name="selected" class="hidden" value="{{Session::get('search')}}"></input>
        {{ Form::close() }}
      </div>
    </div>
    @endsection
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script >
   // $(document).on("ready page:load", function() {
   // setTimeout(function() { $(".alert").fadeOut(); }, 4000);
  //  });
    </script>
       <script type="text/javascript" src="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.js"></script>
