<template>
    <header class='container'>
        <div class='wrapper'>
            <h1 class='page-header'>{{ title }}</h1>
        </div>
    </header>
</template>

<script setup>
defineProps({
    title: String
})
</script>

<style scoped>
.page-header {
    padding: 3rem 3rem 2rem;
    text-align: center;
}
</style>
