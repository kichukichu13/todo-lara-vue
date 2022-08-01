<template>
    <div>
        <PageHeader :title='"Todo list"'/>

        <div
            v-if="firstLoading || loading"
            class="loader d-flex justify-content-center"
            :class='{"first-loading": firstLoading}'
        >
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <main v-if="!firstLoading">
            <div class='container'>
                <div class='section-wrapper'>
                    <div class='row'>
                        <div class='col-6'>
                            <TodoList
                                :title='"Todos"'
                                :itemsCount='itemsCommon.length'
                                :isUrgent='false'
                            >
                                <TodoListItem
                                    v-for='item in itemsCommon'
                                    :key="item.code"
                                    :code='item.code'
                                    :text='item.text'
                                    :isDone='!!item.isDone'
                                    :isUrgent='false'
                                    @setUndone='setUndone'
                                    @setDone='setDone'
                                    @moveToUrgent='moveToUrgent'
                                    @deleteItem='deleteItem'
                                    @editItem='editItem'
                                />
                                <TodoListItemAdd
                                    v-model="addValue"
                                    @addItem='addItem'
                                />
                            </TodoList>
                        </div>
                        <div class='col-6'>
                            <TodoList
                                :title='"Urgent Todos"'
                                :itemsCount='itemsUrgent.length'
                                :isUrgent='true'
                            >
                                <TodoListItem
                                    v-for='item in itemsUrgent'
                                    :key="item.code"
                                    :code='item.code'
                                    :text='item.text'
                                    :isDone='!!item.isDone'
                                    :isUrgent='true'
                                    @setUndone='setUndone'
                                    @setDone='setDone'
                                    @moveToCommon='moveToCommon'
                                    @deleteItem='deleteItem'
                                    @editItem='editItem'
                                />
                            </TodoList>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <div class='container footer'>
            <a target="_blank" href="https://icons8.com/icon/o7rLmdJXolvS/back">Back</a> icon by <a target="_blank"
                                                                                                    href="https://icons8.com">Icons8</a>
            <a target="_blank" href="https://icons8.com/icon/4nwoasigJtPY/важный-файл">Важный файл</a> icon by <a
            target="_blank" href="https://icons8.com">Icons8</a>
            <a target="_blank" href="https://icons8.com/icon/Wpih9C5odsp9/удалить-навсегда">Удалить навсегда</a> icon by
            <a target="_blank" href="https://icons8.com">Icons8</a>
            <a target="_blank" href="https://icons8.com/icon/2DqRGyAK7QkT/отправить-файл">Отправить файл</a> icon by <a
            target="_blank" href="https://icons8.com">Icons8</a>
            <a target="_blank" href="https://icons8.com/icon/LWtVTYCpOSIW/редактировать-свойство">Редактировать
                свойство</a> icon by <a target="_blank" href="https://icons8.com">Icons8</a>
            <a target="_blank"
               href="https://icons8.com/icon/2DKIvbRrRla6/verified-check-circle-for-approved-valid-content">Verified
                check circle for approved valid content</a> icon by <a target="_blank"
                                                                       href="https://icons8.com">Icons8</a>
            <a target="_blank" href="https://icons8.com/icon/1457/перезапуск">Перезапуск</a> icon by <a target="_blank"
                                                                                                        href="https://icons8.com">Icons8</a>
        </div>
    </div>

</template>

<script setup>
import {onMounted, ref} from 'vue'
import {todoListAddItem, todoListGet, todoListDeleteItem, todoListEditItem} from '../services/EventServices.js'
import TodoList from '@/components/TodoList.vue'
import TodoListItem from '@/components/TodoListItem.vue'
import TodoListItemAdd from '@/components/TodoListItemAdd.vue'
import PageHeader from '@/components/ui/PageHeader.vue'
import {showError, showSuccess} from '@/helpers/notifications'

const items = [
    {code: 'qwe', text: 'Что то прям интересное', isDone: false, isUrgent: false},
    {
        code: 'wer',
        text: 'Опопопо по по поп оп оп по оп оп опоп по о по поопоп оп оп опопопоопопоп по по по поп ',
        isDone: true,
        isUrgent: false
    },
    {code: 'ert', text: 'Urgent Item 1', isDone: false, isUrgent: true},
    {code: 'asd', text: 'Urgent Item 2', isDone: false, isUrgent: true},
    {code: 'zxc', text: 'Urgent Item 3', isDone: false, isUrgent: true},
]

const addValue = ref('')
const loading = ref(false)
const firstLoading = ref(true)
const itemsCommon = ref([])
const itemsUrgent = ref([])

onMounted(() => {
    updateByPromise(todoListGet({loading: firstLoading}))
})

const setUndone = (code) => {
    updateByPromise(todoListEditItem(code, {data: {isDone: false}, loading: loading}))
}
const setDone = (code) => {
    updateByPromise(todoListEditItem(code, {data: {isDone: true}, loading: loading}))
}
const moveToCommon = (code) => {
    updateByPromise(todoListEditItem(code, {data: {isUrgent: false}, loading: loading}))
}
const moveToUrgent = (code) => {
    updateByPromise(todoListEditItem(code, {data: {isUrgent: true}, loading: loading}))
}
const editItem = ({code, newText}) => {
    console.log(code, newText, 'editItem')
    updateByPromise(todoListEditItem(code, {data: {text: newText}, loading: loading}))
}
const deleteItem = (code) => {
    updateByPromise(todoListDeleteItem(code, {loading: loading}))
}
const addItem = (text) => {
    const promise = todoListAddItem({data: {text: text}, loading: loading})
    updateByPromise(promise)
    promise.then(() => {
        addValue.value = ''
    })
}

const updateByPromise = (promiseResult) => {
    promiseResult.then((data) => {
        if (data?.items && data.items?.length) {
            const tmpItemsCommon = []
            const tmpItemsUrgent = []
            for (const item of data.items) {
                if (item.isUrgent) tmpItemsUrgent.push(item)
                else tmpItemsCommon.push(item)
            }
            itemsCommon.value = tmpItemsCommon
            itemsUrgent.value = tmpItemsUrgent
        }
    })
}

</script>

<style scoped>
.loader {
    padding: 7rem;
    position: fixed;
    z-index: 3;
    width: 100vw;
    height: 100vh;
    background-color: #ffffffad;
}

.loader.first-loading {
    background-color: #fff;
}

.footer {
    position: fixed;
    padding: 10px 10px 0 10px;
    bottom: 0;
    width: 100%;
}
</style>
