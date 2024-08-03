<!-- componente TreeMap - filho -->
<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    data: {
        type: Array,
        required: true
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
    
    //criando os reangulos
    const createRectangles = (data, x, y, width, height) => {
    if (!Array.isArray(data)) {
        console.error('Erro nao e um array', data);
        return;
    }

    // Calcula o valor total dos dados
    const totalValue = data.reduce((sum, item) => sum + item.value, 0);

    let offsetX = x;
    let offsetY = y;
    
    data.forEach(item => {
        const proportion = item.value / totalValue;
        
        // Calcula a largura e altura do retângulo
        const rectWidth = width * proportion;
        const rectHeight = height;
        
        // Cria o retângulo
        const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
        rect.setAttribute("x", offsetX);
        rect.setAttribute("y", offsetY);
        rect.setAttribute("width", rectWidth);
        rect.setAttribute("height", rectHeight);
        rect.setAttribute("fill", "steelblue");
        svg.appendChild(rect);
        
        // Adiciona o texto ao retângulo
        const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
        text.setAttribute("x", offsetX + 5);
        text.setAttribute("y", offsetY + 20);
        text.setAttribute("fill", "white");
        text.textContent = `${item.name}: R$ ${item.value.toFixed(2)}`;
        svg.appendChild(text);
        
        // Ajusta o offsetY para o próximo retângulo
        offsetY += rectHeight;
        
        // Cria retângulos para os filhos, se houver
        console.log('Children', item.children)
        if (item.children) {
            createRectangles(item.children, x, offsetY, rectWidth, height / item.children.length);
        }
    });
};



    createRectangles(props.data, 0, 0, width, height);
};

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
