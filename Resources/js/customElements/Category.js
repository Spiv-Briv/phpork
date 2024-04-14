class Category {
    newTag = document.createElement('div');
    constructor(tag, parent) {
        const title = tag.getAttribute('header');
        if(title!==null) {
            this.newTag.innerHTML = `<h2>${title}</h2>${tag.innerHTML}`;
        }
        else {
            this.newTag.innerHTML = tag.innerHTML;
        }
        this.newTag.classList = tag.classList;
        const items = tag.children;
        for(const item of items) {
            if(item.nodeName!=="ITEM") {
                console.error('Children node of Category can be only Item');
                break;
            }
            new Item(item, this.newTag);
        }
        this.outlook(tag.getAttribute('outlook'), tag.getAttribute('includeDefault'));
        const cleanup = this.newTag.getElementsByTagName('item');
        for(let i = 0; i < cleanup.length; i=0) {
            cleanup[0].remove();
        }
        parent.appendChild(this.newTag);
    }

    outlook(styles, defaultLook) {
        if(styles===null || defaultLook==="true" || defaultLook===null) {
            this.newTag.setAttribute('outlook','list_category_default');
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