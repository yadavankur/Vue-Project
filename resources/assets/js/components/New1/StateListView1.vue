<template>
    <div class="state-list-view">
        <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
            <thead>
            <tr>
                <th>State Name</th>     <th>Comment</th>        <th>Actions
                    <div class="pull-right">
                        <span class="btn btn-primary btn-xs" title="New" @click="onClickNew">
                            <span class="glyphicon glyphicon-plus"></span>NEW
                        </span>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(stateNode,index) in stateNodes">
                <td>{{ stateNode.name }}</td>
                <td>{{ stateNode.comment }}</td>
                <td class="center">
                    <state-list-view-actions :item="stateNode"
                                            :row-data="stateNode"
                                            :row-index="index"
                                            @onActions="onActions"
                    >
                    </state-list-view-actions>
                </td>  </tr>   </tbody>  </table>  </div>
</template>
<script>
    import Vue from 'vue';
    import StateCustomActions from './StateCustomActions1.vue'
    export default 
    {   props: {  stateNodes: { type: Object, default() { return null; }  },  },
        data () {  return {   }   },
        created() { console.log('components/New1/StateListView.vue-StateListView Component created.')   },
        updated() { console.log('components/New1/StateListView.vue-StateListView Component updated.')   },
        components: {'state-list-view-actions': StateCustomActions, },
        mounted() { console.log('components/New1/StateListView.vue-StateListView Component mounted.')  },
        methods: 
          {
            onClickNew() 
              { console.log('components/New1/StateListView.vue-onClickNew');
                let formData = {   name: '',  comment: '',  id: '',  };
                let payload = { isShow: true, data: {action: 'Add', data: formData, index: 0} };
                this.$store.dispatch('setStateShowModal', payload)
              },
            onActions(data)
              { console.log('components/New1/StateListView.vue-onActions data=', data);
                let payload = { isShow: true, data: data   };
                if (data.action === 'Delete')
                {
                    //
                    let swal = this.$swal;
                    let me = this;
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
                    }).then(function() {
                        me.$store.dispatch('deleteStateRequest', data.data)
                            .then((response) => {})
                            .catch((error) => {});
                    }, function (dismiss) {
                    });
                    return;
                }
                this.$store.dispatch('setStateShowModal', payload)
            }
        }
    }
</script>
