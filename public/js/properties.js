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


          'numErf':'',
          'numPortion':'0',
          'strStreetNo':'',
          'strStreetName':'',
          'strSqMeters':'',
          'strComplexNo':'',
          'strComplexName':'',
          'dtmRegDate':'',
          'strAmount':'',
          'strBondHolder':'',
          'strBondAmount':'',
          'strOwners':'',
          'strIdentity':'',
          'strSellers':'',
          'strTitleDeed':'',

    },


    fillItem : { 
          'id':'',
          'numErf':'',
          'numPortion':'0',
          'strStreetNo':'',
          'strStreetName':'',
          'strSqMeters':'',
          'strComplexNo':'',
          'strComplexName':'',
          'dtmRegDate':'',
          'strAmount':'',
          'strBondHolder':'',
          'strBondAmount':'',
          'strOwners':'',
          'strIdentity':'',
          'strSellers':'',
          'strTitleDeed':'',
          'strKey':'',
          'strSuburb':'',
          'followup':'',
          'note':'',

              },

      search : {'string':''},

    }




var vm = new Vue({

  el: '#manage-properties',

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
      

      // get all select box data
      this.getVueSelects();


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
            this.$http.get('/farmbook_admin_test/public/vueproperties?page='+page).then((response) => {
              this.$set('items', response.data.data.data);
              this.$set('pagination', response.data.pagination);


            });


        },

        getVueSelects: function(){
            this.$http.get('/farmbook_admin_test/public/vuepropertiesSelects').then((response) => {
              this.$set('streets', response.data.streets);
              this.$set('complexes', response.data.complexes);
              this.$set('owners', response.data.owners);
            });
        },


        searchVueItems: function(page){
           var input = this.search.string;

           // clear search
           if (!input) {
            this.$http.get('/farmbook_admin_test/public/vueproperties?page='+page).then((response) => {
              this.$set('items', response.data.data.data);
              this.$set('pagination', response.data.pagination);
            //  this.$set('agents', response.data.agents);
            });
          // do search
           } else {
            this.$http.post('/farmbook_admin_test/public/searchvueproperties/'+input).then((response) => {
              this.$set('items', response.data.data.data);
              this.$set('pagination', response.data.pagination);
           //   this.$set('agents', response.data.agents);
            });

           }
        },

        createItem: function(){
      		  var input = this.newItem;
            //alert(this.newItem.selected);
      		  this.$http.post('/farmbook_admin_test/public/vueproperties',input).then((response) => {
          		  this.changePage(this.pagination.current_page);
          			this.newItem = {
                        'numErf':'',
                        'numPortion':'',
                        'strStreetNo':'',
                        'strStreetName':'',
                        'strSqMeters':'',
                        'strComplexNo':'',
                        'strComplexName':'',
                        'dtmRegDate':'',
                        'strAmount':'',
                        'strBondHolder':'',
                        'strBondAmount':'',
                        'strOwners':'',
                        'strIdentity':'',
                        'strSellers':'',
                        'strTitleDeed':'',
                               };
                this.resetErrors();
          			$("#create-item").modal('hide');
                $(".modal-header button").click();

          			toastr.success('Property Created Successfully.', 'Success Alert', {timeOut: 5000});
          		}, (response) => {
          			this.formErrors = response.data;
                toastr.error('Error in form.', 'Warning', {timeOut: 5000});
        	    });
      	},

      // add new owner
      createForms: function(){

                this.resetErrors();
                this.newItem = {
                        'numErf':'',
                        'numPortion':'',
                        'strStreetNo':'',
                        'strStreetName':'',
                        'strSqMeters':'',
                        'strComplexNo':'',
                        'strComplexName':'',
                        'dtmRegDate':'',
                        'strAmount':'',
                        'strBondHolder':'',
                        'strBondAmount':'',
                        'strOwners':'',
                        'strIdentity':'',
                        'strSellers':'',
                        'strTitleDeed':'',
                               };

                               $("#create-item").modal('show');
                               $("#create-item").myModalLabel.modal-title.innerHtml = "hello";
      },

      deleteItem: function(item){

          var result = confirm("Are you sure you would like to delete this Property?");
          if (result) {
                  this.$http.delete('/farmbook_admin_test/public/vueproperties/'+item.id).then((response) => {
                      this.changePage(this.pagination.current_page);
                      toastr.success('Property Deleted Successfully.', 'Success Alert', {timeOut: 5000});
                  });
          }

      },

      editItem: function(item){
          this.fillItem.id = item.id;
          this.fillItem.numErf = item.numErf;
          this.fillItem.strSuburb = item.strSuburb ;
          this.fillItem.numErf = item.numErf ;
          this.fillItem.numPortion = item.numPortion ;
          this.fillItem.strStreetNo = item.strStreetNo ;
          this.fillItem.strStreetName = item.strStreetName ;
          this.fillItem.strSqMeters = item.strSqMeters ;
          this.fillItem.strComplexNo = item.strComplexNo ;
          this.fillItem.strComplexName = item.strComplexName ;
          this.fillItem.dtmRegDate = item.dtmRegDate ;
          this.fillItem.strAmount = item.strAmount ;
          this.fillItem.strBondHolder = item.strBondHolder ;
          this.fillItem.strBondAmount = item.strBondAmount ;
          this.fillItem.strOwners = item.strOwners ;
          this.fillItem.strIdentity = item.strIdentity ;
          this.fillItem.strSellers = item.strSellers ;
          this.fillItem.strTitleDeed = item.strTitleDeed ;
          this.fillItem.strKey = item.strKey ;
          this.fillItem.note = item.note.memNotes ;
          this.fillItem.followup = item.note.followup ;

          $("#edit-item").modal('show');

          this.resetErrors();
      },

      duplicateItem: function(item){
          //this.fillItem.id = item.id;
          this.newItem.numErf = item.numErf;
          this.newItem.strSuburb = item.strSuburb ;
          this.newItem.numErf = item.numErf ;
          this.newItem.numPortion = item.numPortion ;
          this.newItem.strStreetNo = item.strStreetNo ;
          this.newItem.strStreetName = item.strStreetName ;
          this.newItem.strSqMeters = item.strSqMeters ;
          this.newItem.strComplexNo = item.strComplexNo ;
          this.newItem.strComplexName = item.strComplexName ;
          this.newItem.dtmRegDate = item.dtmRegDate ;
          this.newItem.strAmount = item.strAmount ;
          this.newItem.strBondHolder = item.strBondHolder ;
          this.newItem.strBondAmount = item.strBondAmount ;

          this.newItem.strSellers = item.strSellers ;
          this.newItem.strTitleDeed = item.strTitleDeed ;

          this.newItem.strIdentity = "";
          $("#create-item").modal('show');

          this.resetErrors();
      },

      updateItem: function(id){

     
        //this.fillItem.selected = this.selectedAgent;
        var input = this.fillItem;
        this.$http.put('/farmbook_admin_test/public/vueproperties/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillItem = {
              'id':'',
              'numErf':'',
              'numPortion':'0',
              'strStreetNo':'',
              'strStreetName':'',
              'strSqMeters':'',
              'strComplexNo':'',
              'strComplexName':'',
              'dtmRegDate':'',
              'strAmount':'',
              'strBondHolder':'',
              'strBondAmount':'',
              'strOwners':'',
              'strIdentity':'',
              'strSellers':'',
              'strTitleDeed':'',
              'strKey':'',
              'strSuburb':'',
              'followup':'',
              'note':'',
          };
            $("#edit-item").modal('hide');
            toastr.success('Property Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
              toastr.error('Error in form.', 'Warning', {timeOut: 5000});
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