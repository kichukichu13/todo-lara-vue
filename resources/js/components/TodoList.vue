<template>
    <div class='todo-list__title ms-2 fw-bold'>
        {{ title }}
        <span
            class='urgent'
            :class='{"color-red": isUrgent && itemsCount >= overmuchUrgentCount}'
        >
			({{ itemsCount }})
		</span>
    </div>
    <div class='list-group list-group'>
        <slot/>
    </div>
</template>

<script setup>

defineProps({
    title: {
        type: String,
        required: true,
    },
    itemsCount: {
        type: Number,
        default: 0,
    },
    isUrgent: {
        type: Boolean,
        required: true,
    },
})

const overmuchUrgentCount = 3

</script>

<style scoped>
.todo-list__title {
    margin-bottom: 1rem;
}

.color-red {
    color: red;
}
</style>
