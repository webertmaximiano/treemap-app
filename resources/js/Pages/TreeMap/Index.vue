<script setup>
import { ref, computed } from 'vue';
import Parent from '@/Pages/TreeMap/Partials/Parent.vue';
import Child from '@/Pages/TreeMap/Partials/Child.vue';

const props = defineProps({
    treeMap: {
        type: Object,
        required: true,
    },
});

const states = ref(Object.keys(props.treeMap));
const selectedState = ref(null);

const parentWidth = 800;
const parentHeight = 600;

const selectedStateData = computed(() => {
    if (selectedState.value && props.treeMap[selectedState.value]) {
        return props.treeMap[selectedState.value];
    }
    return null;
});

const children = computed(() => {
    if (selectedStateData.value) {
        return selectedStateData.value.children.map(child => ({
            name: child.store_name,
            value: child.total_amount,
        }));
    }
    return [];
});

const totalValue = computed(() => {
    return children.value.reduce((sum, child) => sum + (child.value || 0), 0);
});

const processedChildren = computed(() => {
    const data = children.value.map(child => {
        const proportion = totalValue.value ? child.value / totalValue.value : 0;
        return {
            ...child,
            proportion,
            width: parentWidth * proportion,
            height: parentHeight * proportion,
            x: 0,
            y: 0, // Ajuste a posição conforme necessário
            color: 'lightblue', // Defina cores diferentes conforme necessário
        };
    });

    // Adicionar posicionamento estático para o exemplo
    let offsetX = 0;
    data.forEach(child => {
        child.x = offsetX;
        child.y = 0; // Atualize conforme necessário
        offsetX += child.width;
    });

    return data;
});

// Computed properties for formatted labels
const formattedStateAmount = computed(() => {
    return `R$ ${selectedStateData.value?.total_amount?.toFixed(2) || '0.00'}`;
});

const formattedChildValues = computed(() => {
    return processedChildren.value.map(child => ({
        ...child,
        formattedValue: `R$ ${child.value?.toFixed(2) || '0.00'}`,
    }));
});

const handleStateChange = (event) => {
    selectedState.value = event.target.value;
};

</script>

<template>
    <div class="min-h-screen flex flex-col items-left justify-center">
        <select @change="handleStateChange" class="p-2 border rounded">
            <option value="">Selecione um estado</option>
            <option v-for="stateId in states" :key="stateId" :value="stateId">
                {{ props.treeMap[stateId].state_name }}
            </option>
        </select>
        <div class="container mx-auto p-4">


            <div v-if="selectedStateData" class="mt-10">
                <Parent class="ml-2" :width="parentWidth" :height="parentHeight" :x="0" :y="0" color="steelblue"
                    :labelName="selectedStateData.state_name" :labelValue="formattedStateAmount">
                    <Child v-for="(child, index) in formattedChildValues" :key="index" :width="child.width"
                        :height="child.height" :x="child.x" :y="child.y" :color="child.color" :labelName="child.name"
                        :labelValue="child.formattedValue" />
                </Parent>
            </div>
        </div>
    </div>

</template>
