Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");



// this is our Model
var data =  {
    items: [],
    streets: [],
    complexes: [],
    owners: [],
    pagination: {
        total: 0, 
        per_page: 2,
        from: 1, 
        to: 0,
        current_page: 1
      },
    offset: 4,
    formErrors:{},
    formErrorsUpdate:{},
    newItem : { 

          'TITLE':'',
          'INITIALS':'',
          'strIDNumber':'',
          'strSurname':'',
          'strFirstName':'',
          'strHomePhoneNo':'',
          'strWorkPhoneNo':'',
          'strCellPhoneNo':'',
          'EMAIL':'',


    },


    fillItem : { 
          'id':'',
          'TITLE':'',
          'INITIALS':'',
          'strIDNumber':'',
          'strSurname':'',
          'strFirstName':'',
          'strHomePhoneNo':'',
          'strWorkPhoneNo':'',
          'strCellPhoneNo':'',
          'EMAIL':'',

              },

      search : {'string':''},

    }




var vm = new Vue({

  el: '#manage-owners',

  data: data,


  computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },

  ready : function(){



      // get all items
  		this.getVueItems(this.pagination.current_page);
      




        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };


  },

  methods : {




        getVueItems: function(page){
            this.$http.get('/farmbook_admin_test/public/vueowners?page='+page).then((response) => {
              this.$set('items', response.data.data.data);
              this.$set('pagination', response.data.pagination);
              }, (response) => {

              toastr.error('Error '+response.status, 'Warning', {timeOut: 5000});
              });


        },




        searchVueItems: function(page){
           var input = this.search.string;

           // clear search
           if (!input) {
            this.$http.get('/farmbook_admin_test/public/vueowners?page='+page).then((response) => {
              this.$set('items', response.data.data.data);
              this.$set('pagination', response.data.pagination);
            //  this.$set('agents', response.data.agents);
            });
          // do search
           } else {
            this.$http.post('/farmbook_admin_test/public/searchvueowners/'+input).then((response) => {
              this.$set('items', response.data.data.data);
              this.$set('pagination', response.data.pagination);
           //   this.$set('agents', response.data.agents);
            });

           }
        },

        createItem: function(){
      		  var input = this.newItem;
            //alert(this.newItem.selected);
      		  this.$http.post('/farmbook_admin_test/public/vueowners',input).then((response) => {
          		  this.changePage(this.pagination.current_page);
          			this.newItem = {
                        'TITLE':'',
                        'INITIALS':'',
                        'strIDNumber':'',
                        'strSurname':'',
                        'strFirstName':'',
                        'strHomePhoneNo':'',
                        'strWorkPhoneNo':'',
                        'strCellPhoneNo':'',
                        'EMAIL':'',
                               };

          			$("#create-item").modal('hide');
                $(".modal-header button").click();

          			toastr.success('Owner Created Successfully.', 'Success Alert', {timeOut: 5000});
          		}, (response) => {
          			this.formErrors = response.data;
              toastr.error('Error in form.', 'Warning', {timeOut: 5000});
        	    });
      	},



      createForms: function(){

                this.resetErrors();
                this.newItem = {
                        'TITLE':'',
                        'INITIALS':'',
                        'strIDNumber':'',
                        'strSurname':'',
                        'strFirstName':'',
                        'strHomePhoneNo':'',
                        'strWorkPhoneNo':'',
                        'strCellPhoneNo':'',
                        'EMAIL':'',
                               };
                $("#create-item").modal('show');
      },


      deleteItem: function(item){

          var result = confirm("Are you sure you would like to delete this Property?");
          if (result) {
                  this.$http.delete('/farmbook_admin_test/public/vueowners/'+item.id).then((response) => {
                      this.changePage(this.pagination.current_page);
                      toastr.success('Owner Deleted Successfully.', 'Success Alert', {timeOut: 5000});
                      }, (response) => {
               toastr.error('Owner cannot be deleted.', 'Warning', {timeOut: 5000});
                  });
          }

      },

      editItem: function(item){
          this.fillItem.id = item.id;
          this.fillItem.TITLE = item.TITLE;
          this.fillItem.INITIALS = item.INITIALS;
          this.fillItem.strIDNumber = item.strIDNumber;
          this.fillItem.strSurname = item.strSurname ;
          this.fillItem.strFirstName = item.strFirstName ;
          this.fillItem.strHomePhoneNo = item.strHomePhoneNo ;
          this.fillItem.strWorkPhoneNo = item.strWorkPhoneNo ;
          this.fillItem.strCellPhoneNo = item.strCellPhoneNo ;
          this.fillItem.EMAIL = item.EMAIL ;


          $("#edit-item").modal('show');

          this.resetErrors();
      },

      updateItem: function(id){

     
        //this.fillItem.selected = this.selectedAgent;
        var input = this.fillItem;
        this.$http.put('/farmbook_admin_test/public/vueowners/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillItem = {
                        'id':'',
                        'TITLE':'',
                        'INITIALS':'',
                        'strIDNumber':'',
                        'strSurname':'',
                        'strFirstName':'',
                        'strHomePhoneNo':'',
                        'strWorkPhoneNo':'',
                        'strCellPhoneNo':'',
                        'EMAIL':'',
          };
            $("#edit-item").modal('hide');
            toastr.success('Owner Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
                  this.formErrorsUpdate = response.data;

                   toastr.error('Error in form. '+response.status, 'Warning', {timeOut: 5000});

              
          });
      },

      resetErrors: function() {
         // $('.form-group').each(function () { $(this).closest("span").removeClass('error text-danger'); });
         //  $('.form-group').each(function () { $(this).removeClass('form-control'); });
      //    $( ".text-danger" ).remove();

           this.formErrorsUpdate = "";
           this.formErrors = "";
      },




      log: function(str) {
        alert("change log");
        $('#log').append(str + "<br>");
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueItems(page);
      }

  }

});