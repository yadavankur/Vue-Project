<template>
  <div id="appmenu">
    <header class="header">
        <div class="container">
            <nav class="navbar navbar-blue">
            <div class="container-fluid">
                <div class="navbar-header">
                <a class="navbar-brand"> {{ projectName }}
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li v-for="menu in menus" v-if="menu.permission !== 'H'" :class="{'dropdown' : menu.children, 'disabled' : menu.permission === 'D'}" @click="clickOnLink(menu, $event)" >
                            <template v-if="!menu.children">
                                <router-link :to="menu.link" v-if="menu.permission !== 'H'">{{ menu.name }}</router-link>
                            </template>
                            <template v-else>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ menu.name }} <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li v-for="submenu in menu.children" v-if="submenu.name !== 'Separator'"  @click="setActiveTab(submenu.link)">
                                            <router-link :to="submenu.link"
                                                         v-if="submenu.permission !== 'H'"
                                                         :class="{'disabled' : submenu.permission === 'D'}">
                                                {{ submenu.name }}
                                            </router-link>
                                        </li>
                                        <li v-else role="separator" class="divider"></li>
                                    </ul>
                            </template>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right"  v-if="user.authenticated">
                        <li class="dropdown notifications-menu hidden-xs hidden-sm">
                            <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">
                                <i class="fa fa-bell"></i><span class="label label-warning" v-if="unReadNotifications > 0">{{ unReadNotifications }}</span>
                            </a>
                            <ul class="dropdown-menu" v-if="unReadNotifications > 0">
                                <li class="footer" @click="setActiveTab('/dashboard')">
                                    <router-link to='/dashboard'>You have {{ unReadNotifications }} unread notification(s)</router-link>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown tasks-menu hidden-xs hidden-sm">
                            <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">
                                <i class="fa fa-flag"></i><span class="label label-danger" v-if="overDueTasks > 0">{{ overDueTasks }}</span>
                            </a>
                            <ul class="dropdown-menu"  v-if="overDueTasks > 0">
                                <li class="footer" @click="setActiveTab('/dashboard')">
                                    <router-link to='/dashboard'>You have {{ overDueTasks }} overdue task(s)</router-link>
                                </li>
                            </ul>
                        </li>
                        <li><a >{{ user.role }}</a></li>
                        <li><a >Hi {{ user.name }}</a></li>
                        <li><a href="#" @click.prevent="logout()">Logout</a></li>
                    </ul>
                </div>
            </div>
            </nav>
        </div>
    </header>
  </div>
</template>

<script>
    
    import Vue from 'vue';
    import {mapGetters,mapState,mapActions} from 'vuex'
    import {projectName} from './../config';

    export default 
    {   components:{     },
        data() {  return {  projectName: projectName,  overDueTasks: 0,  unReadNotifications: 0,  timer: null, }  },
        beforeCreate() { console.log('/resources/js/components/AppMenu.vue- AppMenu vue beforeCreated! start'); },
        beforeDestroy() { console.log('/resources/js/components/AppMenu.vue-AppMenu vue beforeDestroy!');
                          this.cancelAutoUpdateBadgeCount();
                        },
        created() {  console.log('/resources/js/components/AppMenu.vue-AppMenu vue created! start');
                     this.$store.dispatch('setMenus')
                     .then((response) => { console.log('/resources/js/components/AppMenu.vue-AppMenu vue created response=', response);
                                           this.getBadgeCount();
                                        })
                     .catch((error) => {  console.error('/resources/js/components/AppMenu.vue-AppMenu vue created error=', error);
                                       });
                  },
        mounted() {   console.log('/resources/js/components/AppMenu.vue-AppMenu Component mounted.');
                      this.timer = setInterval(() => {  this.getBadgeCount()  }, 30000);
                  },            
        computed: { ...mapGetters({   menus: 'allMenus',  }), ...mapState({  user: state => state.authUser, }), },
        methods:  { ...mapActions([ 'selectTab',  'setActiveTab',   ]),
                    logout() { this.$store.dispatch('logoutRequest')
                            .then(() => {  this.$store.dispatch('hideAllNotifications');
                                           this.$router.push({name: 'login'});
                                        });
                             },
                    clickOnLink(menu, event) 
                           {   console.log('/resources/js/components/AppMenu.vue-clickOnLink submenu=', menu);
                               console.log('/resources/js/components/AppMenu.vue-clickOnLink event.target=', event.target);
                               if (menu.permission == 'D')
                                  {  event.preventDefault();    event.stopPropagation();   return false;    }
                            },
                    onNavigateToDashboard() { console.log('/resources/js/components/AppMenu.vue-onNavigateToDashboard');
                                            },
                    getBadgeCount() {  //console.log('/resources/js/components/AppMenu.vue-AppMenu vue getBadgeCount!'); --manoj3
                                       // get count of unread notifications and overdue task
                                      this.$store.dispatch('getBadgeCount')
                                      .then((response) => {  //console.log('/resources/js/components/AppMenu.vue-AppMenu getBadgeCount response=', response); //---manoj5
                                                             this.overDueTasks = response.data.overDueTasks;
                                                             this.unReadNotifications = response.data.unReadNotifications;
                                                          })
                                      .catch((error) => {    console.error('/resources/js/components/AppMenu.vue-AppMenu getBadgeCount error=', error);
                                                             this.cancelAutoUpdateBadgeCount();
                                                             this.logout();
                                                        });
                                    },
                    cancelAutoUpdateBadgeCount() {  console.log('/resources/js/components/AppMenu.vue-AppMenu vue cancelAutoUpdateBadgeCount!');
                                                    clearInterval(this.timer);
                                                 },
                  },
    }
</script>
<style scoped>

.header { background: #0a5b9e;	color: #fff;
	      box-shadow: 0px 3px 5px 0 rgba(0,0,0,0.3) inset;
        }
.navbar-blue {
    margin-top:5px;
    padding: 5px 0;
    background: #0a5b9e;
    color:#fff;
}
.navbar-blue a {   color: white;  }
.navbar {
    border-radius: 0px !important;
    margin-bottom: 0px !important;
}
.nav .open>a, .nav .open>a:focus, .nav .open>a:hover {
    background-color:  rgb(109, 153, 191) !important;
    border-color: #337ab7 !important;
}
.navbar-blue a:hover, a:focus {
    background-color:  rgb(109, 153, 191) !important;
}
.dropdown-menu {
    background-color: #0a5b9e !important;
    color: white !important;
}
.dropdown-menu a {
    color: white !important;
}
.navbar-toggle .icon-bar {
    background-color: white !important;
}

a.disabled,
a.disabled:visited ,
a.disabled:active,
a.disabled:hover {
    color: #999 !important;
    cursor: no-drop;
}
.dropdown-menu {
    a.disabled,
    a.disabled:visited ,
    a.disabled:active,
    a.disabled:hover {
        color: #999 !important;
        background-color: white;
        cursor: no-drop;
    }
}
.nav > li > a i {
    margin-right: 6px;
}
.navbar .nav>li>a>.label {
    position: absolute;
    top: 6px;
    right: 12px;
    text-align: center;
    font-size: 10px;
    padding: 2px 5px;
    border-radius: .25em;
    font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-weight: 500;
    line-height: 12px;
 }
</style>