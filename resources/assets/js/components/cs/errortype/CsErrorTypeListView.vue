<template>
    <div class="state-list-view"><table class="table table-hover table-striped table-responsive table-bordered table-condensed">
            <thead> <tr>
                <th>ID</th><th>ERROR CODE</th>  <th>Ticket Type</th> <th>Comments</th>  <th>Actions
                    <div class="pull-right">
                            <span class="btn btn-primary btn-xs" title="New" @click="onClickNew">
                                <span class="glyphicon glyphicon-plus">
                                </span>
                               NEW
                            </span>
                    </div>  </th></tr> </thead>
            <tbody>
            <tr v-for="(stateNode,index) in ticketstatus">
                <td >{{ stateNode.id}}</td> 
                <td>{{ stateNode.errorcode }}</td>
                 <td >{{ stateNode.ttype.ticket_type}}</td> 
                <td>{{ stateNode.comment }}</td>
                <td class="center"> <test-list-view-actions :item="stateNode" :row-data="stateNode" :row-index="index"
                                                             @onActions="onActions" > 
                                    </test-list-view-actions> 
                </td>
                 </tr>   </tbody>  </table>  </div>
</template>
 <script>
    import Vue from 'vue'
    import TestCustomActions from './CsErrorTypeCustomActions.vue'
    export default 
    {   props: {  ticketstatus: { type: Object, default() { return null; }  },   },
        data () {  return { seen: true  } },
        created() { console.log('errorListview.vue---- Component created.')   },
        updated() { console.log('errorListview.vue--- Component updated.')   },
        components: {'test-list-view-actions': TestCustomActions, },
        mounted() { console.log('errorListview.vue-- Component mounted.')  },
        methods: 
          { onClickNew() //--------add----------1
              { console.log('errorListview.vue----onClickNew');
                let formData = {   id: '',  STATUS: '',  comment: '',  };
                let payload = { isShow: true, data: {action: 'Add', data: formData, index: 0} };
                this.$store.dispatch('cserrortypeshowpopup', payload)  //----triggers this in store--with empty data and opens new popup for adding
              },
            onActions(data) //------------edit 2---coming from custom actions
              { console.log('errorListview.vue--onActions data=', data);
                let payload = { isShow: true, data: data   };
          

                if (data.action === 'Delete')
                {   let swal = this.$swal; let me = this;
                    this.$swal({
                                title: 'Are you sure?',  text: 'You will not be able to recover this ERROR TYPE!',  type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes',  cancelButtonText: 'cancel',
                                confirmButtonClass: 'btn btn-success',  cancelButtonClass: 'btn btn-danger',
                                allowOutsideClick: false
                             }).then(function() 
                             {  me.$store.dispatch('deleteerrortype', data.data)
                                .then((response) => {})
                                .catch((error) => {});
                             }, function (dismiss) {  });
                    return;
                }
                this.$store.dispatch('cserrortypeshowpopup', payload) //----edit 3--its here becasue if above delete--popup comes
                    
              }
          }

    }
  </script>


