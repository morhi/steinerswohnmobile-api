import axios from "axios";

export const http = axios.create({
    baseURL: process.env.MIX_API
})

export const api = {
    getVehicle: (id) => http.get(`/vehicles/${ id }`).then(res => res.data)
}
