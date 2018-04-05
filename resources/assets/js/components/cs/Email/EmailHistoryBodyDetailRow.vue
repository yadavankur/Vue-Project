<template>
    <div class="email-history-body-detail-row" @click="onClick">
        <div class="inline field">
            <label>Type: </label>
            <span>{{rowData.type}}</span>
        </div>
        <div class="inline field">
            <label>Email(To): </label>
            <span>{{rowData.to}}</span>
        </div>
        <div class="inline field">
            <label>Email Content: </label>
            <div class="notes" v-html="rowData.body"></div>
        </div>
        <div class="inline field">
            <label>Attachment: </label>
            <span>{{rowData.attached_asset_id}}</span>
        </div>
        <div class="inline field">
            <label>Status: </label>
            <span>{{rowData.status}}</span>
        </div>
        <div class="inline field">
            <label>Subject: </label>
            <span>{{rowData.subject}}</span>
        </div>
        <div class="inline field">
            <label>Date Sent: </label>
            <span>{{rowData.updated_at}}</span>
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
                console.log('email-history-body-detail-row: on-click', event.target)
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
