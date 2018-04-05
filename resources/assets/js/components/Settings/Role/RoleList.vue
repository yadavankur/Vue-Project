<template>
    <div id="role-list">
        <div class="main">
            <div class="container">
                <role-crud-modal></role-crud-modal>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="role-title">
                                    <span class="pull-left">Assigned Roles:
                                        <v-select  id="selectRoles"
                                                   :options="currentAssignedRoles"
                                                   :value="selectedRole.name"
                                                   placeholder="Please select a role..."
                                                   @change="onRoleChanged">
                                        </v-select>
                                    </span>
                                </div>
                            </div>
                            <div id="rolelist" class="panel-collapse collapse in">
                                <ul class="list-group">
                                    <li id="roles-list" class="list-group-item">
                                        <role-list-view :roleNodes="childrenRoles" :selectedRole="selectedRole"></role-list-view>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'
    import RoleListView from './RoleListView.vue'
    import select from 'vue-strap/src/Select'
    import RoleCrudModal from './RoleCrudModal.vue'

    export default {
        data () {
            return {
                selectedRole: {name:''},
                options: [],
            }
        },
        computed: {
            ...mapGetters({
                roles: 'allRoles',
            }),
            ...mapState({
                user: state => state.authUser,
            }),
            currentAssignedRoles() {
                console.log('currentAssignedRoles = ', this.roles.assingedRoles);
                let options = [];
                for (let role in this.roles.assingedRoles) {
                    if (!this.selectedRole.name) this.selectedRole.name = this.roles.assingedRoles[role].name;
                    options.push({value: this.roles.assingedRoles[role].name, label: this.roles.assingedRoles[role].name});
                }
                return options;
            },
            childrenRoles() {
                return this.setChildrenOptions();
            },

        },
        created() {
            console.log('RoleList vue Component created.');
            this.$store.dispatch('setRoles')
                .then((response) => {
                    console.log('RoleList vue created response=', response);
                })
                .catch((error) => {
                    console.log('RoleList vue created error=', error);
                });
        },
        components: {
            'v-select': select,
            'role-list-view': RoleListView,
            'role-crud-modal': RoleCrudModal,
        },
        mounted() {
            console.log('RoleList vue Component mounted.');
            //this.onRoleChanged('Admin');
        },
        methods: {
            setSelectedRole(roleName) {
                console.log('setSelectedRole roleName=', roleName);
                for (let role in this.roles.assingedRoles) {
                    if (this.roles.assingedRoles[role].name === roleName)
                    {
                        this.selectedRole = this.roles.assingedRoles[role];
                        console.log('setSelectedRole selectedRole=', this.selectedRole);
                        return;
                    }
                  }
            },
            onRoleChanged(value)
            {
                console.log('onSelected=', value);
                //this.selectedRole = value;
                this.setSelectedRole(value);
                // this.setChildrenOptions(value);

            },
            setChildrenOptions() {
                if (!this.selectedRole) return;
                let type = this.selectedRole.name;
                console.log('setChildrenOptions=', this.selectedRole);

                let childRoles = null;
                for (let role in this.roles.childRoles)
                {
                    console.log('childRoles=', role);
                    if (type === role)
                    {
                        childRoles = this.roles.childRoles[role];
                    }
                }
                return childRoles;
            }
        },
    }
</script>

<style scoped>
    /*.panel-primary > .panel-heading {*/
        /*color: white;*/
        /*background-color: #0a5b9e;*/
        /*border-color: #0a5b9e;*/
    /*}*/
    /*.panel-heading a {*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle:after {*/
        /*!* symbol for "opening" panels *!*/
        /*font-family: 'Glyphicons Halflings';*/
        /*content: "\e114";*/
        /*float: right;*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle.collapsed:after {*/
        /*!* symbol for "collapsed" panels *!*/
        /*content: "\e080";*/
    /*}*/
    .role-title {
        margin-left: 6px;
        height: 30px;
    }
    .panel > .list-group, .panel > .panel-collapse > .list-group {
        margin-bottom: 0;
        margin-top: 18px;
    }
</style>