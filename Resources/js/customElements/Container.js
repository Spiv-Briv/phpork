class Container {
    newTag = document.createElement('div');
    constructor(tag) {
        this.newTag.innerHTML = tag.innerHTML;
        if(tag.classList.length>0) {
            this.newTag.classList = tag.classList;
        }
        this.outlook(tag.getAttribute('outlook'), tag.getAttribute('includeDefault'));
        tag.replaceWith(this.newTag);
    }

    outlook(styles, defaultLook) {
        if(styles===null || defaultLook==="true" || defaultLook===null) {
            this.newTag.setAttribute('outlook','container_default');
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