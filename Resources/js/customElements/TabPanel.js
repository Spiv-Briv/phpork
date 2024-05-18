class TabPanel extends Element {
    bar;
    panelContainer;
    constructor(node, iterator) {
        super(node, "TabPanel", iterator, "tab_panel_default", 5, ["initial"],[],[],["Panel"]);
        this.bar = document.createElement('div');
        this.panelContainer = document.createElement('div');
        if(this.default_outlook) {
            this.bar.setAttribute("outlook","tab_panel_bar_default");
            this.panelContainer.setAttribute("outlook","tab_panel_container_default");
        }
        this.render();
    }

    render() {
        let panels = this.element.getElementsByTagName('Panel');
        const length = panels.length;
        if(length!=this.element.children.length) {
            console.warn(`There is unallowed tag. (${this.iteration} of TabPanel Tag)`);
        }
        this.newElement.innerHTML = "";
        for(let i=0; i<length; i++) {
            let button = document.createElement("button");
            button.innerText = panels[0].getAttribute('tabname');
            button.addEventListener('click', () => {
                for(let j=0;j<this.panelContainer.children.length;j++) {
                    if(j==i) {
                        this.bar.children[j].setAttribute("current","true");
                        console.log(this.bar.children[j]);
                        this.panelContainer.children[j].style.display = "block";
                    }
                    else {
                        this.bar.children[j].removeAttribute("current")
                        this.panelContainer.children[j].style.display = "none";
                    }
                }
            })

            const panel = new Panel(panels[0], i);
            if(panel.returnCode<200) {
                if(
                    (this.newElement.getAttribute("initial")===null&&i==0)||
                    (this.newElement.getAttribute("initial")!==null&&this.newElement.getAttribute("initial")==(i+1))
                ) {
                    button.setAttribute("current","true");
                    panel.newElement.style.display = "block";
                }
                this.panelContainer.appendChild(panel.newElement);
                this.bar.appendChild(button);
                this.newElement.appendChild(this.bar);
                this.newElement.appendChild(this.panelContainer);
            }
        }
    }
}