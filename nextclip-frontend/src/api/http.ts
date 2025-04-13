import axios from 'axios'

const token = '13|A1Ah2scneEDSyRNdnrzxStt3dGRjMok9TsMKwtzOb64f8b9e'

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    Accept: 'application/json',
    Authorization: `Bearer ${token}`,
  },
  withCredentials: true,
})

export default api
