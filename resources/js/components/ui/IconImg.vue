<template>
	<img
		class='img-icon'
		:class='classes'
		:src='src'
		:alt='alt'
	/>
</template>

<script setup>
const props = defineProps({
	alt: {
		type: String,
		required: true,
	},
	src: {
		type: String,
		required: true,
	},
	size: {
		number: String,
		default: 1,
	}
})

const classes = []

switch (props.size) {
	case 1: classes.push('size-1'); break;
	case 2: classes.push('size-2'); break;
}


</script>

<style scoped>
.img-icon.size-1 {
	width: 1rem;
	height: 1rem;
}
.img-icon.size-2 {
	width: 2rem;
	height: 2rem;
}
</style>