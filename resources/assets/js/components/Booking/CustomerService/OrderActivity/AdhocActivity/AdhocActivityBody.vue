<template>
    <div>
        <div>
            <div class="adhoc-activity-body">
                <!--<div class="form-group">-->
                    <!--<div class="form-group">-->
                        <!--<div><label for="service">Position</label></div>-->
                        <!--<Select v-model="position" placeholder="Please select a position...">-->
                            <!--<Option v-for="item in positionOptions" :value="item.value" :key="item">{{ item.label }}</Option>-->
                        <!--</Select>-->
                    <!--</div>-->
                    <!--<bs-input label="Activity Name" type="text" required  :maxlength="255" :icon="true" v-model="formData.name"></bs-input>-->
                    <!--<bs-input label="Activity Duration" type="text" required  :maxlength="10" :icon="true" v-model="formData.duration"></bs-input>-->
                    <!--<bs-input label="SQL Statement" type="textarea" :maxlength="10240" v-model="formData.sql_statement"></bs-input>-->
                    <!--<bs-input label="Comment" type="textarea" :maxlength="255" :icon="true" v-model="formData.comment"></bs-input>-->
                <!--</div>-->
                <Form :model="formData" label-position="top">
                    <Form-item label="Current Activity Name">
                        <Input :value="currentActivityName" disabled></Input>
                    </Form-item>
                    <Form-item label="Position to current activity">
                        <Select v-model="formData.position" placeholder="Please select a position...">
                            <Option v-for="item in positionOptions" :value="item.value" :key="item">{{ item.label }}</Option>
                        </Select>
                    </Form-item>
                    <Form-item label="Ad hoc Activity Name">
                        <Input v-model="formData.name" placeholder="Please input activity name..."></Input>
                    </Form-item>
                    <Form-item label="Activity Duration">
                        <Input-number v-model="formData.duration" :step="0.1" :min="0"></Input-number>
                    </Form-item>
                    <Form-item label="SQL Statement">
                        <Input v-model="formData.sql_statement" :maxlength="10240" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Please input sql statement..."></Input>
                    </Form-item>
                    <Form-item label="Comment">
                        <Input v-model="formData.comment" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Please input comment..."></Input>
                    </Form-item>
                </Form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            itemData: null,
            order: null,
            currentActivityName: '',
        },
        data () {
            return {
                positionOptions: [
                    {value: 'before', label: 'before'},
                    {value: 'after', label: 'after'},
                ],
                formData: {
                    position: '',
                    name: '',
                    duration: 0,
                    sql_statement: '',
                    comment: '',
                },
            }
        },
        created() {
            console.log('CSAdhocActivityBody Component created.');
        },
        components: {
        },
        mounted() {
            console.log('CSAdhocActivityBody Component mounted. itemData=', this.itemData);
            console.log('CSAdhocActivityBody Component mounted. order=', this.order);
        },
        methods:
        {

        }
    }
</script>

<style scoped>
    /*label {*/
        /*margin-top: 0px !important;*/
        /*margin-bottom: 0px !important;*/
    /*}*/
    .block_title {
        width: 50%;
        text-align: left;
        display: inline-block;
    }
    .block_content {
        display: inline-block;
    }
</style>
