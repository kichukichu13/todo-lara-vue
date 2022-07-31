import axios from 'axios'
import { showError } from '@/helpers/notifications'

const apiClient = (changedParams, method = 'get') => {
	const params = {
		baseURL: "",
		withCredentials: false,
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		method: method,
	}

	if (changedParams && Object.entries(changedParams).length) {
		for (const paramName in changedParams) params[paramName] = changedParams[paramName]
	}

	return axios.create(params)
}

export const processing = (url, { data, loading, errors, defaultProcessing, clientParams }, method = 'get') => {
	if (!defaultProcessing) defaultProcessing = true
	const axiosInstance = apiClient(clientParams || {}, method)

	const requestParams = {url: url}
	if (data && (Object.entries(data).length || data instanceof FormData)) requestParams['data'] = data
	const axiosResponsePromise = axiosInstance.request(requestParams)

	return defaultProcessing ? defaultProcessingExecute(axiosResponsePromise, loading, errors) : axiosResponsePromise
}

const defaultProcessingExecute = (axiosResponsePromise, loading = false, errors = false) => {
	const loadingDefined = (typeof loading === 'object' && typeof loading?.value === 'boolean')
	const errorsDefined = (typeof errors === 'object')

	if (loadingDefined) loading.value = true

	return new Promise((resolve, reject) => {
		axiosResponsePromise.then((axiosResponse) => {
			const data = axiosResponse.data

			if (data?.success) {
				return resolve(data?.data ? data?.data : true)
			} else {
				if (errorsDefined) {
					if (data?.data) {
						if (typeof data.data === 'string' || typeof data.data === 'number') {
							errors.value = { '-': data.data }
						} else if (typeof data.data === 'object') {
							for (const fieldKey in data.data) {
								if (Object.prototype.hasOwnProperty.call(data.data, fieldKey)) {
									//нужно добавить еще какую нибудь проверку, которая точно обозначит что пришел Ref
									if (Object.prototype.hasOwnProperty.call(errors, 'value')) {
										errors.value[fieldKey] = data.data[fieldKey]
									} else {
										errors[fieldKey] = data.data[fieldKey]
									}
								}
							}
						} else {
							errors.value = data.data
						}
					} else {
						errors.value = { '-': 'Ошибка! Не обработанная ошибка' }
					}
				} else if (data?.data) {
					showError(data.data)
				}

				return reject(data.data)
			}
		}).catch((error) => {
			if (errorsDefined) {
				errors.value = { '-': error }
			} else {
				showError(error)
			}

			return reject(error)
		}).finally(() => {
			if (loadingDefined) loading.value = false
		})
	})
}
