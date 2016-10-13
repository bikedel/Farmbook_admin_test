

@extends('layouts.app')



@section('content')

     <!-- select2 -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.css" media="all" rel="stylesheet" type="text/css" />
     <!-- CSS to make Select2 fit in with Bootstrap 3.x -->
     <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2-bootstrap.min.css" />
     <!-- toastr -->
     <link href="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.css" rel="stylesheet">

     <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css rel="stylesheet">

<style type="text/css">

/*
   tr:nth-child(even){background-color: #f2f2f2}
   tr:nth-child(odd){background-color: #ECF2F9}
*/
.fred{

    background-color: lightblue;
    color:white;
}
    table
    {
      table-layout:fixed;
    }

    th {
        background-color: rosybrown;
    }

    table td  {
        padding: 5px;
        text-overflow: ellipsis;
        max-width:1500px;
        overflow:hidden;
        white-space:nowrap;
    }

    .newAgent{
          border-color: black !important;
          color:red;
    }

    .table-bordered td, .table-bordered th{
        border-color: black !important;
    }

    .slategrey-background {
      background-color: #008DB7;
    }

.modal-header {
    background-color: #008DB7;
}

.modal-body {
   background-color: WhiteSmoke ;
}

#custom-search-input {
        margin:0;
        margin-top: 10px;
        padding: 0;
    }

    #custom-search-input .search-query {
        padding-right: 3px;
        padding-right: 4px \9;
        padding-left: 3px;
        padding-left: 4px \9;
        /* IE7-8 doesn't have border-radius, so don't indent the padding */

        margin-bottom: 0;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }

    #custom-search-input button {
        border: 30;
        background: #008DB7;
        /** belows styles are working good */
        padding: 6px 8px;
        margin-top: 1px;
        position: relative;
        left: 2px;
        /* IE7-8 doesn't have border-radius, so don't indent the padding */
        margin-bottom: 0;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        color: white;
    }

    .search-query:focus + button {
        z-index: 8;
    }

pre {
    display: block;
    height:200px;

                font-family: 'Lato';
    white-space: pre;
    margin: 1em 0;
}

