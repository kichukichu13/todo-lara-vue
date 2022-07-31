<template>
	<li
		class='list-group-item d-flex'
	>
		<div class="input-group mb-3">
			<input
				v-model='model'
				type="text"
				class="form-control"
				placeholder="..."
				aria-label="add item input"
			/>
			<button
				class="btn btn-outline-secondary"
				type="button"
				@click='add'
			>
				add
			</button>
		</div>
	</li>
</template>

<script setup>
import { computed } from 'vue'
const emit = defineEmits({
	addItem: null,
})
const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
})
const add = (event) => {
    event.target.blur()
	emit("addItem", model.value)
}
const model = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value);
    }
})

</script>

<style scoped>
</style>
