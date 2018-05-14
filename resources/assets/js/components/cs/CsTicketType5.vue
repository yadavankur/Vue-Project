<template>
  <div id="root-order-history-summary">
    <div class="app-row">
        <div class="panel panel-primary">
            <div class="panel-heading">
              <a class="accordion-toggle" data-toggle="collapse"  href="#csticketinfoo"> Ticket No: {{ selectedTicket1 ? selectedTicket1.ticket_no  : ''  }} [ SDA ] </a>
              <span class="pull-right"> <button class="btn btn-info btn-xs" @click.prevent="onClickPdf">SAVE PDF</button>
                                        <button class="btn btn-success btn-sm" @click.prevent="onClickNew">NEW</button> 
                                        <button class="btn btn-warning btn-sm" @click.prevent="onClickEdit">EDIT</button>
                                        <button class="btn btn-danger btn-sm" @click.prevent="onClickDel">DEL</button>
              </span>
            </div>
           
            <div id="csticketinfoo" class="panel-collapse collapse in table-responsive">
              <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
               
                <tbody>
                      <tr><td>SDA ID</td><td colspan="2"> {{csticket[0] ? csticket[0].id: '' }}</td></tr>

                      <tr><td>Ticket No</td><td colspan="2"> {{csticket[0] ? csticket[0].ticket_no: '' }}</td></tr>
                      <tr v-for="find in csticket.allitems ">
                        <td>ITEM/ERROR/NOTES</td><td colspan="2"> ITEM:[{{ find ? find.items : '' }}] ERROR:[{{ find ? find.errors : '' }}] NOTES:[{{ find ? find.notes : '' }}]</td>
                        </tr>
                                 
                    <tr><td>Comments</td><td colspan="2"> {{ csticket[0] ? csticket[0].comment : '' }}</td></tr>
                  
                 
                     <tr><td>Status</td><td colspan="2"> {{csticket[0] ? csticket[0].tstatus.STATUS: '' }}</td></tr>                      
                      <tr><td>Approving User : Group </td>
                        <td>{{ csticket[0] ? csticket[0].auserid.name : '' }}</td>
                        <td>{{ csticket[0] ? csticket[0].agroupid.name : '' }}</td>
                      </tr>
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
                        selectedTicketttype1: state => state.cstkt.selectedTicket.ttype5,
                        csType1perTicket: state => state.cstickettype.csType5perTicket,
                        selectedTicket: state => state.cstkt.selectedTicket,
                         v6itemstable: state => state.cstkt.selectedTicket.v6items,
                      }),
          selectedTicket1(){  //console.log('/2a/- this.selectedTicket=',this.selectedTicket);
                              return this.selectedTicket; 
                           },
          csticket() { // console.log('/2a/- this.selectedTicketttype1=',this.selectedTicketttype1); 
                        if (this.csType1perTicket )  //if edit done-----
                              { //console.log('/2a/ this.csType1perTicket=',this.csType1perTicket); 
                                //console.log('/2a/ this.selectedTicketttype1=',this.selectedTicketttype1); 
                               // console.log('/2a/ this.csType1perTicket[0].ttype1=',this.csType1perTicket[0].ttype2a);
                                if(this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no)
                                  {     // console.log('this.csType1perTicke---outside edit edit=',this.csType1perTicket);
                                             var gg=[];
                                            if(this.csType1perTicket[0].ttype5.length>0)
                                                  {     console.log('this.csType1perTicke---inside edit=',this.csType1perTicket);
                                                       var abc1=this.csType1perTicket[0].ttype5[0].aaa? this.csType1perTicket[0].ttype5[0].aaa : '';
                                                       var abb1=this.csType1perTicket[0].ttype5[0].bbb? this.csType1perTicket[0].ttype5[0].bbb : '';
                                                       var abd1=this.csType1perTicket[0].ttype5[0].ccc? this.csType1perTicket[0].ttype5[0].ccc : '';
                                                        
                                                        var gg=[];
                                                        var abc2=abc1.split("||"); var abb2=abb1.split("||"); var abd2=abd1.split("||");  
                                                        for ( let j=1,i=0;j<abc2.length;j++,i++)
                                                        {  var abc3=abc2[j].split("."); var abb3=abb2[j].split(".");  var abd3=abd2[j].split(".");
                                                          // gg[i].items=abc3[1]; gg[i].errors=abb3[1]; gg[i].notes=abd3[1];
                                                            gg.push({items:abc3[1],errors:abb3[1], notes: abd3[1]   } )
                                                        }
                                                        console.log('gg=',gg); 
                                                     // this.csType1perTicket[0].ttype5[0].allitems =gg;
                                                      console.log('this.this.csType1perTicket[0].ttype5[0] with itemsn=',this.csType1perTicket[0].ttype5[0]);
                                                  }
                                            if( gg.length>0 ) this.csType1perTicket[0].ttype5.allitems =gg;
                                            else this.csType1perTicket[0].ttype5.allitems = [];
                                           return this.csType1perTicket[0].ttype5;
                                  } //below is if edit done but for some other ticket---then return old
                                else  {    var gg=[];
                                          if(this.selectedTicketttype1.length>0)
                                            {    var abc1=this.selectedTicketttype1[0].aaa? this.selectedTicketttype1[0].aaa : '';
                                                var abb1=this.selectedTicketttype1[0].bbb? this.selectedTicketttype1[0].bbb : '';
                                                var abd1=this.selectedTicketttype1[0].ccc? this.selectedTicketttype1[0].ccc : '';
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
                                            return this.selectedTicketttype1;
                                      }
                              } 
                        else if (this.selectedTicketttype1)  //if no edit done
                            {   
                                console.log('this.selectedTicketttype1 returned outside=',this.selectedTicketttype1); 
                                var gg=[];
                               if(this.selectedTicketttype1.length>0)
                                 {    var abc1=this.selectedTicketttype1[0].aaa? this.selectedTicketttype1[0].aaa : '';
                                     var abb1=this.selectedTicketttype1[0].bbb? this.selectedTicketttype1[0].bbb : '';
                                     var abd1=this.selectedTicketttype1[0].ccc? this.selectedTicketttype1[0].ccc : '';
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
                               return this.selectedTicketttype1;
                            } 
                        else return null; 
                     },
        }, //computed finish

    created() {   console.log('cs/cstickettype1.vue-in created-.this.csticket', this.selectedTicket); 
                    this.collectItemOptions(this.v6itemstable);
              },
    data () {  return {    }   },
    components: {       },
    updated() {   console.log('cs/cstickettype1.vue-in updated-.this.selectedorder', this.selectedTicketttype1); 
              },
    methods: 
          { collectItemOptions(statuses) 
                     {  console.log('Type5Crud--- Items=',statuses);
                        let options = [];
                        for (let i=0;i< statuses.length; i++) 
                         { var ss=statuses[i];
                             options.push({value: ss.QTE_POS, label: ss.FRA_CODE});  
                         }
                           this.itemOptions = options;
                            console.log('Type5Crud--- ItemOptions=',this.itemOptions);
                     },
            
            
            onClickNew() 
              { console.log('cs/cstickettype5.vue-onClickNew');
                let formData = {   ticket_no: '',ticket_type_id: '',QUOTE_ID: '',ORDER_ID: '',
                                   location_id: '',  name: '',  title: '',  id: ''
                               };
                let payload = { isShow: true, data: {action: 'Add', data: formData,index: 0} };
                if(this.selectedTicketttype1.length > 0) 
                {   if (this.csType1perTicket && this.csType1perTicket[0].ttype5.length === 0 && this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no )  
                     {  console.log('inside 1st--deletion done--this.csType1perTicket',this.csType1perTicket);
                        this.$store.dispatch('setCsTicketType5ShowModal', payload)
                     }
                     else{
                          console.log('this.selectedTicketttype1.length>0',this.selectedTicketttype1);
                          if(this.csType1perTicket) console.log('csType1perTicket=',this.csType1perTicket);
                          this.$store.dispatch('showErrorNotification', 'Pickup Docket is already added to this Ticket !');
                          return;
                     }
                } else if (this.csType1perTicket && this.csType1perTicket[0].ttype5.length > 0 && this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no )  
                     {  console.log('inside 2nd-this.csttype5perTicket',this.csType1perTicket);
                        this.$store.dispatch('showErrorNotification', 'Pickup Docket is already added to this Ticket !');
                        return;
                     }
                else {this.$store.dispatch('setCsTicketType5ShowModal', payload)  //----triggers this in store--with empty data and opens new popup for adding
                }
             }, //onclicknew finish
              onClickEdit() 
              {   console.log('cs/cstickettype5.vue-onClickNew');
                  let payload = { isShow: true, data: {action: 'Edit' } };  
                  console.log('cs/type3.vue-onClickEdit payload=',payload);
                  if(this.selectedTicketttype1.length > 0) 
                    {    if (this.csType1perTicket && this.csType1perTicket[0].ttype5.length === 0 && this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no )  
                            { this.$store.dispatch('showErrorNotification', 'Nothing to Edit!');}
                          else {this.$store.dispatch('setCsTicketType5ShowModal', payload)}
                    } 
                  else if (this.csType1perTicket && this.csType1perTicket[0].ttype5.length > 0 && this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no ) 
                    {   this.$store.dispatch('setCsTicketType5ShowModal', payload)    }
                  else 
                    { this.$store.dispatch('showErrorNotification', 'Please add Pickup Docket to this Ticket !');
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
                        //    doc.text(30, 40, 'Status');  doc.text(60, 40, cstkt.tstatus.STATUS);
                             doc.text(30, 50, 'Comment'); doc.text(60, 50, cstkt.comment);
                             //-------------------------------
                           doc.save('Test.pdf');
                          };
              },
             onClickDel()
              {   if(this.selectedTicketttype1.length > 0) 
                     {    
                         if (this.csType1perTicket && this.csType1perTicket[0].ttype5.length === 0 && this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no )  
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
                                      text: 'You will not be able to recover this Pickup Docket!',
                                      type: 'warning',   showCancelButton: true,
                                      confirmButtonColor: '#3085d6',   cancelButtonColor: '#d33',
                                      confirmButtonText: 'Yes',  cancelButtonText: 'cancel',
                                      confirmButtonClass: 'btn btn-success', cancelButtonClass: 'btn btn-danger',
                                      allowOutsideClick: false
                                     }).then(  function() {    me.$store.dispatch('deletetype5', data)
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
                       } else if(this.csType1perTicket && this.csType1perTicket[0].ttype5.length > 0 && this.csType1perTicket[0].ticket_no == this.selectedTicket.ticket_no ) 
                                {   let data=this.csticket[0];
                                    let payload = { isShow: true, data: data   };
                                    console.log('delete 2nd- payload',payload );
                                    let swal = this.$swal;     let me = this;
                                    this.$swal({
                                              title: 'Are you sure?',
                                              text: 'You will not be able to recover this Pickup Docket!',
                                              type: 'warning',  showCancelButton: true,
                                              confirmButtonColor: '#3085d6',   cancelButtonColor: '#d33',
                                              confirmButtonText: 'Yes',   cancelButtonText: 'cancel',
                                              confirmButtonClass: 'btn btn-success',  cancelButtonClass: 'btn btn-danger',
                                              allowOutsideClick: false
                                            }).then(  function() {    me.$store.dispatch('deletettype5', data)
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