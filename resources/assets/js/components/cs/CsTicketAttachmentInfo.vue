<template>
  <div id="root-order-history-summary">
    <div class="app-row">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#csticketinfoo"> Attachments</a>
              <div class="pull-right">  <td>
        <input type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>
  </td><td>
        <button v-on:click="submitFile()">Submit</button></td>
                </div>
            </div>
            <div id="csticketinfoo" class="panel-collapse collapse in table-responsive">
              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <thead>
                  <tr> <th>Ticket No</th><th>Date Time </th> <th>Description</th> <th>Comments</th><th>Status </th></tr>

               
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>          
        </div>
    </div>
  </div>
</template>



<script>
  export default 
  {
    data(){  return {    file: ''  }    },
    methods: 
    { submitFile()     /*  Submits the file to the server */
        {  let data = new FormData();   /* Initialize the form data   */
     //   formData: {   ticket_no: '2'};
      
        console.log(' this.data', this.data);
       // console.log(' payload', payload);
         console.log(' sirf data', data);
          data.append('file', this.file); /*  Add the form data we need to submit */
           console.log('  this.file', this.file);
console.log(' this.data', this.data);
console.log('sirf data', data);
        /* Make the request to the POST /single-file URL  
            axios.post( '/single-file',
                formData,
                {    headers: {'Content-Type': 'multipart/form-data'        }     }
            ).then(function(){    console.log('SUCCESS!!');    })
             .catch(function(){     console.log('FAILURE!!');     });

*/
            this.$store.dispatch('addFile', data)
           // this.$store.dispatch('addfiile', this.formData)
                        .then((response) => {  console.log(' save success');    })
                        .catch((error) => {console.log('save error');});
      },

      /*        Handles a change on the file upload      */
      handleFileUpload(){   this.file = this.$refs.file.files[0];                           
                      
                      console.log(' handlefileupload this.file', this.file);
                      }
    }
  }
</script>
