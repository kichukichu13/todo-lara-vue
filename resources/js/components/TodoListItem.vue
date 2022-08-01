<template>
    <li
        class='list-group-item d-flex justify-content-between align-items-start'
        :class='{"list-group-item__loading": isLoading}'
    >
        <template v-if='!editMode'>
            <ButtonCustom
                v-if='isDone'
                :title='"undone"'
                @click='$emit("setUndone", code)'
            >
                <img class='btn-picture done' src='@/assets/icons/again.png' alt='revive'/>
            </ButtonCustom>
            <ButtonCustom
                v-else
                :title='"done"'
                @click='$emit("setDone", code)'
            >
                <img class='btn-picture done' src='@/assets/icons/done.png' alt='done'/>
            </ButtonCustom>
        </template>

        <div
            class='title ms-2 me-auto'
            :class='{"title-urgent": isDone && !editMode, "title-edit": editMode}'
            :contenteditable='editMode'
            @click='initEditMode'
            @blur='doneEdit'
            @keyup.enter="doneEdit"
            @keyup.esc="cancelEdit"
        >
            {{ text }}
        </div>

        <template v-if='!editMode'>
            <ButtonCustom
                v-if='isUrgent'
                :text='"move to common"'
                @click='$emit("moveToCommon", code)'
            >
                <img class='btn-picture back' src='@/assets/icons/back.png' alt='move to common'/>
            </ButtonCustom>
            <ButtonCustom
                v-else
                :title='"move to urgent"'
                @click='$emit("moveToUrgent", code)'
            >
                <img class='btn-picture urgent' src='@/assets/icons/urgent.png' alt='move to urgent'/>
            </ButtonCustom>

            <ButtonCustom
                :title='"remove"'
                @click='deleteItem'
            >
                <img class='btn-picture delete' src='@/assets/icons/delete.png' alt='remove'/>
            </ButtonCustom>
        </template>
    </li>
</template>

<script setup>
import {ref, nextTick} from 'vue'
import ButtonCustom from '@/components/ui/ButtonCustom.vue'

const props = defineProps({
    code: {
        type: String,
        required: true,
    },
    text: {
        type: String,
        required: true,
    },
    isDone: {
        type: Boolean,
        required: true,
    },
    isUrgent: {
        type: Boolean,
        required: true,
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits({
    setUndone: null,
    setDone: null,
    moveToCommon: null,
    moveToUrgent: null,
    deleteItem: null,
    editItem: null,
})

const editMode = ref(false)
const itemValue = ref(props.text)

const deleteItem = () => {
    if (confirm('Are you sure you want to delete the element?')) {
        emit('deleteItem', props.code)
    }
}

const initEditMode = (event) => {
    if (!editMode.value) {
        editMode.value = true
        nextTick(() => event.target.focus())
    }
}

const doneEdit = (event) => {
    if (editMode.value) {
        editMode.value = false
        const newText = event.target.textContent
        if (newText != props.text) emit('editItem', {code: props.code, newText: newText})
    }
}

const cancelEdit = (event) => {
    editMode.value = false
    event.target.textContent = props.text
}

</script>

<style scoped>
.title-edit {
    width: 100%;
    padding: 0 1rem;
}

.btn-picture {
    width: 1rem;
    height: 1rem;
}

.title-urgent {
    text-decoration: line-through;
    color: lightgrey;
}

.btn-picture.done {
    filter: invert(68%) sepia(73%) saturate(315%) hue-rotate(83deg) brightness(90%) contrast(87%);
}

.btn-picture.done:hover {
    filter: invert(47%) sepia(48%) saturate(465%) hue-rotate(63deg) brightness(91%) contrast(87%);
}

.btn-picture.delete {
    filter: invert(49%) sepia(46%) saturate(2113%) hue-rotate(324deg) brightness(110%) contrast(106%);
}

.btn-picture.delete:hover {
    filter: invert(52%) sepia(37%) saturate(1135%) hue-rotate(312deg) brightness(77%) contrast(92%);
}

.btn-picture.urgent {
    filter: invert(85%) sepia(23%) saturate(7314%) hue-rotate(325deg) brightness(106%) contrast(89%);
}

.btn-picture.urgent:hover {
    filter: invert(48%) sepia(90%) saturate(308%) hue-rotate(344deg) brightness(93%) contrast(91%);
}

.btn-picture.back {
    filter: invert(78%) sepia(0%) saturate(94%) hue-rotate(142deg) brightness(86%) contrast(88%);
    transform: scale(-1, 1);
}

.btn-picture.back:hover {
    filter: invert(36%) sepia(12%) saturate(3%) hue-rotate(317deg) brightness(96%) contrast(88%);
}
</style>
