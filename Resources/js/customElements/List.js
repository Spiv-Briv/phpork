class List {
    newTag = document.createElement('div');
    constructor(tag) {
        const title = tag.getAttribute('header');
        if(title!==null) {
            this.newTag.innerHTML = `<h2>${title}</h2>${tag.innerHTML}`;
        }
        else {
            this.newTag.innerHTML = tag.innerHTML;
        }
        this.newTag.classList = tag.classList;
        const categories = tag.children;
        for(const category of categories) {
            if(category.nodeName!=="CATEGORY") {
                console.error('Children node of List can be only Category');
                break;
            }
            new Category(category, this.newTag);
        }
        this.outlook(tag.getAttribute('outlook'), tag.getAttribute('includeDefault'));
        const cleanup = this.newTag.getElementsByTagName('category');
        for(let i = 0; i < cleanup.length; i=0) {
            cleanup[0].remove();
        }
        tag.replaceWith(this.newTag);
    }

    outlook(styles, defaultLook) {
        if(styles===null || defaultLook==="true" || defaultLook===null) {
            this.newTag.setAttribute('outlook','list_default');
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