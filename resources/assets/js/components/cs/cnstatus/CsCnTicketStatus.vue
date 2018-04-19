<template> <div id="test-list"> <div class="main"><div class="container">
                <test-crud-modal></test-crud-modal>
                <div class="row"> <div class="col-md-12"> <div class="panel panel-primary">
                            <div class="panel-heading"><div class="test-title"> Test list </div> </div>
                            <div id="testlist" class="panel-collapse collapse in table-responsive">
                                <ul class="test-group">  <li id="tests-list" class="list-group-item">
                                        <test-list-view :ticketstatus="ticketstatus"></test-list-view>
                       </li> </ul> </div></div></div> </div> </div>  </div></div>
</template>
<script>
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import TestListView from './CsCnStatusListView.vue'
    import TestCrudModal from './CsCnStatusCrudModal.vue'
export default 
    {   data () { return {   }  },
        computed:  { ...mapGetters({ ticketstatus: 'ticketcnstatustablegetter',  }), ...mapState({    }),  },
        created() 
            {//console.log('components/New1/NewView1.vue-hehe=', stateNodes1);
              console.log('cs/csstatus/csticketstatus.vue- Component created.');
              this.$store.dispatch('getticketcnstatustable')
                .then((response) => { console.log('cs/csstatus/csticketstatus.vue-- created response=', response);  })
                .catch((error) => { console.error('cs/csstatus/csticketstatus.vue-- created error=', error);   });
            },
        components: { 'test-crud-modal': TestCrudModal, 'test-list-view': TestListView,  },
        updated() { console.log('cs/csstatus/csticketstatus.vue--- Component updated.')   },
        mounted() { console.log('cs/csstatus/csticketstatus.vue--- Component mounted.')  },
    }

    </script>