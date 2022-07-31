import {processing} from '@/services/EventServicesSetup'

export const todoListGet = (params = {}) => processing('/todo/', params, 'get')
export const todoListAddItem = (params = {}) => processing('/todo/', params, 'post')
export const todoListDeleteItem = (code, params = {}) => processing('/todo/' + code + '/', params, 'delete')
export const todoListEditItem = (code, params = {}) => processing('/todo/' + code + '/', params, 'patch')
