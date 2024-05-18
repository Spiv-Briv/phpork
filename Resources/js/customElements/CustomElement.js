class Element {
    outlookName;

    //renders
    RENDER_NONE = 0;
    RENDER_HIDDEN = 1;
    RENDER_REWRITE_TEXT = 2;
    RENDER_REWRITE_HTML = 3;
    RENDER_IN_SUBCLASS = 4;
    RENDER_IN_SUBCLASS_AFTER_CONSTRUCTOR = 5;

    //return codes
    SUCCESS = 0;

    NO_RECOMMENDED_ATTRIBUTES = 100;
    NO_RECOMMENDED_CHILDREN = 101;

    NO_REQUIRED_ATTRIBUTES = 200;
    NO_REQUIRED_CHILDREN = 201;


    requiredAttributes = [];
    recommendedAttributes = [];
    requiredChildren = [];
    recommendedChildren = [];

    element = null;
    newElement = document.createElement('div');

    elementName;
    iteration;

    default_outlook = true;

    renderStyle = this.RENDER_REWRITE_HTML;
    returnCode = this.SUCCESS;

    constructor(
        node,
        elementName,
        iteration,
        outlookName = "element_default",
        renderStyle = this.RENDER_REWRITE_HTML,
        recommendedAttributes = [],
        recommendedChildren = [],
        requiredAttributes = [],
        requiredChildren = []
    ) {
        this.element = node;

        this.elementName = elementName;
        this.iteration = iteration;
        this.outlookName = outlookName;
        this.renderStyle = renderStyle;

        this.recommendedAttributes = recommendedAttributes;
        this.recommendedChildren = recommendedChildren;
        this.requiredAttributes = requiredAttributes;
        this.requiredChildren = requiredChildren;

        this.returnCode = this.checkRecommendedAttributes();
        this.returnCode = this.checkRecommendedChildren();
        this.returnCode = this.checkRequiredAttributes();
        this.returnCode = this.checkRequiredChildren();

        if (this.returnCode < 200) {
            this.transferAttributes();
            this.transferChildren();
            this.createOutlook();
            this.element.replaceWith(this.newElement);
        }
    }

    checkRequiredAttributes() {
        let undefinedAttributes = [];
        for (const requiredAttribute of this.requiredAttributes) {
            let attribute = this.element.getAttribute(requiredAttribute)
            if (attribute === null) {
                undefinedAttributes.push(requiredAttribute);
            }
        }
        if (undefinedAttributes.length > 0) {
            console.error(`Cannot create element without ['${undefinedAttributes.join('\',')}'] attributes (${this.iteration} of ${this.elementName} tag)`);
            return this.NO_REQUIRED_ATTRIBUTES;
        }
        return this.returnCode;
    }

    checkRecommendedAttributes() {
        let undefinedAttributes = [];
        for (const recommendedAttribute of this.recommendedAttributes) {
            let attribute = this.element.getAttribute(recommendedAttribute)
            if (attribute === null) {
                undefinedAttributes.push(recommendedAttribute);
            }
        }
        if (undefinedAttributes.length > 0) {
            console.warn(`It is recommended to set ['${undefinedAttributes.join('\',\'')}'] attributes (${this.iteration} of ${this.elementName} tag)`);
            return this.NO_RECOMMENDED_ATTRIBUTES;
        }
        return this.returnCode;
    }

    checkRecommendedChildren() {
        let undefinedChildren = [];
        for (const recommendedChild of this.recommendedChildren) {
            let children = this.element.getElementsByTagName(recommendedChild);
            if (children.length == 0) {
                undefinedChildren.push(recommendedChild);
            }
        }
        if (undefinedChildren.length > 0) {
            console.warn(`It is recommended to set ['${undefinedChildren.join('\',')}'] children (${this.iteration} of ${this.elementName} tag)`);
            return this.NO_RECOMMENDED_CHILDREN;
        }
        return this.returnCode;
    }

    checkRequiredChildren() {
        let undefinedChildren = [];
        for (const requiredChild of this.requiredChildren) {
            let children = this.element.getElementsByTagName(requiredChild);
            if (children.length == 0) {
                undefinedChildren.push(requiredChild);
            }
        }
        if (undefinedChildren.length > 0) {
            console.error(`Cannot create element without ['${undefinedChildren.join('\',')}'] children (${this.iteration} of ${this.elementName} tag)`);
            return this.NO_REQUIRED_CHILDREN;
        }
        return this.returnCode;
    }

    transferAttributes() {
        let attributes = this.element.attributes;
        for (const attribute of attributes) {
            this.newElement.setAttribute(attribute.name, attribute.value);
        }
    }

    transferChildren() {
        switch (this.renderStyle) {
            case this.RENDER_NONE: {
                break;
            }
            case this.RENDER_HIDDEN: {
                let length = this.element.children.length
                for (let i = 0; i < length; i++) {
                    let child = this.element.children[0];
                    child.style.display = "none";
                    this.newElement.appendChild(child);
                }
                break;
            }
            case this.RENDER_REWRITE_TEXT: {
                this.newElement.innerText = this.element.innerText;
                break;
            }
            case this.RENDER_REWRITE_HTML: {
                this.newElement.innerHTML = this.element.innerHTML;
                break;
            }
            case this.RENDER_IN_SUBCLASS: {
                this.render();
                break;
            }
            case this.RENDER_IN_SUBCLASS_AFTER_CONSTRUCTOR: {
                break;
            }
            default:
                break;
        }
    }

    createOutlook() {
        const preventDefault = this.newElement.getAttribute('prevent_default');
        if (preventDefault === null) {
            this.newElement.setAttribute("outlook", this.outlookName);
        }
        else {
            this.default_outlook = false;
            this.newElement.removeAttribute('prevent_default');
        }
    }

    render() {
        console.error("Render not implemented for this object");
    }

    createElement(nodeName, attributes, text = null) {
        let element = document.createElement(nodeName);

        if (attributes !== "") {
            const attributesArray = attributes.split(",");
            for (const attribute of attributesArray) {
                const parsedAttribute = attribute.split("=");
                element.setAttribute(parsedAttribute[0], parsedAttribute[1]);
            }
        }

        if (text !== null) {
            element.innerText = text;
        }

        return element;
    }
}