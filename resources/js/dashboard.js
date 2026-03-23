(function () {
    const chartData = window.dashboardToneData || [
        { day: 'День 1', positive: 80, negative: 10 },
        { day: 'День 2', positive: 90, negative: 9 },
        { day: 'День 3', positive: 95, negative: 9 },
        { day: 'День 4', positive: 62, negative: 5 },
        { day: 'День 5', positive: 84, negative: 18 },
        { day: 'День 6', positive: 70, negative: 8 },
        { day: 'День 7', positive: 72, negative: 9 }
    ];

    const chartContainer = document.getElementById('toneChartD3');
    const tooltip = document.getElementById('chartTooltip');

    if (!chartContainer || typeof d3 === 'undefined') {
        return;
    }

    function animateLine(pathSelection, duration = 1200, delay = 0) {
        const node = pathSelection.node();
        if (!node) return;

        const totalLength = node.getTotalLength();

        pathSelection
            .attr('stroke-dasharray', `${totalLength} ${totalLength}`)
            .attr('stroke-dashoffset', totalLength)
            .transition()
            .duration(duration)
            .delay(delay)
            .ease(d3.easeCubicOut)
            .attr('stroke-dashoffset', 0);
    }

    function createTooltipContent(datum) {
        return `
            <div><strong>${datum.day}</strong></div>
            <div>Позитивные: ${datum.positive}%</div>
            <div>Негативные: ${datum.negative}%</div>
        `;
    }

    function showTooltip(event, datum) {
        tooltip.innerHTML = createTooltipContent(datum);
        tooltip.style.opacity = '1';

        const offsetX = 14;
        const offsetY = -20;

        tooltip.style.left = `${event.pageX + offsetX}px`;
        tooltip.style.top = `${event.pageY + offsetY}px`;
    }

    function moveTooltip(event) {
        const offsetX = 14;
        const offsetY = -20;

        tooltip.style.left = `${event.pageX + offsetX}px`;
        tooltip.style.top = `${event.pageY + offsetY}px`;
    }

    function hideTooltip() {
        tooltip.style.opacity = '0';
    }

    function renderChart() {
        chartContainer.innerHTML = '';

        const containerWidth = chartContainer.clientWidth || 500;
        const containerHeight = chartContainer.clientHeight || 190;

        const margin = {
            top: 10,
            right: 16,
            bottom: 40,
            left: 48
        };

        const width = containerWidth;
        const height = containerHeight;

        const innerWidth = width - margin.left - margin.right;
        const innerHeight = height - margin.top - margin.bottom;

        const svg = d3
            .select(chartContainer)
            .append('svg')
            .attr('class', 'chart-svg')
            .attr('viewBox', `0 0 ${width} ${height}`)
            .attr('preserveAspectRatio', 'xMidYMid meet');

        const root = svg
            .append('g')
            .attr('transform', `translate(${margin.left},${margin.top})`);

        const x = d3
            .scalePoint()
            .domain(chartData.map(d => d.day))
            .range([0, innerWidth])
            .padding(0.2);

        const y = d3
            .scaleLinear()
            .domain([0, 100])
            .nice()
            .range([innerHeight, 0]);

        const xGrid = d3
            .axisBottom(x)
            .tickSize(-innerHeight)
            .tickFormat('');

        const yGrid = d3
            .axisLeft(y)
            .ticks(5)
            .tickSize(-innerWidth)
            .tickFormat('');

        root.append('g')
            .attr('class', 'grid')
            .attr('transform', `translate(0, ${innerHeight})`)
            .call(xGrid);

        root.append('g')
            .attr('class', 'grid')
            .call(yGrid);

        const xAxis = d3.axisBottom(x);
        const yAxis = d3.axisLeft(y).ticks(5);

        root.append('g')
            .attr('class', 'axis')
            .attr('transform', `translate(0, ${innerHeight})`)
            .call(xAxis);

        root.append('g')
            .attr('class', 'axis')
            .call(yAxis);

        const linePositive = d3
            .line()
            .x(d => x(d.day))
            .y(d => y(d.positive))
            .curve(d3.curveMonotoneX);

        const lineNegative = d3
            .line()
            .x(d => x(d.day))
            .y(d => y(d.negative))
            .curve(d3.curveMonotoneX);

        const positivePath = root.append('path')
            .datum(chartData)
            .attr('class', 'line-positive')
            .attr('d', linePositive);

        const negativePath = root.append('path')
            .datum(chartData)
            .attr('class', 'line-negative')
            .attr('d', lineNegative);

        animateLine(positivePath, 1300, 0);
        animateLine(negativePath, 1300, 180);

        root.selectAll('.point-positive')
            .data(chartData)
            .enter()
            .append('circle')
            .attr('class', 'point-positive')
            .attr('cx', d => x(d.day))
            .attr('cy', d => y(d.positive))
            .attr('r', 0)
            .transition()
            .delay((d, i) => 300 + i * 80)
            .duration(350)
            .ease(d3.easeBackOut.overshoot(1.7))
            .attr('r', 3.5);

        root.selectAll('.point-negative')
            .data(chartData)
            .enter()
            .append('circle')
            .attr('class', 'point-negative')
            .attr('cx', d => x(d.day))
            .attr('cy', d => y(d.negative))
            .attr('r', 0)
            .transition()
            .delay((d, i) => 450 + i * 80)
            .duration(350)
            .ease(d3.easeBackOut.overshoot(1.7))
            .attr('r', 3.5);

        const hoverLine = root.append('line')
            .attr('class', 'hover-line')
            .attr('y1', 0)
            .attr('y2', innerHeight);

        root.selectAll('.hover-capture')
            .data(chartData)
            .enter()
            .append('rect')
            .attr('class', 'hover-capture')
            .attr('x', d => x(d.day) - 18)
            .attr('y', 0)
            .attr('width', 36)
            .attr('height', innerHeight)
            .on('mouseenter', function (event, d) {
                hoverLine
                    .attr('x1', x(d.day))
                    .attr('x2', x(d.day))
                    .style('opacity', 1);

                showTooltip(event, d);
            })
            .on('mousemove', function (event) {
                moveTooltip(event);
            })
            .on('mouseleave', function () {
                hoverLine.style('opacity', 0);
                hideTooltip();
            });

        svg.append('text')
            .attr('class', 'axis-label')
            .attr('x', width / 2)
            .attr('y', height - 6)
            .attr('text-anchor', 'middle')
            .text('Дни');

        svg.append('text')
            .attr('class', 'axis-label')
            .attr('transform', 'rotate(-90)')
            .attr('x', -(height / 2))
            .attr('y', 14)
            .attr('text-anchor', 'middle')
            .text('Процент (%)');
    }

    let resizeTimer = null;

    function debounceRender() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(renderChart, 150);
    }

    document.addEventListener('DOMContentLoaded', renderChart);
    window.addEventListener('resize', debounceRender);
})();