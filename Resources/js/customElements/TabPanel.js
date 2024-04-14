class TabPanel {
    panels = [];
    newTag = document.createElement('div');
    tabBar = document.createElement('div');
    panelContainer = document.createElement('div');
    default = 0;
    constructor(tag) {
        this.tabBar.setAttribute('outlook','tab_panel_bar_default');
        this.panelContainer.setAttribute('name','panel_container');
        this.panels = tag.children;
        this.newTag.classList = tag.classList;
        this.newTag.innerHTML = tag.innerHTML;
        if(tag.getAttribute('default')==null) {
            console.warn('It is recommended to set initially displayed Panel');
        }
        else {
            this.default = tag.getAttribute('default');
        }
        for(const panel of this.panels) {
            let tab = document.createElement('button');
            if(panel.nodeName!=="PANEL") {
                console.error('Children node of TabPanel can be only Panel');
                break;
            }
            if(panel.getAttribute('tabname')===null) {
                console.error("One of panels lacks required 'tabname' attribute");
                break;
            }
            tab.innerText = panel.getAttribute('tabname');
            tab.addEventListener('click',() => {
                for (const child of this.panelContainer.children) {
                    if(child.getAttribute('name')!=panel.getAttribute('tabname')) {
                        child.style.display = "none";
                    }
                    else {
                        child.style.display = "block";
                    }
                }
                for (const child of this.tabBar.children) {
                    if(child!=tab) {
                        child.setAttribute('current','false');
                    }
                    else {
                        child.setAttribute('current','true');
                    }
                }
            });
            if(this.tabBar.children.length==this.default) {
                tab.setAttribute("current","true")
            }
            this.tabBar.appendChild(tab);
            new Panel(panel, this.panelContainer, this.default);
        };
        this.outlook(tag.getAttribute('outlook'), tag.getAttribute('includeDefault'));
        const cleanup = this.newTag.getElementsByTagName('panel');
        for(let i = 0; i < cleanup.length; i=0) {
             cleanup[0].remove();
        }
        this.newTag.append(this.tabBar);
        this.newTag.append(this.panelContainer);
        tag.replaceWith(this.newTag);
    }

    outlook(styles, defaultLook) {
        if(styles===null || defaultLook==="true" || defaultLook===null) {
            this.newTag.setAttribute('outlook','tab_panel_default');
            if(styles===null) {
                return;
            }
        }
        for (const style of styles.split(" ")) {
            const element = style.split(":");
            element[1] = element[1].replaceAll(',',' ');
            this.newTag.style.setProperty(element[0], element[1]);
        }
    }
}