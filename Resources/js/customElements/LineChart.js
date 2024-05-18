class LineChart extends Element {
    constructor(node, iterator) {
        super(node, "LineChart", iterator, "line_chart_default", 4, ['Y_axis_label', 'X_axis_label', 'width', 'height']);
    }

    render() {
        const widthAttr = this.newElement.getAttribute('width');
        const heightAttr = this.newElement.getAttribute('height');

        let color = "black";
        let backgroundColor = "initial";
        let generalSize = 3;

        
        
        let width = 300;
        let height = 150;
        
        if (widthAttr !== null) {
            width = widthAttr;
            this.newElement.removeAttribute('width');
        }
        if (heightAttr !== null) {
            height = heightAttr;
            this.newElement.removeAttribute('height');
        }
        
        let yLabelBegin = height - 45;
        let yLabelEnd = 15;
        let negator = -1;
        
        if (this.newElement.getAttribute('flip_chart') !== null) {
            yLabelBegin = 15;
            yLabelEnd = height - 45;
            negator = 1;
            this.newElement.removeAttribute('flip_chart');
        }

        const newColor = this.newElement.getAttribute('color');
        const newBackgroundColor = this.newElement.getAttribute('background_color');
        const size = this.newElement.getAttribute('size');
        if(newColor!==null) {
            color = newColor;
            this.newElement.removeAttribute('color');
        }
        if(newBackgroundColor!==null) {
            backgroundColor = newBackgroundColor;
            this.newElement.removeAttribute('background_color');
        }
        if(size!==null) {
            generalSize = size;
            this.newElement.removeAttribute('size');
        }

        let canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        canvas.style.backgroundColor = backgroundColor;
        let ctx = canvas.getContext('2d');
        
        ctx.font = "16px Arial";
        ctx.lineWidth = 2;
        ctx.strokeStyle = color;
        ctx.fillStyle = color;
        
        const xLabel = this.newElement.getAttribute('X_axis_label');
        if (xLabel !== null) {
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            ctx.fillText(xLabel, width / 2, height - 10);
            this.newElement.removeAttribute('X_axis_label');
        }
        const yLabel = this.newElement.getAttribute('Y_axis_label');
        if (yLabel !== null) {
            ctx.rotate(90 * Math.PI / 180);
            ctx.fillText(yLabel, height / 2 - 15, -15);
            ctx.resetTransform();
            this.newElement.removeAttribute('Y_axis_label');
        }

        //Writes chart borders

        ctx.beginPath();
        ctx.moveTo(60, 15);
        ctx.lineTo(60, height - 45);
        ctx.lineTo(width - 20, height - 45);
        ctx.stroke();

        const points = this.element.getElementsByTagName('Point');
        let maxValue = -9007199254740991;
        let minValue = 9007199254740991;

        for (const point of points) {
            const value = point.innerText;
            if (isNaN(value)) {
                console.error('Value is not numeric');
            }
            if (parseFloat(maxValue) < parseFloat(value)) {
                maxValue = value;
            }
            if (parseFloat(minValue) > parseFloat(value)) {
                minValue = value;
            }
        }

        let yLabelMin = parseFloat(minValue);
        let yLabelMax = parseFloat(maxValue);

        const min = this.newElement.getAttribute('min');
        if(min!==null) {
            yLabelMin = parseFloat(min);
            this.newElement.removeAttribute('min');
        }
        const max = this.newElement.getAttribute('max');
        if(max!==null) {
            yLabelMax = parseFloat(max);
            this.newElement.removeAttribute('max');
        }
        let yLabelGap = parseInt((yLabelMax-yLabelMin)/5);
        if(yLabelGap==0) {
            yLabelGap = 1;
        }
        const gap = this.newElement.getAttribute('gap');
        if(gap!==null) {
            yLabelGap = parseFloat(gap);
            this.newElement.removeAttribute('gap');
        }

        if ((yLabelMax - yLabelMin) % yLabelGap != 0) {
            ctx.beginPath();
            ctx.moveTo(65, yLabelEnd);
            ctx.lineTo(55, yLabelEnd);
            ctx.stroke();
            ctx.fillText(yLabelMax, 40, yLabelEnd);
        }

        let chartHeight = height - 45 - 15;
        let chartWidth = width - 60 - 20;
        const chart1pixelsY = chartHeight / (yLabelMax - yLabelMin);

        ctx.textAlign = "end";
        //Vertical Labels
        for (let i = yLabelMin; i <= yLabelMax; i += yLabelGap) {
            ctx.beginPath();
            ctx.moveTo(65, yLabelBegin + negator *  ((chart1pixelsY * i) - yLabelMin * chart1pixelsY));
            ctx.lineTo(55, yLabelBegin + negator *  ((chart1pixelsY * i) - yLabelMin * chart1pixelsY));
            ctx.stroke();
            ctx.fillText(i, 45, yLabelBegin + negator *  ((chart1pixelsY * i) - yLabelMin * chart1pixelsY));
        }

        let pointValues = [];
        const length = points.length;
        for (let i = 0; i < length; i++) {
            const array = new Point(points[0], 0);
            if (array[0] === null && array[1] === null && array[2] === null) {
                console.error("Not all points are properly rendered");
                return;
            }
            pointValues.push(array);
        }
        const chart1pixelsX = chartWidth / (length - 1);
        ctx.textAlign = "center";
        if (pointValues.length == 1) {
            ctx.beginPath();
            ctx.moveTo(width / 2 + 20, height - 50);
            ctx.lineTo(width / 2 + 20, height - 40);
            ctx.stroke();
            ctx.closePath();

            let size = generalSize;
            if(pointValues[i][3]!==null) {
                size = pointValues[i][3];
            }
            ctx.fillText(pointValues[0][0], width / 2 + 20, height - 30);
            ctx.arc(width / 2 + 20, yLabelBegin + negator * ((chart1pixelsY * pointValues[0][1]) - yLabelMin * chart1pixelsY), size, 0, 2 * Math.PI);
            ctx.fill();
        }
        else {
            ctx.beginPath();
            let noValue = false;
            if(pointValues[0][1] === "") {
                noValue = true;
            }
            else {
                ctx.moveTo(60 + chart1pixelsX * 0, yLabelBegin + negator * ((chart1pixelsY * pointValues[0][1]) - yLabelMin * chart1pixelsY));
            }
            for(let i = 1;i <pointValues.length; i++) {
                if(!noValue) {
                    if(pointValues[i][1] === "") {
                        ctx.stroke();
                        noValue = true;
                    }
                }
                else {
                    if(pointValues[i][1] !== "") {
                        ctx.beginPath();
                        noValue = false;
                    }
                }
                ctx.lineTo(60 + chart1pixelsX * i, yLabelBegin + negator * ((chart1pixelsY * pointValues[i][1]) - yLabelMin * chart1pixelsY));
            }
            ctx.stroke();

            for (let i = 0; i < pointValues.length; i++) {
                ctx.beginPath();
                ctx.moveTo(60 + chart1pixelsX * i, height - 50);
                ctx.lineTo(60 + chart1pixelsX * i, height - 40);
                ctx.stroke();
                ctx.closePath();

                ctx.fillText(pointValues[i][0], 60 + chart1pixelsX * i, height - 30);
                if(pointValues[i][2]!==null) {
                    ctx.fillStyle = pointValues[i][2];
                }
                let size = generalSize;
                if(pointValues[i][3]!==null) {
                    size = pointValues[i][3];
                }
                ctx.arc(60 + chart1pixelsX * i, yLabelBegin + negator * ((chart1pixelsY * pointValues[i][1]) - yLabelMin * chart1pixelsY), size, 0, 2 * Math.PI);
                ctx.fill();
                ctx.fillStyle = color;
            }
        }
        this.newElement.appendChild(canvas);
    }
}