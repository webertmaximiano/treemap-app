<script setup>
import { computed } from 'vue';
import TreeMap from './TreeMap.vue';

const props = defineProps({
    treeMap: Object
});

const reportData = computed(() => JSON.parse(props.treeMap.reportData));
</script>
<template>
    <div>
        <h1>{{ treeMap.name }}</h1>
        <TreeMap :data="reportData" />
    </div>
</template>

<style scoped>
/* Adicione estilo conforme necessário */
</style>