class ProgressBar extends Element
{
    static items = [];
    constructor(node, iterator) {
        super(node, "ProgressBar", iterator,"progress_bar_default", 4, ['description','name', 'value','bar_class'], [], ['min','max']);
        ProgressBar.items.push(this.newElement);
    }

    render() {
        let bar = document.createElement("div");
        let text = document.createElement("div");
        if(this.element.getAttribute('prevent_default')===null) {
            bar.setAttribute('outlook','bar_default');
            text.setAttribute('outlook','text_default');
        }
        if(this.element.getAttribute('hide_value')!==null) {
            text.style.display = "none";
        }
        this.newElement.appendChild(bar);
        this.newElement.appendChild(text);
        ProgressBar.setValue(this.element.getAttribute('value'), this.newElement);
    }

    static setValue(value, node) {
        const max = node.getAttribute('max');
        const min = node.getAttribute('min');
        if(parseInt(value)>parseInt(max)) {
            console.error(`Value ${value} can't be greater than ${max}`);
            return;
        }
        if(parseInt(value)<parseInt(min)) {
            console.error(`Value ${value} can't be smaller than ${min}`);
            return;
        }
        node.setAttribute('value', (value-min)*100/(max-min));
        node.children[0].style.width = (value-min)*100/(max-min) + "%";
        node.children[1].innerText = value;
        console.log(`Changed value to ${value} (${(value-min)*100/(max-min)}%)`);
    }
}