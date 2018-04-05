<template>
    <div class="state-list-view"><table class="table table-hover table-striped table-responsive table-bordered table-condensed">
            <thead> <tr>
                <th>State Name</th>  <th>Comment</th>   <th>Actions
                    <div class="pull-right"><span class="btn btn-primary btn-xs" title="New" @click="onClickNew">
                            <span class="glyphicon glyphicon-plus"></span>NEW </span>
                    </div>  </th></tr> </thead>
            <tbody>
            <tr v-for="(stateNode,index) in stateNodes3">
                <td >
                   <p v-if="seen">{{ stateNode.name }}</p>
                   <p><button class="btn btn-lg btn-primary" v-on:click="seen = !seen">Toggle</button></p>
                </td> 

                <td>{{ stateNode.title }}</td>
                <td class="center"> <test-list-view-actions :item="stateNode" :row-data="stateNode" :row-index="index"
                                            @onActions="onActions" > </test-list-view-actions> 
                </td>
                 </tr>   </tbody>  </table>  </div>
</template>
 <script>
    import Vue from 'vue'
    import TestCustomActions from './TestCustomActions.vue'
    export default 
    {   props: {  stateNodes3: { type: Object, default() { return null; }  },   },
        data () {  return { seen: true  } },
        created() { console.log('components/New2/TestListView.vue-StateListView Component created.')   },
        updated() { console.log('components/New2/TestListView.vue-StateListView Component updated.')   },
        components: {'test-list-view-actions': TestCustomActions, },
        mounted() { console.log('components/New2/TestListView.vue-StateListView Component mounted.')  },
        methods: 
          { onClickNew() 
              { console.log('components/New2/TestListView.vue-onClickNew');
                let formData = {   id: '',  name: '',  title: '',  };
                let payload = { isShow: true, data: {action: 'Add', data: formData, index: 0} };
                this.$store.dispatch('setTestShowModal', payload)  //----triggers this in store--with empty data and opens new popup for adding
              },
              onActions(data)
              { console.log('components/New2/TestListView.vue-onActions data=', data);
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
                             {  me.$store.dispatch('deleteTestRequest', data.data)
                                .then((response) => {})
                                .catch((error) => {});
                             }, function (dismiss) {  });
                    return;
                }
                    this.$store.dispatch('setTestShowModal', payload)
              }
          }

    }
  </script>


