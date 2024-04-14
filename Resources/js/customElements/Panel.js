class Panel {
    newTag = document.createElement('div');
    constructor(tag, parent, initial) {
        this.newTag.classList = tag.classList;
        this.newTag.innerHTML = tag.innerHTML;
        this.newTag.setAttribute('name',tag.getAttribute('tabname'));
        console.log(tag.children);
        for(const child of tag.children) {
            new CustomElement(child);
        }
        this.outlook(tag.getAttribute('outlook'), tag.getAttribute('includeDefault'));
        if(parent.children.length!=initial) {
            this.newTag.style.display = "none";
        }
        parent.appendChild(this.newTag);
    }

    outlook(styles, defaultLook) {
        if(styles===null || defaultLook==="true" || defaultLook===null) {
            this.newTag.setAttribute('outlook','tab_panel_container_default');
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