</style>



    <div class="container" id="manage-properties">




         <div class="row ">
                <div class="col-lg-6 margin-tb pull-left" >
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="searchVueItems">
                             <div id="custom-search-input" >
                               <div class="input-group ">
                                <input type="text" name="Search" class="form-control" v-model="search.string" placeholder="Search Erf number"/>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-info">
                                         <span class=" glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                                </div>
                             </div>
                    </form>
                </div>
        </div>


        <div class="row ">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2></h2>
                </div>

                 <div class="pull-right">
                    <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#">
                      @{{pagination.total}} Records
                    </button>
                </div>

                <div class="pull-left">
                    <button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#create-item">
                      New Property
                    </button>
                </div>

            </div>
        </div>

        <!-- Item Listing -->
        <div class="slategrey-background " style="overflow-x:auto; border: 12px solid SlateGrey  ;">
            <table class="table table-bordered table-hover">
                <tr>
                    <th width="120px">Action</th>
                    <th width="80px">Erf</th>
                    <th width="80px">Port</th>
                    <th width="80px">Street No</th>
                    <th width="200px">Street Name</th>
                    <th width="100px">Complex No</th>
                    <th width="120px">Complex Name</th>
                    <th width="140px">Identity</th>
                    <th width="300px">Owner</th>
                    <th width="300px">Sellers</th>
                    <th width="120px">Reg Date</th>
                    <th width="800px">Notes</th>
                    <th width="120px">Followup</th>
                </tr>
                <tr v-for="item in items">
                    <td >
                      <button class="btn btn-primary btn-xs" @click.prevent="editItem(item)">Edit</button>
                      <button class="btn btn-danger btn-xs" @click.prevent="deleteItem(item)">Delete</button>
                    </td>
                    <td>@{{ item.numErf }}</td>
                    <td>@{{ item.numPortion}}</td>
                    <td>@{{ item.strStreetNo}}</td>
                    <td>@{{ item.strStreetName}}</td>
                    <td>@{{ item.strComplexNo}}</td>
                    <td>@{{ item.strComplexName}}</td>
                    <td>@{{ item.strIdentity}}</td>
                    <td>@{{ item.strOwners}}</td>
                    <td>@{{ item.strSellers}}</td>
                    <td>@{{ item.dtmRegDate}}</td>
                    <td>@{{ item.note.memNotes}}</td>
                    <td>@{{ item.note.followup}}</td>
                </tr>
            </table>
        </div>





        <!-- Pagination -->
        <nav>
            <ul class="pagination .pagination-sm">
                <li v-if="pagination.current_page > 1">
                    <a href="#" aria-label="Previous"
                       @click.prevent="changePage(pagination.current_page - 1)">
                        <span aria-hidden="true">«</span>
                    </a>
                </li>
                <li v-for="page in pagesNumber"
                    v-bind:class="[ page == isActived ? 'active' : '']">
                    <a href="#"
                       @click.prevent="changePage(page)">@{{ page }}</a>
                </li>
                <li v-if="pagination.current_page < pagination.last_page">
                    <a href="#" aria-label="Next"
                       @click.prevent="changePage(pagination.current_page + 1)">
                        <span aria-hidden="true">»</span>
                    </a>
                </li>
            </ul>
        </nav>


 <!-- <pre>@{{ $data | json }}</pre>   -->

        <!-- Create Item Modal -->

        <div class="modal " id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" id="create-item-modal-header-button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Property</h4>
              </div>
              <div class="modal-body">

                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createItem">


                    @if ( Session::has('flash_message') )
                      <div class="alert {{ Session::get('flash_type') }} ">
                        <button type="button" class="form-group btn close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <p>{{ Session::get('flash_message') }}</p>
                      </div>
                    @endif

                    <div class="form-group">
                       <span v-if="formErrors['test']" class="error text-danger">@{{ formErrors['test'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Surname">numErf:</label>
                        <input type="text" name="numErf" class="form-control" v-model="newItem.numErf" />
                        <span v-if="formErrors['numErf']" class="error text-danger">@{{ formErrors['numErf'] }}</span>

                    </div>

                    <div class="form-group">
                        <label for="Firstname">numPortion:</label>
                        <input type="text" name="numPortion" class="form-control" v-model="newItem.numPortion" />
                        <span v-if="formErrors['numPortion']" class="error text-danger">@{{ formErrors['numPortion'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Firstname">strStreetNo:</label>
                        <input type="text" name="strStreetNo" class="form-control" v-model="newItem.strStreetNo" />
                        <span v-if="formErrors['strStreetNo']" class="error text-danger">@{{ formErrors['strStreetNo'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strStreetName:</label>

                        <select  class="form-control "  v-model="newItem.strStreetName" style="width: 100%;"  >
                               <option v-for="street in streets" "  >
                                    @{{ street.strStreetName }}
                               </option>
                        </select>


                        <span v-if="formErrors['strStreetName']" class="error text-danger">@{{ formErrors['strStreetName'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strSqMeters:</label>
                        <input type="text" name="strSqMeters" class="form-control" v-model="newItem.strSqMeters" />
                        <span v-if="formErrors['strSqMeters']" class="error text-danger">@{{ formErrors['strSqMeters'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strComplexNo:</label>
                        <input type="text" name="strComplexNo" class="form-control" v-model="newItem.strComplexNo" />
                        <span v-if="formErrors['strComplexNo']" class="error text-danger">@{{ formErrors['strComplexNo'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strComplexName:</label>
                        <select  class="form-control "  v-model="newItem.strComplexName" style="width: 100%;"  >
                               <option v-for="complex in complexes" "  >
                                    @{{ complex.strComplexName }}
                               </option>
                        </select>
                        <span v-if="formErrors['strComplexName']" class="error text-danger">@{{ formErrors['strComplexName'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">dtmRegDate:</label>
                        <input type="date" name="dtmRegDate" class="form-control datepicker" v-model="newItem.dtmRegDate" />
                        <span v-if="formErrors['dtmRegDate']" class="error text-danger">@{{ formErrors['dtmRegDate'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strAmount:</label>
                        <input type="text" name="strAmount" class="form-control" v-model="newItem.strAmount" />
                        <span v-if="formErrors['strAmount']" class="error text-danger">@{{ formErrors['strAmount'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strBondHolder:</label>
                        <input type="text" name="strBondHolder" class="form-control" v-model="newItem.strBondHolder" />
                        <span v-if="formErrors['strBondHolder']" class="error text-danger">@{{ formErrors['strBondHolder'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strBondAmount:</label>
                        <input type="text" name="strBondAmount" class="form-control" v-model="newItem.strBondAmount" />
                        <span v-if="formErrors['strBondAmount']" class="error text-danger">@{{ formErrors['strBondAmount'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Firstname">strSellers:</label>
                        <input type="text" name="strSellers" class="form-control" v-model="newItem.strSellers" />
                        <span v-if="formErrors['strSellers']" class="error text-danger">@{{ formErrors['strSellers'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strTitleDeed:</label>
                        <input type="text" name="strTitleDeed" class="form-control" v-model="newItem.strTitleDeed" />
                        <span v-if="formErrors['strTitleDeed']" class="error text-danger">@{{ formErrors['strTitleDeed'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strIdentity:</label>
                        <select  class="form-control "  v-model="newItem.strIdentity" style="width: 100%;"  >
                               <option v-for="owner in owners" "  >
                                    @{{ owner.strIDNumber }}
                               </option>
                        </select>
                        <span v-if="formErrors['strIdentity']" class="error text-danger">@{{ formErrors['strIdentity'] }}</span>
                    </div>

                    <div class="form-group">
                       <span v-if="formErrors['test']" class="error text-danger">@{{ formErrors['test'] }}</span>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>

                    </form>

              </div>
            </div>
          </div>
        </div>

        <!-- Edit Item Modal -->
        <div class="modal fade" id="edit-item" tabindex="-1050" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Property</h4>
              </div>
              <div class="modal-body">

                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateItem(fillItem.id)">

                    <div class="form-group">
                       <span v-if="formErrorsUpdate['test']" class="error text-danger">@{{ formErrorsUpdate['test'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Surname">numErf:</label>
                        <input type="text" name="numErf" class="form-control" v-model="fillItem.numErf" readonly/>
                        <span v-if="formErrorsUpdate['numErf']" class="error text-danger">@{{ formErrorsUpdate['numErf'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Firstname">numPortion:</label>
                        <input type="text" name="numPortion" class="form-control" v-model="fillItem.numPortion" readonly/>
                        <span v-if="formErrorsUpdate['numPortion']" class="error text-danger">@{{ formErrorsUpdate['numPortion'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Firstname">strStreetNo:</label>
                        <input type="text" name="strStreetNo" class="form-control" v-model="fillItem.strStreetNo" />
                        <span v-if="formErrorsUpdate['strStreetNo']" class="error text-danger">@{{ formErrorsUpdate['strStreetNo'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strStreetName:</label>

                        <select  class="form-control "  v-model="fillItem.strStreetName" style="width: 100%;"  >
                               <option v-for="street in streets" "  >
                                    @{{ street.strStreetName }}
                               </option>
                        </select>
                        <span v-if="formErrorsUpdate['strStreetName']" class="error text-danger">@{{ formErrorsUpdate['strStreetName'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strSqMeters:</label>
                        <input type="text" name="strSqMeters" class="form-control" v-model="fillItem.strSqMeters" />
                        <span v-if="formErrorsUpdate['strSqMeters']" class="error text-danger">@{{ formErrorsUpdate['strSqMeters'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strComplexNo:</label>
                        <input type="text" name="strComplexNo" class="form-control" v-model="fillItem.strComplexNo" />
                        <span v-if="formErrorsUpdate['strComplexNo']" class="error text-danger">@{{ formErrorsUpdate['strComplexNo'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strComplexName:</label>
                        <select  class="form-control "  v-model="fillItem.strComplexName" style="width: 100%;"  >
                               <option v-for="complex in complexes" "  >
                                    @{{ complex.strComplexName }}
                               </option>
                        </select>
                        <span v-if="formErrorsUpdate['strComplexName']" class="error text-danger">@{{ formErrorsUpdate['strComplexName'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">dtmRegDate:</label>
                        <input type="date" name="dtmRegDate" class="form-control datepicker" v-model="fillItem.dtmRegDate" />
                        <span v-if="formErrorsUpdate['dtmRegDate']" class="error text-danger">@{{ formErrorsUpdate['dtmRegDate'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strAmount:</label>
                        <input type="text" name="strAmount" class="form-control" v-model="fillItem.strAmount" />
                        <span v-if="formErrorsUpdate['strAmount']" class="error text-danger">@{{ formErrorsUpdate['strAmount'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strBondHolder:</label>
                        <input type="text" name="strBondHolder" class="form-control" v-model="fillItem.strBondHolder" />
                        <span v-if="formErrorsUpdate['strBondHolder']" class="error text-danger">@{{ formErrorsUpdate['strBondHolder'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strBondAmount:</label>
                        <input type="text" name="strBondAmount" class="form-control" v-model="fillItem.strBondAmount" />
                        <span v-if="formErrorsUpdate['strBondAmount']" class="error text-danger">@{{ formErrorsUpdate['strBondAmount'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Firstname">strSellers:</label>
                        <input type="text" name="strSellers" class="form-control" v-model="fillItem.strSellers" />
                        <span v-if="formErrorsUpdate['strSellers']" class="error text-danger">@{{ formErrorsUpdate['strSellers'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strTitleDeed:</label>
                        <input type="text" name="strTitleDeed" class="form-control" v-model="fillItem.strTitleDeed" />
                        <span v-if="formErrorsUpdate['strTitleDeed']" class="error text-danger">@{{ formErrorsUpdate['strTitleDeed'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="Firstname">strIdentity:</label>
                        <select  class="form-control "  v-model="fillItem.strIdentity" style="width: 100%;"  >
                               <option v-for="owner in owners" "  >
                                    @{{ owner.strIDNumber }}
                               </option>
                        </select>
                        <span v-if="formErrorsUpdate['strIdentity']" class="error text-danger">@{{ formErrorsUpdate['strIdentity'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Firstname">note:</label>
                        <textarea  name="note" rows="5" class="form-control" v-model="fillItem.note" ></textarea>
                        <span v-if="formErrorsUpdate['note']" class="error text-danger">@{{ formErrorsUpdate['note'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Firstname">followup:</label>
                        <input type="date" name="dtmRegDate" class="form-control datepicker" v-model="fillItem.followup" />
                        <span v-if="formErrorsUpdate['followup']" class="error text-danger">@{{ formErrorsUpdate['followup'] }}</span>
                    </div>

                    <div class="form-group">
                       <span v-if="formErrorsUpdate['test']" class="error text-danger">@{{ formErrorsUpdate['test'] }}</span>
                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>

                    </form>

              </div>
            </div>
          </div>
        </div>

    </div>

 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

 <!--<script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script> -->


<!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
 <!--<script src="//code.jquery.com/jquery.js"></script>-->

 <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <!--   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>-->




    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.26/vue.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/vue.resource/0.9.3/vue-resource.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.js"></script>

    <script type="text/javascript" src="{!! asset('js/properties.js') !!}"></script>

    @endsection
