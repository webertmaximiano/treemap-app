<template>
    <div :style="parentStyle" class="tree-map-parent">
      <div class="label">
        <span>{{ labelName }}</span>
        <span>{{ labelValue }}</span>
      </div>
      <slot></slot>
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
      default: 'steelblue'
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
  
  const parentStyle = computed(() => ({
    position: 'absolute',
    left: `${props.x}px`,
    top: `${props.y}px`,
    width: `${props.width}px`,
    height: `${props.height}px`,
    backgroundColor: props.color,
    border: '1px solid black',
    padding: '5px', // Add padding to the parent div
    boxSizing: 'border-box'
  }));
  </script>
  
  <style scoped>
  .tree-map-parent {
    display: flex;
    flex-direction: column;
    color: white;
    font-size: 12px;
    overflow: hidden;
  }
  
  .label {
    align-self: flex-start;
    margin-bottom: 5px;
  }
  </style>
  