<!-- componente TreeMap - filho -->
<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    color: {
        type: String,
        default: 'steelblue'
    }
});

const treeMap = ref(null);

const createTreeMap = () => {
    if (!treeMap.value) return;

    const width = 800;
    const height = 600;
    const container = treeMap.value;

    while (container.firstChild) {
        container.removeChild(container.firstChild);
    }

    const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("width", width);
    svg.setAttribute("height", height);
    container.appendChild(svg);

    const createMainRectangles = (data, x, y, width, height) => {
        const totalValue = data.reduce((sum, item) => sum + item.value, 0);
        let offsetX = x;

        data.forEach(item => {
            const proportion = item.value / totalValue;
            const rectWidth = width * proportion;
            const rectHeight = height;
            const rectColor = props.color;

            const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
            rect.setAttribute("x", offsetX);
            rect.setAttribute("y", y);
            rect.setAttribute("width", rectWidth);
            rect.setAttribute("height", rectHeight);
            rect.setAttribute("fill", rectColor);
            svg.appendChild(rect);

            const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
            text.setAttribute("x", offsetX + 5);
            text.setAttribute("y", y + 20);
            text.setAttribute("fill", "white");
            text.textContent = `${item.name}: R$ ${item.value.toFixed(2)}`;
            svg.appendChild(text);

            if (item.children && item.children.length > 0) {
                createChildRectangles(item.children, offsetX, y + 30, rectWidth, height - 30, rectColor);
            }

            offsetX += rectWidth;
        });
    };

    const createChildRectangles = (data, parentX, parentY, parentWidth, parentHeight, parentColor) => {
        const totalValue = data.reduce((sum, item) => sum + item.value, 0);
        let offsetX = parentX;
        let offsetY = parentY;

        data.forEach(item => {
            const proportion = item.value / totalValue;
            const rectWidth = parentWidth;
            const rectHeight = parentHeight * proportion;
            const rectColor = parentColor;
console.log('Cor', parentColor)
            const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
            rect.setAttribute("x", offsetX);
            rect.setAttribute("y", offsetY);
            rect.setAttribute("width", rectWidth);
            rect.setAttribute("height", rectHeight);
            rect.setAttribute("fill", rectColor);
            svg.appendChild(rect);

            const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
            text.setAttribute("x", offsetX + 5);
            text.setAttribute("y", offsetY + 20);
            text.setAttribute("fill", "white");
            text.textContent = `${item.name}: R$ ${item.value.toFixed(2)}`;
            svg.appendChild(text);

            offsetY += rectHeight;
        });
    };

    createMainRectangles(props.data, 0, 0, width, height);
}

onMounted(() => {
    createTreeMap();
});
</script>

<template>
    <div ref="treeMap" class="tree-map"></div>
</template>

<style scoped>
.tree-map {
    width: 100%;
    height: 100%;
    position: relative;
}
</style>
