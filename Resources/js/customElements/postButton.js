class PostButton {
    newTag = document.createElement('form');
    constructor(tag) {
        this.newTag.action = "./processor.php";
        this.newTag.method = 'POST';
        const attributes = ['controller','endpoint'];
        for(let attribute of attributes) {
            let element = document.createElement('input');
            element.setAttribute('type','hidden');
            element.setAttribute('name',attribute);
            if(tag.getAttribute(attribute)==null){
                console.error(`PostButton lacks '${attribute}' attribute value`);
                tag.remove();
                return;
            }
            element.setAttribute('value',tag.getAttribute(attribute));
            this.newTag.appendChild(element);
        }
        let text = document.createElement('input');
        text.setAttribute('type','submit');
        text.setAttribute('value', tag.innerText);
        this.newTag.appendChild(text);
        if(tag.id!=="") {
            this.newTag.id = tag.id;
        }
        this.outlook(tag.getAttribute('outlook'), tag.getAttribute('includeDefault'));
        tag.replaceWith(this.newTag);
    }
    outlook(styles, defaultLook) {
        if(styles===null || defaultLook==="true" || defaultLook===null) {
            this.newTag.setAttribute('outlook','button_default');
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