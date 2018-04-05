<template>
    <div class="cs-associated-jobs-detail-row" @click="onClick">
        <div class="inline field">
            <label>Original Delivery Date: </label>
            <span>{{formatDate(rowData.DELIVERY_DATE, 'DD/MMM/YYYY')}}</span>
        </div>
        <div class="inline field">
            <label>Confirmed Date: </label>
            <span>{{formatDate(rowData.confirmed_date, 'DD/MMM/YYYY')}}</span>
        </div>
        <div class="inline field">
            <label>Confirmed By: </label>
            <span>{{rowData.confirmed_date ? rowData.updated_by.name : ''}}</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            rowData: {
                type: Object,
                required: true
            },
            rowIndex: {
                type: Number
            }
        },
        methods: {
            onClick (event) {
                console.log('cs-associated-jobs-detail-row: on-click', event.target)
            }
        },
    }
</script>
<style scoped>
    .notes {
        height: 150px;
        overflow-y: scroll;
    }
</style>
