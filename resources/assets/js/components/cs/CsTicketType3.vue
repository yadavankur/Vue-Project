<template>
  <div id="root-order-history-summary">
    <div class="app-row">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#csticketinfoo"> Ticket No: {{ selectedTicket1 ? selectedTicket1.ticket_no  : ''  }} [ Rectification Report ] </a>
              <span class="pull-right"> <button class="btn btn-info btn-xs" @click.prevent="onClickPdf">SAVE PDF</button>
                                        <button class="btn btn-success btn-sm" @click.prevent="onClickNew">NEW</button> 
                                        <button class="btn btn-warning btn-sm" @click.prevent="onClickEdit">EDIT</button>
                                        <button class="btn btn-danger btn-sm" @click.prevent="onClickDel">DEL</button>
              </span>
            </div>
            <div id="csticketinfoo" class="panel-collapse collapse in table-responsive">
              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
               
                <tbody>
                      <tr><td>Credit Note ID</td><td colspan="2"> {{csticket[0] ? csticket[0].id: '' }}</td></tr>
                      
                       <tr v-for="find in csticket.allitems ">
                        <td>ITEM/ERROR/NOTES</td><td colspan="2"> ITEM:[{{ find ? find.items : '' }}] ERROR:[{{ find ? find.errors : '' }}] NOTES:[{{ find ? find.notes : '' }}]</td>
                        </tr>
                      <tr><td>Ticket No</td><td colspan="2"> {{csticket[0] ? csticket[0].ticket_no: '' }}</td></tr>
                      <tr><td>Error Caused By</td><td colspan="2"> {{csticket[0] ? csticket[0].errorcausedby : '' }}</td></tr>
                      <tr><td>Issues</td><td colspan="2"> {{csticket[0] ? csticket[0].issues: '' }}</td></tr>
                      <tr><td>OfficeUse(Scoper)</td><td colspan="2"> {{csticket[0] ? csticket[0].officeuse: '' }}</td></tr>
                      <tr><td>Status</td><td colspan="2"> {{csticket[0] ? csticket[0].tstatus.STATUS: '' }}</td></tr>
                      
                      <tr><td>Approving User : Group </td>
                        <td>{{ csticket[0] ? csticket[0].auserid.name : '' }}</td>
                        <td>{{ csticket[0] ? csticket[0].agroupid.name : '' }}</td>
                      </tr>
                     <tr><td>Comments</td><td colspan="2"> {{ csticket[0] ? csticket[0].comment : '' }}</td></tr>
                    <tr><td>Created By/ Updated By </td>
                        <td>{{ csticket[0] ? csticket[0].created_by.name : '' }}</td>
                        <td>{{ csticket[0] ? csticket[0].updated_by.name : '' }}</td>
                    </tr>
                    <tr><td>Created At/ Updated At </td>
                        <td>{{ csticket[0] ? csticket[0].created_at : '' }}</td>
                        <td>{{ csticket[0] ? csticket[0].updated_at : '' }}</td>
                    </tr>
                   
                    <td class="center">

                </td>

                
                </tbody>
              </table>
            </div>          
        </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue';
import { mapGetters, mapState} from 'vuex'

import jsPDF from 'jspdf'
import modal from 'vue-strap/src/Modal'
import input from 'vue-strap/src/Input'
import imgurl from '../../../img/logo.png'


