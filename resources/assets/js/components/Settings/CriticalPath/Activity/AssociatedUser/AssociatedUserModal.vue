<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" :backdrop="true">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Assigning users</h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <associated-user-body v-if="isShowModal"
                                        :activityId="activityId"
                                        :serviceId="serviceId"
                                        :associatedUsers="associatedUsers"
                                        :associatedManagers="associatedManagers"
                                        :associatedUserOptions="associatedUserOptions"
                                        :associatedGroupOptions="associatedGroupOptions"
                                        @onClickNewUser="onClickNewUser"
                                        @onDeleteUser="onDeleteUser"
                                        @onClickNewManager="onClickNewManager"
                                        @onDeleteManager="onDeleteManager"
                                        ref="associatedUserBody">
                    </associated-user-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                    <button type="button" class="btn btn-success" @click="onClickSave">Save</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import AssociatedUserBody from './AssociatedUserBody.vue'

    export default {
        props: {
            isShowAssociatedUser: false,
            activityId: '',
            serviceId: '',
        },
        data () {
            return {
                isVisible: false,
                associatedUsers: [],
                associatedManagers: [],
                associatedUserOptions: [],
                associatedGroupOptions: [],
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
            isShowModal() {
                this.isVisible = this.isShowAssociatedUser;
                return this.isVisible;
            }
        },
        created() {
            console.log('AssociatedUserModal Component created.');
        },
        components: {
            'associated-user-body': AssociatedUserBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('AssociatedUserModal Component mounted.');
        },
        methods: {
            onClickCancel() {
                console.log('onClickCancel');
                // clear the arrays
                this.associatedUsers = [];
                this.associatedManagers = [];
                this.$emit('onCloseAssociatedUserModal');
            },
            onClickSave() {
                console.log('onClickSave associatedUsers=', this.associatedUsers);
                console.log('onClickSave associatedManagers=', this.associatedManagers);
                this.$emit('onCloseAssociatedUserModal');
                // send save request
                let payload = {
                    associatedUsers: this.associatedUsers,
                    associatedManagers: this.associatedManagers,
                    activityId: this.activityId,
                    serviceId: this.serviceId,
                };
                this.$store.dispatch('updateAssociatedUsersRequest', payload)
                    .then((response) => {
                        console.log('created updateAssociatedUsers success=', response);
                    })
                    .catch((error) => {
                        console.error('updateAssociatedUsers error=', error);
                    });
            },
            onClickNewUser() {
                console.log('onClickNewUser');
                this.associatedUsers.push({id: '', type: 'USER', name: ''});
            },
            onClickNewManager() {
                console.log('onClickNewManager');
                this.associatedManagers.push({id: '', type: 'USER', name: ''});
            },
            onDeleteUser(index) {
                this.associatedUsers.splice(index, 1);
                //this.associatedUsers[index].active = false;
            },
            onDeleteManager(index) {
                this.associatedManagers.splice(index, 1);
                //this.associatedManagers[index].active = false;
            }
        },
        watch: {
            isShowAssociatedUser(newVal, oldVal) {
                console.log('watching -> isShowAssociatedUser = newVal', newVal);
                console.log('watching -> isShowAssociatedUser = oldVal', oldVal);
                if (newVal)
                {
                    // get user options
                    this.$store.dispatch('getCpmActivityUserOptions')
                        .then((response) => {
                            console.log('created getCpmActivityUserOptions success=', response);
                            this.associatedUserOptions = response.body.users;
                        })
                        .catch((error) => {
                            console.error('getCpmActivityUserOptions error=', error);
                        });
                    // get role options
                    this.$store.dispatch('getCpmActivityGroupOptions')
                        .then((response) => {
                            console.log('created getCpmActivityGroupOptions success=', response);
                            this.associatedGroupOptions = response.body.groups;
                        })
                        .catch((error) => {
                            console.error('getCpmActivityGroupOptions error=', error);
                        });
                    // get associatedUsers
                    this.$store.dispatch('getCpmActivityAssociatedUsers', this.activityId)
                        .then((response) => {
                            console.log('created getCpmActivityAssociatedUsers success=', response);
                            this.associatedUsers = response.body.users;
                        })
                        .catch((error) => {
                            console.error('getCpmActivityUserOptions error=', error);
                        });
                    // get associatedManagers
                    this.$store.dispatch('getCpmActivityAssociatedManagers', this.activityId)
                        .then((response) => {
                            console.log('created getCpmActivityAssociatedManagers success=', response);
                            this.associatedManagers = response.body.managers;
                        })
                        .catch((error) => {
                            console.error('getCpmActivityUserOptions error=', error);
                        });
                }
            }
        }
    }
</script>

<style scoped>
    /*.modal-header {*/
        /*padding: 15px;*/
        /*border-bottom: 1px solid #e5e5e5;*/
        /*color: white !important;*/
        /*background-color: #0a5b9e !important;*/
        /*border-color: #0a5b9e;*/
        /*border-top-right-radius: 3px;*/
        /*border-top-left-radius: 3px;*/
    /*}*/
</style>
