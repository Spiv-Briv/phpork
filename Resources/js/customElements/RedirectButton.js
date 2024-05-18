class RedirectButton extends Element {
    form = document.createElement("form");
    constructor(
        node,
        elementName,
        iterator,
        outlookName = "redirect_button_default",
        recommendedAttributes = [],
        recommendedChildren = [],
        requiredAttributes = [],
        requiredChildren = []
    ) {
        super(node, elementName, iterator, outlookName, 5, recommendedAttributes, recommendedChildren, requiredAttributes, requiredChildren);
        this.render();
    }

    render() {
        let additionalInputs = this.element.children;
        const preventDefault = this.element.getAttribute('prevent_default');
        const disabled = this.element.getAttribute('disabled');
        for(const attribute of this.element.attributes) {
            this.form.setAttribute(attribute.nodeName, attribute.value);
        }
        if (preventDefault === null) {
            this.form.setAttribute("outlook", "form_default");
        }
        for (let i = additionalInputs.length-1; i>=0; i--) {
            const input = additionalInputs[0];
            this.form.appendChild(input);
        }
        const requests = this.form.getAttribute('request');
        if (requests !== null) {
            const parsedRequests = requests.split(',');
            for (const request of parsedRequests) {
                const arrayRequest = request.split("=");
                if(arrayRequest.length<2) {
                    console.error("All request should have corresponding value");
                    return;
                }
                let input = this.createElement("input",`type=hidden,name=${arrayRequest[0]},value=${arrayRequest[1]}`);
                this.form.appendChild(input);
            }
            this.form.removeAttribute('request');
        }
        this.newElement.replaceWith(this.form);
    }
}