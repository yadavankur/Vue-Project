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
    import TestListView from './CsErrorTypeListView.vue'
    import TestCrudModal from './CsErrorTypeCrudModal.vue'
export default 
    {   data () { return {   }  },
        computed:  { ...mapGetters({ ticketstatus: 'ticketerrortypetablegetter',  }), ...mapState({    }),  },
        created() 
            {//console.log('components/New1/NewView1.vue-hehe=', stateNodes1);
              console.log('errortype.vue- Component created.');
              this.$store.dispatch('getticketerrortypetable')
                .then((response) => { console.log('errortype.vue-- created response=', response);  })
                .catch((error) => { console.error('errortype.vue-- created error=', error);   });
            },
        components: { 'test-crud-modal': TestCrudModal, 'test-list-view': TestListView,  },
        updated() { console.log('errortype.vue.vue--- Component updated.')   },
        mounted() { console.log('errortype.vue--- Component mounted.')  },
    }

    </script>