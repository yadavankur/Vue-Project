<template>
    <div id="state-list"> <div class="main"><div class="container">
                <state-crud-modal></state-crud-modal>
                <div class="row"> <div class="col-md-12"> <div class="panel panel-primary">
                            <div class="panel-heading"><div class="state-title"> State list </div> </div>
                            <div id="statelist" class="panel-collapse collapse in table-responsive">
                                <ul class="list-group">
                                    <li id="states-list" class="list-group-item">
                                        <state-list-view :stateNodes="stateNodes"></state-list-view>
                                  </li> </ul>
                            </div></div></div> </div> </div>  </div></div>
</template>
<script>
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import StateListView1 from './StateListView1.vue'
    import StateCrudModal1 from './StateCrudModal1.vue'
    export default 
    {   data () { return {   }  },
        computed: 
             { ...mapGetters({  stateNodes: 'allStateNodes',  }),
               ...mapState({  user: state => state.authUser,  }),
             },
        created() 
            {//console.log('components/New1/NewView1.vue-hehe=', stateNodes1);
              console.log('components/New1/NewView1.vue- StateList vue Component created.');
              this.$store.dispatch('setStateNodes')
                .then((response) => { console.log('components/New1/NewView1.vue-StateList vue created response=', response);  })
                .catch((error) => { console.error('components/New1/NewView1.vue-StateList vue created error=', error);   });
            },
        components: { 'state-crud-modal': StateCrudModal1, 'state-list-view': StateListView1,  },
        mounted() {  console.log('components/New1/NewView1.vue-StateList vue Component mounted.');    },
        methods: {    },
    }
</script>

<style scoped>
   
    .role-title {  margin-left: 6px;  height: 30px;    }
</style>