import Vue from 'vue';
import * as api from './../../config';

export default {
    state: {
    },
    mutations: {
    },
    actions: {
        getNotificationList: ({dispatch}, payload) => {
            console.log('getNotificationList');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentNotificationList, {params: payload} )
                    .then(response => {
                        console.log('getNotificationList get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('getNotificationList error =', error);
                        reject(error);
                    });
            });
        },
        readNotification: ({dispatch}, payload) => {
            console.log('readNotification');
            return new Promise((resolve, reject) => {
                Vue.http.post(api.readNotification, payload, { showLoadingBar: false } )
                    .then(response => {
                        console.log('readNotification get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('readNotification error =', error);
                        reject(error);
                    });
            });
        },
        getTaskFeedByPagination({dispatch}, payload) {
            console.log('getTaskFeedByPagination');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.currentTaskList, {params: payload} )
                    .then(response => {
                        console.log('getTaskFeedByPagination get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('getTaskFeedByPagination error =', error);
                        reject(error);
                    });
            });
        },
        taskOverviewBarchart({dispatch}) {
            console.log('taskOverviewBarchart');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.taskOverviewBarchart)
                    .then(response => {
                        console.log('taskOverviewBarchart get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('taskOverviewBarchart error =', error);
                        reject(error);
                    });
            });
        },
        taskOverviewDoughnutchart({dispatch}) {
            console.log('taskOverviewDoughnutchart');
            return new Promise((resolve, reject) => {
                Vue.http.get(api.taskOverviewDoughnutchart)
                    .then(response => {
                        console.log('taskOverviewDoughnutchart get http success response.body=', response.body);
                        resolve(response);
                    })
                    .catch(error => {
                        console.error('taskOverviewDoughnutchart error =', error);
                        reject(error);
                    });
            });
        },

    }
}