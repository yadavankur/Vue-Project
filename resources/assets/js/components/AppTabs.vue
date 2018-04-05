<template>
  <div id="apptabs">
    <div class="container">
        <div class="tabs is-medium">
            <ul >
                <li
                    v-for="tab in tabs" :class="{'is-active': tab.isActive}"
                    v-if="tab.permission !== 'H' && tab.permission !== 'D'"
                    @click="selectTab(tab)" >
                    <router-link :to="tab.link" v-if="tab.isGShow|| selectedOrder"
                                 :class="{ 'disabled' : tab.permission === 'D'}">
                        {{ tab.name }}
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
  </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import * as api from './../config';

    export default 
    {   components: {        },
        computed: {   ...mapGetters({  tabs: 'allTabs', selectedOrder: 'selectedOrder'   }),
                      ...mapState({  user: state => state.authUser,   }),
                  },
        mounted() {  console.log('/components/AppTabs.vue- mounted router.path=', this.$route.path);
                     console.log('/components/AppTabs.vue- mounted tabs=', this.tabs);
                  },
        beforeCreate() { console.log('/components/AppTabs.vue- beforeCreate!');  },
        created() {  console.log('/components/AppTabs.vue- created!');
                     this.$store.dispatch('setTabs')
                     .then((response) => 
                     {   console.log('/components/AppTabs.vue-created response=', response);
                         this.setActiveTab(this.$route.path);
                         if (this.$route.path == '/') { this.$router.push( {path: api.homeUrlPath } );  }
                         else this.$router.push( {path: this.$route.path} );
                      })
                     .catch((error) => { console.log('/components/AppTabs.vue-created error=', error);   });
                  },
          data() {  return {     }  },
         methods: {  ...mapActions([ 'selectTab', 'setActiveTab',  ]),  },

    }
</script>
<style lang="scss" src='bulma\bulma.sass' scoped>
</style>
<style scoped>
    .tabs li.is-active a {
        border-bottom-color: #0a5b9e !important;
        color: #0a5b9e !important;
    }
</style>