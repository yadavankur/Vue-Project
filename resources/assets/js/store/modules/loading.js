import * as types from './../../mutation-types';

export default 
{   state: { isLoading: false, loadingList: [], },
    mutations: 
     {  [types.UPDATE_LOADING] (state, payload) 
        {   console.log('/store/modules/loading.js-mutations UPDATE_LOADING payload=', payload);
            // state.isLoading = payload.isLoading;
            if (payload.isLoading)
            {  state.isLoading = payload.isLoading; state.loadingList.push({text: 'loading...'})  }
            else
            {   state.loadingList.shift();
                if (state.loadingList.length == 0) {  state.isLoading = payload.isLoading; }
            }
        },
    },
    actions: 
    {   updateLoading({commit}, data) 
         {  console.log('/store/modules/loading.js- actions updateLoading data=', data);
            commit({  type: types.UPDATE_LOADING,  isLoading: data });
         },
    }
}