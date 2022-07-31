<template>
	<button class='btn-clean' :title='title'>
		<slot />
	</button>
</template>

<script setup>
defineProps({
	title: String
})
</script>

<style scoped>
.btn-clean {
	border: none;
	background: transparent;
}
</style>