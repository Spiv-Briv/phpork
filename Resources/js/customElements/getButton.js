class GetButton {
    newTag = document.createElement('form');
    constructor(tag) {
        this.newTag.action = `./${tag.getAttribute('endpoint')}.php`;
        this.newTag.method = 'GET';
        let text = document.createElement('input');
        text.setAttribute('type', 'submit');
        text.setAttribute('value', tag.innerText);
        this.newTag.appendChild(text);
        if (tag.getAttribute('disabled') === "true") {
            text.disabled = true;
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