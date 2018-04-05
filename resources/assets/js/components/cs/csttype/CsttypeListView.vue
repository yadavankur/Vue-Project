<template>
    <div class="state-list-view"><table class="table table-hover table-striped table-responsive table-bordered table-condensed">
            <thead> <tr>
                <th>Ticket Type</th>  <th>SLA Time</th>  <th>comment</th> <th>Actions
                    <div class="pull-right"><span class="btn btn-primary btn-xs" title="New" @click="onClickNew">
                            <span class="glyphicon glyphicon-plus"></span>NEW </span>
                    </div>  </th></tr> </thead>
            <tbody>
            <tr v-for="(stateNode,index) in tickettype">
                <td > {{ stateNode.ticket_type }}               
                </td> 

                <td>{{ stateNode.sla_time }}</td>
                <td>{{ stateNode.comment }}</td>
                <td class="center"> <test-list-view-actions :item="stateNode" :row-data="stateNode" :row-index="index"
                                            @onActions="onActions" > </test-list-view-actions> 
                </td>
                 </tr>   </tbody>  </table>  </div>
</template>
 <script>
    import Vue from 'vue'
    import TestCustomActions from './TestCustomActions.vue'
    export default 
    {   props: {  tickettype: { type: Object, default() { return null; }  },   },
        data () {  return { seen: true  } },
        created() { console.log('cs/csttype/--ListView.vue- Component created.')   },
        updated() { console.log('cs/csttype/--ListView.vue- Component updated.')   },
        components: {'test-list-view-actions': TestCustomActions, },
        mounted() { console.log('cs/csttype/--ListView.vue- Component mounted.')  },
        methods: 
          { onClickNew() 
              { console.log('cs/csttype/--ListView.vue--onClickNew'); //-------add 1
                let formData = {   id: '',  ticket_type: '',  sla_time: '', comment: '' };
                let payload = { isShow: true, data: {action: 'Add', data: formData, index: 0} };
                this.$store.dispatch('cstypeshowpopup', payload)  //----triggers this in store--with empty data and opens new popup for adding
              },
            onActions(data)
              { console.log('cs/csttype/--TestListView.vue--onActions data=', data);
                let payload = { isShow: true, data: data   };
                if (data.action === 'Delete')
                {   let swal = this.$swal; let me = this;
                    this.$swal({
                                title: 'Are you sure?',
                                text: 'You will not be able to recover this state!',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'cancel',
                                confirmButtonClass: 'btn btn-success',
                                cancelButtonClass: 'btn btn-danger',
                                allowOutsideClick: false
                             }).then(function() 
                             {  me.$store.dispatch('deletetype', data.data)
                                .then((response) => {})
                                .catch((error) => {});
                             }, function (dismiss) {  });
                    return;
                }
                    this.$store.dispatch('cstypeshowpopup', payload)
              }
          }

    }
  </script>


