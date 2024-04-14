class Item {
    newTag = document.createElement('div');
    constructor(tag, parent) {
        this.newTag.innerHTML = tag.innerHTML;
        this.newTag.classList = tag.classList;
        this.outlook(tag.getAttribute('outlook'), tag.getAttribute('includeDefault'));
        parent.appendChild(this.newTag);
    }

    outlook(styles, defaultLook) {
        if(styles===null || defaultLook==="true" || defaultLook===null) {
            this.newTag.setAttribute('outlook','list_category_item_default');
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