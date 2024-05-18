class PostButton extends RedirectButton {
    constructor(node, iterator, outlookName = "input_submit_default", elementName = "PostButton") {
        super(node, elementName, iterator, outlookName, ["request"], [], ["action","function", "controller", "text"], [])
        this.subrender();
    }

    subrender() {
        this.form.setAttribute("method", "POST");
        const controller = this.element.getAttribute('controller');
        if (controller !== null) {
            let input = this.createElement('input',`type=hidden,name=controller,value=${controller}`);
            this.form.removeAttribute("controller");
            this.form.appendChild(input);
        }
        
        const fun = this.element.getAttribute('function');
        if (fun !== null) {
            let input = this.createElement('input',`type=hidden,name=function,value=${fun}`);
            this.form.removeAttribute("function");
            this.form.appendChild(input);
        }
        let input = this.createElement('input',`type=submit,value=${this.form.getAttribute("text")}`);
        this.form.removeAttribute('text');
        const preventDefault = this.element.getAttribute('prevent_default');
        if (preventDefault === null) {
            input.setAttribute("outlook", this.outlookName);
        }
        else {
            input.removeAttribute('prevent_default');
        }
        if(this.element.getAttribute('disabled') !== null) {
            input.setAttribute('disabled','true');
            this.form.removeAttribute('disabled');
        }
        this.form.appendChild(input);
    }
}