export default 
{    computed: 
        { ...mapGetters({    }),
          ...mapState({ user: state => state.authUser,
                        selectedTicketttype1: state => state.cstkt.selectedTicket.ttype3,
                        csType1perTicket: state => state.cstickettype.csType3perTicket,
                        selectedTicket: state => state.cstkt.selectedTicket,
                      }),
          selectedTicket1(){  //console.log('/2a/- this.selectedTicket=',this.selectedTicket);
                              return this.selectedTicket; 
                           },
          csticket() { // console.log('/2a/- this.selectedTicketttype1=',this.selectedTicketttype1); 
                        if (this.csType1perTicket )  
                              { //console.log('/2a/ this.csType1perTicket=',this.csType1perTicket); 
                                //console.log('/2a/ this.selectedTicketttype1=',this.selectedTicketttype1); 
                               // console.log('/2a/ this.csType1perTicket[0].ttype1=',this.csType1perTicket[0].ttype2a);
                                if(this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no)
                                { let aa="";
                                  if(this.csType1perTicket[0].ttype3.length==1)
                                    {
                                      if(this.csType1perTicket[0].ttype3[0].builderorcustomer==1) aa=aa+"builderorcustomer"+" ";
                                      if(this.csType1perTicket[0].ttype3[0].factory==1) aa=aa+"Factory"+" ";
                                      if(this.csType1perTicket[0].ttype3[0].service==1) aa=aa+"Service"+" ";
                                      if(this.csType1perTicket[0].ttype3[0].customerservice==1) aa=aa+"CustomerService"+" ";
                                      if(this.csType1perTicket[0].ttype3[0].sales==1) aa=aa+"Sales"+" ";
                                      if(this.csType1perTicket[0].ttype3[0].estimating==1) aa=aa+"Estimating"+" ";
                                      if(this.csType1perTicket[0].ttype3[0].transport==1) aa=aa+"Transport"+" ";
                                      if(this.csType1perTicket[0].ttype3[0].supplier==1) aa=aa+"Supplier"+" ";
                                      if(this.csType1perTicket[0].ttype3[0].other==1) aa=aa+"Other"+" ";
                                      this.csType1perTicket[0].ttype3[0].errorcausedby=aa;
                                    }
                                   console.log('/3/this.csType1perTicket[0].ttype3 inside1', this.csType1perTicket[0].ttype3);
                                    var gg=[];
                                            if(this.csType1perTicket[0].ttype3.length>0)
                                                  {     console.log('this.csType1perTicke---inside edit=',this.csType1perTicket);
                                                       var abc1=this.csType1perTicket[0].ttype3[0].ddd? this.csType1perTicket[0].ttype3[0].ddd : '';
                                                       var abb1=this.csType1perTicket[0].ttype3[0].eee? this.csType1perTicket[0].ttype3[0].eee : '';
                                                       var abd1=this.csType1perTicket[0].ttype3[0].fff? this.csType1perTicket[0].ttype3[0].fff : '';
                                                        
                                                        var gg=[];
                                                        var abc2=abc1.split("||"); var abb2=abb1.split("||"); var abd2=abd1.split("||");  
                                                        for ( let j=1,i=0;j<abc2.length;j++,i++)
                                                        {  var abc3=abc2[j].split("."); var abb3=abb2[j].split(".");  var abd3=abd2[j].split(".");
                                                          // gg[i].items=abc3[1]; gg[i].errors=abb3[1]; gg[i].notes=abd3[1];
                                                            gg.push({items:abc3[1],errors:abb3[1], notes: abd3[1]   } )
                                                        }
                                                        console.log('gg=',gg); 
                                                      console.log('this.this.csType1perTicket[0].ttype3[0] with itemsn=',this.csType1perTicket[0].ttype3[0]);
                                                  }
                                              if( gg.length>0 ) this.csType1perTicket[0].ttype3.allitems =gg;
                                              else this.csType1perTicket[0].ttype3.allitems = [];
                                   
                                   return this.csType1perTicket[0].ttype3;
                                }
                                else {
                                       console.log('/3/this.selectedTicketttype1 inside returned', this.selectedTicketttype1);
                                       let aa="";
                                        // if(this.selectedTicketttype1.length==1)
                                       if(this.selectedTicketttype1.length==1)
                                         { 
                                            if(this.selectedTicketttype1[0].builderorcustomer==1) aa=aa+"Builder/Customer\r\n "+"\n"; 
                                            if(this.selectedTicketttype1[0].factory==1) aa=aa+"Factory <br/>"+"\n";
                                            if(this.selectedTicketttype1[0].service==1) aa=aa+"Service"+"\n\r";
                                            if(this.selectedTicketttype1[0].customerservice==1) aa=aa+"CustomerService \n"+"\n";
                                            if(this.selectedTicketttype1[0].sales==1) aa=aa+"Sales"+" ";
                                            if(this.selectedTicketttype1[0].estimating==1) aa=aa+"Estimating"+"\n";
                                            if(this.selectedTicketttype1[0].transport==1) aa=aa+"Transport"+"\n";
                                            if(this.selectedTicketttype1[0].supplier==1) aa=aa+"Supplier"+"\n";
                                            if(this.selectedTicketttype1[0].other==1) aa=aa+"Other"+"\n";
                                           //  if(this.selectedTicketttype1.service) aa=aa+"Service";
                                           this.selectedTicketttype1[0].errorcausedby=aa;
                                         }
                                //---------------------
                                          var gg=[];
                                          if(this.selectedTicketttype1.length>0)
                                            {   var abc1=this.selectedTicketttype1[0].ddd? this.selectedTicketttype1[0].ddd : '';
                                                var abb1=this.selectedTicketttype1[0].eee? this.selectedTicketttype1[0].eee : '';
                                                var abd1=this.selectedTicketttype1[0].fff? this.selectedTicketttype1[0].fff : '';
                                                var gg=[];
                                                var abc2=abc1.split("||"); var abb2=abb1.split("||"); var abd2=abd1.split("||");  
                                                for ( let j=1,i=0;j<abc2.length;j++,i++)
                                                {  var abc3=abc2[j].split("."); var abb3=abb2[j].split(".");  var abd3=abd2[j].split(".");
                                                   gg.push({items:abc3[1],errors:abb3[1], notes: abd3[1]   } )
                                                }
                                             }
                                            if( gg.length>0 ) this.selectedTicketttype1.allitems =gg;
                                            else this.selectedTicketttype1.allitems = [];
                                            //---------------
                                       return this.selectedTicketttype1;
                                      }
                              } 
                        else if (this.selectedTicketttype1)  
                            {   console.log('this.selectedTicketttype1 returned outside=',this.selectedTicketttype1); 
                                var aa="";
                                 console.log('this.selectedTicketttype1.length=',this.selectedTicketttype1.length); 
                                if(this.selectedTicketttype1.length==1)
                                { if(this.selectedTicketttype1[0].builderorcustomer==1) aa=aa+"Builder/Customer"+" ";    
                                  if(this.selectedTicketttype1[0].factory==1) aa=aa+"Factory"+" ";
                                      if(this.selectedTicketttype1[0].service==1) aa=aa+"Service"+" ";
                                      if(this.selectedTicketttype1[0].customerservice==1) aa=aa+"CustomerService"+" ";
                                      if(this.selectedTicketttype1[0].sales==1) aa=aa+"Sales"+" ";
                                      if(this.selectedTicketttype1[0].estimating==1) aa=aa+"Service"+" ";
                                      if(this.selectedTicketttype1[0].transport==1) aa=aa+"Transport"+" ";
                                      if(this.selectedTicketttype1[0].supplier==1) aa=aa+"Supplier"+" ";
                                      if(this.selectedTicketttype1[0].other==1) aa=aa+"Other"+" ";
                                  //  if(this.selectedTicketttype1.service) aa=aa+"Service";
                                    console.log('issuecause=',aa); 
                                  this.selectedTicketttype1[0].errorcausedby=aa;
                                }
                          //--------------------------------------------------
                                var gg=[];
                               if(this.selectedTicketttype1.length>0)
                                 {    var abc1=this.selectedTicketttype1[0].ddd? this.selectedTicketttype1[0].ddd : '';
                                     var abb1=this.selectedTicketttype1[0].eee? this.selectedTicketttype1[0].eee : '';
                                     var abd1=this.selectedTicketttype1[0].fff? this.selectedTicketttype1[0].fff : '';
                                     var gg=[];
                                     var abc2=abc1.split("||"); var abb2=abb1.split("||"); var abd2=abd1.split("||");  
                                     for ( let j=1,i=0;j<abc2.length;j++,i++)
                                     {  var abc3=abc2[j].split("."); var abb3=abb2[j].split(".");  var abd3=abd2[j].split(".");
                                        gg.push({items:abc3[1],errors:abb3[1], notes: abd3[1]   } )
                                      }
                                   }
                                if( gg.length>0 ) this.selectedTicketttype1.allitems =gg;
                                else this.selectedTicketttype1.allitems = [];
                                console.log('this.selectedTicketttype1[0] with gg=',this.selectedTicketttype1);
                               
                                console.log('this.selectedTicketttype1 returned outside=',this.selectedTicketttype1); 
                                return this.selectedTicketttype1;
                            } 
                        else return null; 
                     },
        }, //computed finish

    created() {   console.log('cs/cstickettype1.vue-in created-.this.csticket', this.selectedTicket); 
              },
    data () {  return {    }   },
    components: {       },
    updated() {   console.log('cs/cstickettype1.vue-in updated-.this.selectedorder', this.selectedTicketttype1); 
              },
    methods: 
          { onClickNew() 
              { console.log('cs/cstickettype1.vue-onClickNew');
                let formData = {   ticket_no: '',ticket_type_id: '',QUOTE_ID: '',ORDER_ID: '',
                                   location_id: '',  name: '',  title: '',  id: ''
                               };
                let payload = { isShow: true, data: {action: 'Add', data: formData,index: 0} };
                if(this.selectedTicketttype1.length > 0) 
                {   if (this.csType1perTicket && this.csType1perTicket[0].ttype3.length === 0 && this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no )  
                     {  console.log('inside 1st--deletion done--this.csType1perTicket',this.csType1perTicket);
                        this.$store.dispatch('setCsTicketType3ShowModal', payload)
                     }
                     else{
                          console.log('this.selectedTicketttype1.length>0',this.selectedTicketttype1);
                          if(this.csType1perTicket) console.log('csType1perTicket=',this.csType1perTicket);
                          this.$store.dispatch('showErrorNotification', 'Rectification Report is already added to this Ticket !');
                          return;
                     }
                } else if (this.csType1perTicket && this.csType1perTicket[0].ttype3.length > 0 && this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no )  
                     {  console.log('inside 2nd-this.csType3perTicket',this.csType1perTicket);
                        this.$store.dispatch('showErrorNotification', 'Rectification Report is already added to this Ticket !');
                        return;
                     }
                else {this.$store.dispatch('setCsTicketType3ShowModal', payload)  //----triggers this in store--with empty data and opens new popup for adding
                }
             }, //onclicknew finish
              onClickEdit() 
              {   console.log('cs/cstickettype3.vue-onClickNew');
                 // let formData = {   ticket_no: '',ticket_type_id: '',QUOTE_ID: '',ORDER_ID: '', location_id: '',  name: '',  title: '',  id: ''  };
                  let payload = { isShow: true, data: {action: 'Edit' } };  // data: formData,index: 0} };
                  console.log('cs/type3.vue-onClickEdit payload=',payload);
                  if(this.selectedTicketttype1.length > 0) 
                    {    if (this.csType1perTicket && this.csType1perTicket[0].ttype3.length === 0 && this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no )  
                            { this.$store.dispatch('showErrorNotification', 'Nothing to Edit!');}
                          else {this.$store.dispatch('setCsTicketType3ShowModal', payload)}
                    } 
                  else if (this.csType1perTicket && this.csType1perTicket[0].ttype3.length > 0 && this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no ) 
                    {   this.$store.dispatch('setCsTicketType3ShowModal', payload)    }
                  else 
                    { this.$store.dispatch('showErrorNotification', 'Please add Rectification Report to this Ticket !');
                      return;
                    }
              }, //onclickEdit finish
              onClickPdf() 
              {       var img = new Image();
                      img.src = imgurl;  var cstkt=this.csticket[0];
                      console.log('this.cstickt[0]=',cstkt);
                     
                      var doc = new jsPDF();
                         img.onload = function()
                         {
                           doc.addImage(img,  'png', 5, 5, 40, 10);
                        doc.setFontSize(6);  
                         doc.text("Dowell Windows Pty Ltd.", 10, 20);
                            doc.text("ABN 78 004 069 523", 10, 22);
                     
                        doc.setFontSize(20); 
                           doc.text(80, 10, "CREDIT NOTE");

                           doc.setFontSize(15); doc.text(90, 30, cstkt.comment);
                            doc.text(30, 20, 'Ticket Number'); doc.text(60, 20, cstkt.ticket_no);
                            doc.text(30, 30, 'Approving User'); doc.text(60, 30, cstkt.auserid.name);
                            doc.text(30, 40, 'Status');  doc.text(60, 40, cstkt.tstatus.STATUS);
                             doc.text(30, 50, 'Comment'); doc.text(60, 50, cstkt.comment);
                             //-------------------------------
                           doc.save('Test.pdf');
                          };
              },
             onClickDel()
              {   if(this.selectedTicketttype1.length > 0) 
                     {    
                         if (this.csType1perTicket && this.csType1perTicket[0].ttype3.length === 0 && this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no )  
                          { console.log('already deleted--this.csType1perTicket',this.csType1perTicket);
                            this.$store.dispatch('showErrorNotification', 'Whats this-- Pls add something !');
                            return;
                          }
                          else
                          {
                            let data=this.csticket[0];
                            let payload = { isShow: true, data: data   };
                            console.log('delete this.selectedTicketttype1.length > 0=',this.selectedTicketttype1 );
                            console.log('delete 1st- payload=',payload );
                            let swal = this.$swal;  let me = this;
                            this.$swal({
                                      title: 'Are you sure?',
                                      text: 'You will not be able to recover this Rectification Report!',
                                      type: 'warning',   showCancelButton: true,
                                      confirmButtonColor: '#3085d6',   cancelButtonColor: '#d33',
                                      confirmButtonText: 'Yes',  cancelButtonText: 'cancel',
                                      confirmButtonClass: 'btn btn-success', cancelButtonClass: 'btn btn-danger',
                                      allowOutsideClick: false
                                     }).then(  function() {    me.$store.dispatch('deletetype3', data)
                                                                  .then((response) => {
                                                                    console.log(' delete success'); 
                                                                     me.$events.fire('refreshcsticket');
                                                                    })
                                                                  .catch((error) => {});
                                                          }, 
                                                function (dismiss) {       }
                                             );
                                  return;
                                
                                   
                            }
                       } else if(this.csType1perTicket && this.csType1perTicket[0].ttype3.length > 0 && this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no ) 
                                {   let data=this.csticket[0];
                                    let payload = { isShow: true, data: data   };
                                    console.log('delete 2nd- payload',payload );
                                    let swal = this.$swal;     let me = this;
                                    this.$swal({
                                              title: 'Are you sure?',
                                              text: 'You will not be able to recover this Rectification Report!',
                                              type: 'warning',  showCancelButton: true,
                                              confirmButtonColor: '#3085d6',   cancelButtonColor: '#d33',
                                              confirmButtonText: 'Yes',   cancelButtonText: 'cancel',
                                              confirmButtonClass: 'btn btn-success',  cancelButtonClass: 'btn btn-danger',
                                              allowOutsideClick: false
                                            }).then(  function() {    me.$store.dispatch('deletetype3', data)
                                                                    .then((response) => {console.log(' delete success'); 
                                                                     me.$events.fire('refreshcsticket');
                                                                                       })
                                                                    .catch((error) => {});
                                                                     
                                                                  }, 
                                                      function (dismiss) {       }
                                                    );
                                     
                                   
                                     console.log('refresh done--after delete2');
                                     return;
                                  
                               // this.$store.dispatch('setCsTicketType2AShowModal', payload) 
                             }
                         else 
                              { this.$store.dispatch('showErrorNotification', 'nothing to delete !');
                                return;
                              }
                      }//del finish
          },//methods finish

          watch: 
          {    }
}
</script>

<style scoped>
td.center {  text-align: center !important; }
</style>