

@extends('layouts.app')


     <!-- select2 -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.css" media="all" rel="stylesheet" type="text/css" />
     <!-- CSS to make Select2 fit in with Bootstrap 3.x -->
     <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2-bootstrap.min.css" />
     <!-- toastr -->
     <link href="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.css" rel="stylesheet">
<style>

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
        padding: 9px 8px;
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
    height:800px;

                font-family: 'Lato';
    white-space: pre;
    margin: 1em 0;
}

</style>

@section('content')

    <div class="container" id="manage-buyers">




         <div class="row ">
                <div class="col-lg-6 margin-tb pull-left" >
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="searchVueItems">
                             <div id="custom-search-input" >
                               <div class="input-group ">
                                <input type="text" name="Search" class="form-control" v-model="search.string" placeholder="Search"/>
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
                <div class="pull-right">
                       <a href="{{ URL::route('exportBuyers') }}" class="btn btn-danger"> Export</a>
                </div>
                <div class="pull-left">
                    <button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#create-item">
                      New Buyer
                    </button>
                </div>

            </div>
        </div>

        <!-- Item Listing -->
        <div class="slategrey-background " style="overflow-x:auto; border: 12px solid SlateGrey  ;">
            <table class="table table-bordered table-hover">
                <tr>
                    <th width="120px">Action</th>
                    <th width="200px">Surname</th>
                    <th width="200px">Firstname</th>
                    <th width="180px">IdNumber</th>
                    <th width="120px">PhoneHome</th>
                    <th width="120px">PhoneWork</th>
                    <th width="120px">PhoneCell</th>
                    <th width="320px">Email</th>
                    <th width="100px">Bedroom</th>
                    <th width="100px">Bathroom</th>
                    <th width="100px">Garage</th>
                    <th width="100px">Pool</th>
                    <th width="120px">Agents</th>
                    <th width="120px">DateInMarket</th>
                    <th width="160px">Price</th>
                    <th width="320px">SellingIn</th>
                    <th width="320px">BuyingIn</th>
                    <th width="120px">Relist</th>
                    <th width="720px">OtherFeatures</th>
                    <th width="220px">Created</th>

                </tr>
                <tr v-for="item in items">
                    <td >
                      <button class="btn btn-primary btn-xs" @click.prevent="editItem(item)">Edit</button>
                      <button class="btn btn-danger btn-xs" @click.prevent="deleteItem(item)">Delete</button>
                    </td>
                    <td>@{{ item.Surname }}</td>
                    <td>@{{ item.Firstname}}</td>
                    <td>@{{ item.IdNumber }}</td>
                    <td>@{{ item.PhoneHome}}</td>
                    <td>@{{ item.PhoneWork }}</td>
                    <td>@{{ item.PhoneCell }}</td>
                    <td>@{{ item.Email }}</td>
                    <td>@{{ item.Bedroom }}</td>
                    <td>@{{ item.Bathroom}}</td>
                    <td>@{{ item.Garage }}</td>
                    <td>@{{ item.Pool}}</td>
                    <td>
                        <p v-for="agent in item.users">
                            @{{ agent.name }}
                        </p>
                    </td>

                    <td>@{{ item.DateInMarket}}</td>
                    <td>@{{ item.Price | currency 'R '}}</td>
                    <td>@{{ item.SellingIn}}</td>
                    <td>@{{ item.BuyingIn }}</td>
                    <td>@{{ item.Relist }}</td>
                    <td>@{{ item.OtherFeatures}}</td>
                    <td>@{{ item.created_at}}</td>
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


 <pre>@{{ $data | json }}</pre>

        <!-- Create Item Modal -->

        <div class="modal " id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" id="create-item-modal-header-button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Buyer</h4>
              </div>
              <div class="modal-body">

                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createItem">

                    <div class="form-group">
                        <label for="Surname">Surname:</label>
                        <input type="text" name="Surname" class="form-control" v-model="newItem.Surname" />
                        <span v-if="formErrors['Surname']" class="error text-danger">@{{ formErrors['Surname'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Firstname">Firstname:</label>
                        <input type="text" name="Firstname" class="form-control" v-model="newItem.Firstname" />
                        <span v-if="formErrors['Firstname']" class="error text-danger">@{{ formErrors['Firstname'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="IdNumber">IdNumber:</label>
                        <input type="text" name="IdNumber" class="form-control" v-model="newItem.IdNumber" />
                        <span v-if="formErrors['IdNumber']" class="error text-danger">@{{ formErrors['IdNumber'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="PhoneHome">PhoneHome:</label>
                        <input type="text" name="PhoneHome" class="form-control" v-model="newItem.PhoneHome" />
                        <span v-if="formErrors['PhoneHome']" class="error text-danger">@{{ formErrors['PhoneHome'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="PhoneWork">PhoneWork:</label>
                        <input type="text" name="PhoneWork" class="form-control" v-model="newItem.PhoneWork" />
                        <span v-if="formErrors['PhoneWork']" class="error text-danger">@{{ formErrors['PhoneWork'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="PhoneHome">PhoneCell:</label>
                        <input type="text" name="PhoneCell" class="form-control" v-model="newItem.PhoneCell" />
                        <span v-if="formErrors['PhoneCell']" class="error text-danger">@{{ formErrors['PhoneCell'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Email">Email:</label>
                        <input type="text" name="Email" class="form-control" v-model="newItem.Email" />
                        <span v-if="formErrors['Email']" class="error text-danger">@{{ formErrors['Email'] }}</span>
                    </div>


                    <div class="form-group">
                        <label for="Bedroom">Bedroom:</label>
                        <select class="form-control"  v-model="newItem.Bedroom">
                              <option value="0">0</option>
                              <option selected value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                        <span v-if="formErrors['Bedroom']" class="error text-danger">@{{ formErrors['Bedroom'] }}</span>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="Bathroom">Bathroom:</label>
                        <select class="form-control" v-model="newItem.Bathroom">
                              <option value="0">0</option>
                              <option selected value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                        <span v-if="formErrors['Bathroom']" class="error text-danger">@{{ formErrors['Bathroom'] }}</span>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Garage">Garage:</label>
                        <select class="form-control" v-model="newItem.Garage">
                              <option selected value="0">0</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                        <span v-if="formErrors['Garage']" class="error text-danger">@{{ formErrors['Garage'] }}</span>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Pool">Pool:</label>
                        <select class="form-control" v-model="newItem.Pool">
                              <option selected value="0">0</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                        <span v-if="formErrors['Pool']" class="error text-danger">@{{ formErrors['Pool'] }}</span>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Agent">Agent:</label>
                        <select  class="form-control newAgent" v-newselectedagent="newItem.selected" v-model="newItem.selected" style="width: 100%;"  multiple>
                               <option v-for="agent in agents" v-bind:value="agent.id"  >
                                    @{{ agent.text }}
                               </option>
                        <span v-if="formErrors['Agent']" class="error text-danger">@{{ formErrors['Agent'] }}</span>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="DateInMarket">DateInMarket:</label>
                        <input type="date" name="DateInMarket" class="form-control datepicker" v-model="newItem.DateInMarket" />
                        <span v-if="formErrors['DateInMarket']" class="error text-danger">@{{ formErrors['DateInMarket'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Price">Price:</label>
                        <input type="text" name="Price" class="form-control" v-model="newItem.Price" />
                        <span v-if="formErrors['Price']" class="error text-danger">@{{ formErrors['Price'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="SellingIn">SellingIn:</label>
                        <input type="text" name="SellingIn" class="form-control" v-model="newItem.SellingIn" />
                        <span v-if="formErrors['SellingIn']" class="error text-danger">@{{ formErrors['SellingIn'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="BuyingIn">BuyingIn:</label>
                        <input type="text" name="BuyingIn" class="form-control" v-model="newItem.BuyingIn" />
                        <span v-if="formErrors['BuyingIn']" class="error text-danger">@{{ formErrors['BuyingIn'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Relist">Relist:</label>
                        <input type="text" name="Relist" class="form-control" v-model="newItem.Relist" />
                        <span v-if="formErrors['Relist']" class="error text-danger">@{{ formErrors['Relist'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="OtherFeatures">OtherFeatures:</label>
                        <textarea name="OtherFeatures" class="form-control" v-model="newItem.OtherFeatures"></textarea>
                        <span v-if="formErrors['OtherFeatures']" class="error text-danger">@{{ formErrors['OtherFeatures'] }}</span>
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
                <h4 class="modal-title" id="myModalLabel">Edit Buyer</h4>
              </div>
              <div class="modal-body">

                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateItem(fillItem.id)">

                    <div class="form-group">
                        <label for="Surname">Surname:</label>
                        <input type="text" name="Surname" class="form-control" v-model="fillItem.Surname" />
                        <span v-if="formErrorsUpdate['Surname']" class="error text-danger">@{{ formErrorsUpdate['Surname'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Firstname">Firstname:</label>
                        <input type="text" name="Firstname" class="form-control" v-model="fillItem.Firstname"/>
                        <span v-if="formErrorsUpdate['Firstname']" class="error text-danger">@{{ formErrorsUpdate['Firstname'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="IdNumber">IdNumber:</label>
                        <input type="text" name="IdNumber" class="form-control" v-model="fillItem.IdNumber" />
                        <span v-if="formErrorsUpdate['IdNumber']" class="error text-danger">@{{ formErrorsUpdate['IdNumber'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="PhoneHome">PhoneHome:</label>
                        <input type="text" name="PhoneHome" class="form-control" v-model="fillItem.PhoneHome" />
                        <span v-if="formErrorsUpdate['PhoneHome']" class="error text-danger">@{{ formErrorsUpdate['PhoneHome'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="PhoneWork">PhoneWork:</label>
                        <input type="text" name="PhoneWork" class="form-control" v-model="fillItem.PhoneWork" />
                        <span v-if="formErrorsUpdate['PhoneWork']" class="error text-danger">@{{ formErrorsUpdate['PhoneWork'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="PhoneHome">PhoneCell:</label>
                        <input type="text" name="PhoneCell" class="form-control" v-model="fillItem.PhoneCell" />
                        <span v-if="formErrorsUpdate['PhoneCell']" class="error text-danger">@{{ formErrorsUpdate['PhoneCell'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Email">Email:</label>
                        <input type="text" name="Email" class="form-control" v-model="fillItem.Email" />
                        <span v-if="formErrorsUpdate['Email']" class="error text-danger">@{{ formErrorsUpdate['Email'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Bedroom">Bedroom:</label>
                        <select class="form-control" v-model="fillItem.Bedroom">
                              <option selected value="0">@{{ fillItem.Bedroom }}</option>
                              <option value="0">0</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                        <span v-if="formErrorsUpdate['Bedroom']" class="error text-danger">@{{ formErrorsUpdate['Bedroom'] }}</span>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Bathroom">Bathroom:</label>
                        <select class="form-control" v-model="fillItem.Bathroom">
                              <option selected value="0">@{{ fillItem.Bathroom }}</option>
                              <option value="0">0</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                        <span v-if="formErrorsUpdate['Bathroom']" class="error text-danger">@{{ formErrorsUpdate['Bathroom'] }}</span>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Garage">Garage:</label>
                        <select class="form-control" v-model="fillItem.Garage">
                              <option selected value="0">@{{ fillItem.Garage }}</option>
                              <option value="0">0</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                        <span v-if="formErrorsUpdate['Garage']" class="error text-danger">@{{ formErrorsUpdate['Garage'] }}</span>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Pool">Pool:</label>
                        <select  class="form-control" v-model="fillItem.Pool">
                              <option selected value="0">@{{ fillItem.Pool }}</option>
                              <option value="0">0</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                        <span v-if="formErrorsUpdate['Pool']" class="error text-danger">@{{ formErrorsUpdate['Pool'] }}</span>
                        </select>
                    </div>

<!--
                    <div class="form-group fred">
                        <p v-for="agent in fillItem.users" >
                         @{{ agent.value }}  @{{ agent.id }}  -  @{{ agent.name }}
                        </p>
                    </div>
-->
                    <div class="form-group">
                        <label for="Agent">Agent:</label>
<!--
                        <p>fillItem.selected: @{{fillItem.selected | json}}</p>
                        <p>selectedAgent: @{{selectedAgent | json}}</p>
                        <p>fillItem.Agent: @{{fillItem.Agent | json}}</p>
                        <p>options: @{{options | json}}</p>
-->
                   <!--       <select  class="form-control editAgent"    style="width: 100%;"     v-select="fillItem.selected" multiple > -->
                      <!--        <select v-select="fillItem.users" multiple  v-bind:options="fillItem.users.text" v-model="selected" style="width: 100%; height: 1em;"> -->
                         <!--  <select class="form-control" v-select="selectedAgent" multiple  v-bind:options="['Tom','Fred','11','16','19']"   style="width: 100%; height: 1em;"> -->
                       <!--      <select class="form-control" v-selectedagent="selectedAgent" multiple    v-bind:options="[{'id':11 , 'text':'fuck'}]" style="width: 100%; height: 1em;">  -->
                              <select class="form-control"    v-model="fillItem.selected"   :options="agents"  multiple  style="width: 100%; height: 4em;">

                             <!--  <option value="fillItem.users" selected="selected"></option>-->

                               <option v-for="agent in agents" v-bind:value="agent.id"  v-text="agent.text">
                                    @{{ agent.text }}
                               </option>

                               <!-- <option v-for="user in users" selected = "fillItem.users.id === user.id ? true : false"  >  -->
                              </select>


                        <span v-if="formErrorsUpdate['Agent']" class="error text-danger">@{{ formErrorsUpdate['Agent'] }}</span>
                        </select>

                    </div>


                    <div class="form-group">
                        <label for="DateInMarket">DateInMarket:</label>
                        <input type="date" name="DateInMarket" class="form-control datepicker" v-model="fillItem.DateInMarket" />
                        <span v-if="formErrorsUpdate['DateInMarket']" class="error text-danger">@{{ formErrorsUpdate['DateInMarket'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Price">Price:</label>
                        <input type="text" name="Price" class="form-control" v-model="fillItem.Price" />
                        <span v-if="formErrorsUpdate['Price']" class="error text-danger">@{{ formErrorsUpdate['Price'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="SellingIn">SellingIn:</label>
                        <input type="text" name="SellingIn" class="form-control" v-model="fillItem.SellingIn" />
                        <span v-if="formErrorsUpdate['SellingIn']" class="error text-danger">@{{ formErrorsUpdate['SellingIn'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="BuyingIn">BuyingIn:</label>
                        <input type="text" name="BuyingIn" class="form-control" v-model="fillItem.BuyingIn" />
                        <span v-if="formErrorsUpdate['BuyingIn']" class="error text-danger">@{{ formErrorsUpdate['BuyingIn'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="Relist">Relist:</label>
                        <input type="text" name="Relist" class="form-control" v-model="fillItem.Relist" />
                        <span v-if="formErrorsUpdate['Relist']" class="error text-danger">@{{ formErrorsUpdate['Relist'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="OtherFeatures">OtherFeatures:</label>
                        <textarea name="OtherFeatures" class="form-control" v-model="fillItem.OtherFeatures"></textarea>
                        <span v-if="formErrorsUpdate['OtherFeatures']" class="error text-danger">@{{ formErrorsUpdate['OtherFeatures'] }}</span>
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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>




    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.26/vue.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/vue.resource/0.9.3/vue-resource.min.js"></script>
   <script type="text/javascript" src="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.js"></script>

    <script type="text/javascript" src="{!! asset('js/buyers.js') !!}"></script>

    @endsection
