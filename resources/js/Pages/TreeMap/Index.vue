
<script setup>
import Parent from '@/Pages/TreeMap/Partials/Parent.vue';
import Child from '@/Pages/TreeMap/Partials/Child.vue';

const children = [
  { name: 'Loja 1', value: 21634.90, width: 200, height: 100, x: 50, y: 50, color: 'lightblue' },
  { name: 'Loja 2', value: 21634.90, width: 200, height: 100, x: 300, y: 50, color: 'lightgreen' },
  { name: 'Loja 3', value: 21634.90, width: 200, height: 100, x: 550, y: 50, color: 'lightcoral' },
  { name: 'Loja 4', value: 21634.90, width: 200, height: 100, x: 50, y: 200, color: 'lightpink' },
  { name: 'Loja 5', value: 21634.90, width: 200, height: 100, x: 300, y: 200, color: 'lightyellow' }
];
</script>
<template>
    <Parent
      :width="800"
      :height="600"
      :x="0"
      :y="0"
      color="steelblue"
      labelName="Acre"
      labelValue="R$ 108174.50"
    >
      <Child
        v-for="(child, index) in children"
        :key="index"
        :width="child.width"
        :height="child.height"
        :x="child.x"
        :y="child.y"
        :color="child.color"
        :labelName="child.name"
        :labelValue="`R$ ${child.value.toFixed(2)}`"
      />
    </Parent>
  </template>
