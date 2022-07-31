require('./bootstrap');
import { createApp } from 'vue'
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"
import './assets/main.css'
import TodoLists from './components/TodoLists'


const app = createApp({})

app.component('todo-lists', TodoLists)

app.mount('#app')