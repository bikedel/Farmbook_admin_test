

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

input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0px 1000px #ffffff inset!important;
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



    <div class="container" id="manage-owners">




         <div class="row ">
                <div class="col-lg-6 margin-tb pull-left" >
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="searchVueItems">
                             <div id="custom-search-input" >
                               <div class="input-group ">
                                <input type="text" name="Search" class="form-control" v-model="search.string" placeholder="Search ID Number"/>
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
                    <button type="button" class="btn btn-success btn-md" @click.prevent="createForms">
                      New Owner
                    </button>
                </div>

            </div>
        </div>

        <!-- Item Listing -->
        <div class="slategrey-background " style="overflow-x:auto; border: 12px solid SlateGrey  ;">
            <table class="table table-bordered table-hover">
                <tr>
                    <th width="120px">Action</th>
                    <th width="130px">Id Number</th>
                    <th width="120px">Title</th>
                    <th width="120px">Initials</th>
                    <th width="300px">Name</th>
                    <th width="200px">Surname</th>
                    <th width="120px">Firstname</th>
                    <th width="160px">Home Phone</th>
                    <th width="160px">Work Phone</th>
                    <th width="160px">Cell Phone</th>
                    <th width="300px">Email</th>
                </tr>
                <tr v-for="item in items">
                    <td >
                      <button class="btn btn-primary btn-xs" @click.prevent="editItem(item)">Edit</button>
                      <button class="btn btn-danger btn-xs" @click.prevent="deleteItem(item)">Delete</button>
                    </td>
                    <td>@{{ item.strIDNumber }}</td>
                    <td>@{{ item.TITLE}}</td>
                    <td>@{{ item.INITIALS}}</td>
                    <td>@{{ item.NAME}}</td>
                    <td>@{{ item.strSurname}}</td>
                    <td>@{{ item.strFirstName}}</td>
                    <td>@{{ item.strHomePhoneNo}}</td>
                    <td>@{{ item.strWorkPhoneNo}}</td>
                    <td>@{{ item.strCellPhoneNo}}</td>
                    <td>@{{ item.EMAIL}}</td>
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
                <h4 class="modal-title" id="myModalLabel">Create Owner</h4>
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
                        <label for="strIDNumber">Id Number:</label>
                        <input type="text" name="strIDNumber" class="form-control" v-model="newItem.strIDNumber" />
                        <span v-if="formErrors['strIDNumber']" class="error text-danger">@{{ formErrors['strIDNumber'] }}</span>

                    </div>

                    <div class="form-group">
                        <label for="strSurname">Title:</label>
                        <input type="text" name="TITLE" class="form-control" v-model="newItem.TITLE" />
                        <span v-if="formErrors['TITLE']" class="error text-danger">@{{ formErrors['TITLE'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="strSurname">Initials:</label>
                        <input type="text" name="INITIALS" class="form-control" v-model="newItem.INITIALS" />
                        <span v-if="formErrors['INITIALS']" class="error text-danger">@{{ formErrors['INITIALS'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="strSurname">Name:</label>
                        <input type="text" name="NAME" class="form-control" v-model="newItem.NAME" />
                        <span v-if="formErrors['NAME']" class="error text-danger">@{{ formErrors['NAME'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="strSurname">Surname:</label>
                        <input type="text" name="strSurname" class="form-control" v-model="newItem.strSurname" />
                        <span v-if="formErrors['strSurname']" class="error text-danger">@{{ formErrors['strSurname'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="strFirstName">Firstname:</label>
                        <input type="text" name="strFirstName" class="form-control" v-model="newItem.strFirstName" />
                        <span v-if="formErrors['strFirstName']" class="error text-danger">@{{ formErrors['strFirstName'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="strHomePhoneNo">Home Phone:</label>
                        <input type="text" name="strHomePhoneNo" class="form-control" v-model="newItem.strHomePhoneNo" />
                        <span v-if="formErrors['strHomePhoneNo']" class="error text-danger">@{{ formErrors['strHomePhoneNo'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="strWorkPhoneNo">Work Phone:</label>
                        <input type="text" name="strWorkPhoneNo" class="form-control" v-model="newItem.strWorkPhoneNo" />
                        <span v-if="formErrors['strWorkPhoneNo']" class="error text-danger">@{{ formErrors['strWorkPhoneNo'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="strCellPhoneNo">Cell Phone:</label>
                        <input type="text" name="strCellPhoneNo" class="form-control " v-model="newItem.strCellPhoneNo" />
                        <span v-if="formErrors['strCellPhoneNo']" class="error text-danger">@{{ formErrors['strCellPhoneNo'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="EMAIL">Email:</label>
                        <input type="text" name="EMAIL" class="form-control" v-model="newItem.EMAIL" />
                        <span v-if="formErrors['EMAIL']" class="error text-danger">@{{ formErrors['EMAIL'] }}</span>
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
                        <label for="strIDNumber">Id Number:</label>
                        <input type="text" name="strIDNumber" class="form-control" v-model="fillItem.strIDNumber" readonly/>
                        <span v-if="formErrorsUpdate['strIDNumber']" class="error text-danger">@{{ formErrorsUpdate['strIDNumber'] }}</span>

                    </div>
                    <div class="form-group">
                        <label for="strSurname">Title:</label>
                        <input type="text" name="TITLE" class="form-control" v-model="fillItem.TITLE" />
                        <span v-if="formErrorsUpdate['TITLE']" class="error text-danger">@{{ formErrorsUpdate['TITLE'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="strSurname">Initials:</label>
                        <input type="text" name="INITIALS" class="form-control" v-model="fillItem.INITIALS" />
                        <span v-if="formErrorsUpdate['INITIALS']" class="error text-danger">@{{ formErrorsUpdate['INITIALS'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="strSurname">Name:</label>
                        <input type="text" name="NAME" class="form-control" v-model="fillItem.NAME" />
                        <span v-if="formErrorsUpdate['NAME']" class="error text-danger">@{{ formErrorsUpdate['NAME'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="strSurname">Surname:</label>
                        <input type="text" name="strSurname" class="form-control" v-model="fillItem.strSurname" />
                        <span v-if="formErrorsUpdate['strSurname']" class="error text-danger">@{{ formErrorsUpdate['strSurname'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="strFirstName">Firstname:</label>
                        <input type="text" name="strFirstName" class="form-control" v-model="fillItem.strFirstName" />
                        <span v-if="formErrorsUpdate['strFirstName']" class="error text-danger">@{{ formErrorsUpdate['strFirstName'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="strHomePhoneNo">Home Phone:</label>
                        <input type="text" name="strHomePhoneNo" class="form-control" v-model="fillItem.strHomePhoneNo" />
                        <span v-if="formErrorsUpdate['strHomePhoneNo']" class="error text-danger">@{{ formErrorsUpdate['strHomePhoneNo'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="strWorkPhoneNo">Work Phone:</label>
                        <input type="text" name="strWorkPhoneNo" class="form-control" v-model="fillItem.strWorkPhoneNo" />
                        <span v-if="formErrorsUpdate['strWorkPhoneNo']" class="error text-danger">@{{ formErrorsUpdate['strWorkPhoneNo'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="strCellPhoneNo">Cell Phone:</label>
                        <input type="text" name="strCellPhoneNo" class="form-control " v-model="fillItem.strCellPhoneNo" />
                        <span v-if="formErrorsUpdate['strCellPhoneNo']" class="error text-danger">@{{ formErrorsUpdate['strCellPhoneNo'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="EMAIL">Email:</label>
                        <input type="text" name="EMAIL" class="form-control" v-model="fillItem.EMAIL" />
                        <span v-if="formErrorsUpdate['EMAIL']" class="error text-danger">@{{ formErrorsUpdate['EMAIL'] }}</span>
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

    <script type="text/javascript" src="{!! asset('js/owners.js') !!}"></script>

    @endsection
