<template>
    <div :style="childStyle" class="tree-map-child">
      <div class="label">
        <span>{{ labelName }}</span>
        <span>{{ labelValue }}</span>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, computed } from 'vue';
  
  const props = defineProps({
    width: {
      type: Number,
      required: true
    },
    height: {
      type: Number,
      required: true
    },
    x: {
      type: Number,
      required: true
    },
    y: {
      type: Number,
      required: true
    },
    color: {
      type: String,
      default: 'lightblue'
    },
    labelName: {
      type: String,
      required: true
    },
    labelValue: {
      type: String,
      required: true
    }
  });
  
  const childStyle = computed(() => ({
    position: 'absolute',
    left: `${props.x}px`,
    top: `${props.y}px`,
    width: `${props.width}px`,
    height: `${props.height}px`,
    backgroundColor: props.color,
    border: '1px solid black',
    padding: '5px', // Add padding to the child div
    boxSizing: 'border-box'
  }));
  </script>
  
  <style scoped>
  .tree-map-child {
    display: flex;
    flex-direction: column;
    color: white;
    font-size: 10px;
    overflow: hidden;
  }
  
  .label {
    align-self: flex-start;
    margin-bottom: 5px;
    color: black;
  }
  </style>
  