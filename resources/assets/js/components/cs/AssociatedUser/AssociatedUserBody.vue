<template>
    <div>
        <div>
            <table class="table table-condensed table-responsive table-striped">
                <thead>
                <tr>
                    <th class="user">Users</th>
                    <th class="name">Name</th>
                    <th class="action">
                        <span class="btn btn-primary btn-xs pull-right" title="New" @click="onClickNewUser()">
                            <span class="glyphicon glyphicon-plus"></span> NEW
                        </span>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(item, index) in associatedUsers"
                    :key ="item"
                    v-if="item"
                >
                    <td>
                        <Select labelInValue v-model="item.type"
                                @on-change="onUserTypeChanged(item, ...arguments)"
                                placeholder="Please select a type..."
                        >
                            <Option v-for="option in typeOptions" :value="option.value" :key="option" :label="option.label">{{ option.label }}</Option>
                        </Select>
                    </td>
                    <td>
                        <Select labelInValue v-model="item.id"
                                @on-change="onUserOptionChanged(item, ...arguments)"
                                placeholder="Please select a user or group..."
                        >
                            <template v-if="item.type == 'USER'" >
                                <Option v-for="option in associatedUserOptions" :value="option.value" :key="option" :label="option.label">{{ option.label }}</Option>
                            </template>
                            <template v-else >
                                <Option v-for="option in associatedGroupOptions" :value="option.value" :key="option" :label="option.label">{{ option.label }}</Option>
                            </template>
                        </Select>
                    </td>
                    <td>
                        <span class="btn btn-del btn-danger btn-sm" @click="onDeleteUser(item, index)">
                            <span class="glyphicon glyphicon-trash"></span>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table class="table table-condensed table-responsive table-striped">
                <thead>
                <tr>
                    <th class="user">Managers</th>
                    <th class="name">Name</th>
                    <th class="action">
                        <span class="btn btn-primary btn-xs pull-right" title="New" @click="onClickNewManager()">
                            <span class="glyphicon glyphicon-plus"></span> NEW
                        </span>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(item, index) in associatedManagers"
                    :key ="item"
                    v-if="item"
                >
                    <td>
                        <Select labelInValue v-model="item.type"
                                @on-change="onManagerTypeChanged(item, ...arguments)"
                                placeholder="Please select a type..."
                        >
                            <Option v-for="option in typeOptions" :value="option.value" :key="option" :label="option.label">{{ option.label }}</Option>
                        </Select>
                    </td>
                    <td>
                        <Select labelInValue v-model="item.id"
                                @on-change="onManagerOptionChanged(item, ...arguments)"
                                placeholder="Please select a user or group..."
                        >
                            <template v-if="item.type == 'USER'" >
                                <Option v-for="option in associatedUserOptions" :value="option.value" :key="option" :label="option.label">{{ option.label }}</Option>
                            </template>
                            <template v-else >
                                <Option v-for="option in associatedGroupOptions" :value="option.value" :key="option" :label="option.label">{{ option.label }}</Option>
                            </template>
                        </Select>
                    </td>
                    <td>
                        <span class="btn btn-del btn-danger btn-sm" @click="onDeleteManager(item, index)">
                            <span class="glyphicon glyphicon-trash"></span>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'

    export default {
        props: {
            associatedUsers: {
                type: Array,
            },
            associatedManagers: {
                type: Array,
            },
            associatedUserOptions: {
                type: Array,
            },
            associatedGroupOptions: {
                type: Array,
            },
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        data () {
            return {
                typeOptions: [
                    { value: 'USER', label: 'User'},
                    { value: 'GROUP', label: 'Group'},
                ],
                userOptions: [],
                managerOptions: [],
            }
        },
        created() {
            console.log('AssociatedUserBody Component created.');
        },
        components: {
        },
        mounted() {
            console.log('AssociatedUserBody Component mounted.')
        },
        methods: {
            onClickNewManager() {
                console.log('onClickNewManager.');
                this.$emit('onClickNewManager');
            },
            onClickNewUser() {
                console.log('onClickNewUser.');
                this.$emit('onClickNewUser');
            },
            onManagerTypeChanged(item, selVal) {
                console.log('onManagerTypeChanged selVal=', selVal);
                //item.type = {id: selVal.value, name: selVal.label};
                //item.type = selVal.value;
            },
            onUserTypeChanged(item, selVal) {
                console.log('onUserTypeChanged selVal=', selVal);
                //item.type = {id: selVal.value, name: selVal.label};
                //item.type = selVal.value;
            },
            onManagerOptionChanged(item, selVal) {
                console.log('onManagerOptionChanged selVal=', selVal);
            },
            onUserOptionChanged(item, selVal) {
                console.log('onUserOptionChanged selVal=', selVal);
            },
            onDeleteUser(item, index) {
                console.log('onDeleteUser.');
                this.$emit('onDeleteUser', index);
            },
            onDeleteManager(item, index) {
                console.log('onDeleteManager.');
                this.$emit('onDeleteManager', index);
            },
        }
    }
</script>

<style scoped>
    label {
        margin-top: 5px;
    }
    .user {
        width: 40%
    }
    .name {
        width: 40%
    }
    .action {
        width: 20%
    }
</style>
