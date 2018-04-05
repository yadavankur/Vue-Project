<template> <div id="test-list"> <div class="main"><div class="container">
                <test-crud-modal></test-crud-modal>
                <div class="row"> <div class="col-md-12"> <div class="panel panel-primary">
                            <div class="panel-heading"><div class="test-title"> Test list </div> </div>
                            <div id="testlist" class="panel-collapse collapse in table-responsive">
                                <ul class="test-group">  <li id="tests-list" class="list-group-item">
                                        <test-list-view :stateNodes3="stateNodes2"></test-list-view>
                       </li> </ul> </div></div></div> </div> </div>  </div></div>
</template>
<script>
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import TestListView from './TestListView.vue'
    import TestCrudModal from './TestCrudModal.vue'
export default 
    {   data () { return {   }  },
        computed:  { ...mapGetters({ stateNodes2: 'allStateNodesnew2',  }), ...mapState({    }),  },
        created() 
            {//console.log('components/New1/NewView1.vue-hehe=', stateNodes1);
              console.log('components/New2/New2View1.vue- TestList vue Component created.');
              this.$store.dispatch('setStateNodesnew2')
                .then((response) => { console.log('components/New2/NewView1.vue-StateList vue created response=', response);  })
                .catch((error) => { console.error('components/New2/NewView1.vue-StateList vue created error=', error);   });
            },
        components: { 'test-crud-modal': TestCrudModal, 'test-list-view': TestListView,  },
        updated() { console.log('components/New2/New2View1.vue- Component updated.')   },
        mounted() { console.log('components/New2/New2View1.vue- Component mounted.')  },
    }

    </script>